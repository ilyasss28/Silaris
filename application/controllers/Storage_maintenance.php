<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** CLI-only audit and consolidation for profile photo storage. */
class Storage_maintenance extends CI_Controller
{
    private $photo_directories = array(
        'uploads/user/',
        'uploads/data_notaris/',
        'assets/uploads/foto_profil/',
        'assets/uploads/foto_user/',
    );

    public function __construct()
    {
        parent::__construct();
        if (!is_cli()) {
            show_404();
        }
        $this->load->library('storage_manager');
    }

    public function profile_photos($mode = 'audit')
    {
        $before = $this->duplicate_summary();
        $this->print_summary('before', $before);
        if ($mode !== 'apply') {
            echo 'Dry run only. Use: php index.php storage_maintenance profile_photos apply' . PHP_EOL;
            return;
        }

        $result = $this->consolidate_registry_photos();
        $result['normalized_missing_avatars'] = $this->normalize_missing_avatars();
        $result['deduplicated_user_references'] = $this->deduplicate_user_references();
        $result['deleted_duplicate_files'] = $this->delete_duplicate_files();
        $result['deleted_unreferenced_photos'] = $this->delete_unreferenced_profile_files();
        $after = $this->duplicate_summary();
        foreach ($result as $key => $value) {
            echo $key . '=' . (int) $value . PHP_EOL;
        }
        $this->print_summary('after', $after);
    }

    public function self_test()
    {
        $uuid = 'storage-self-test-' . substr(sha1(uniqid('', true)), 0, 10);
        $name = 'test-document.txt';
        $temporary = FCPATH . 'uploads/tmp/' . $uuid;
        if (!is_dir($temporary) && !mkdir($temporary, 0755, true)) {
            echo 'storage_self_test=FAILED_CREATE_TEMP' . PHP_EOL;
            return;
        }
        file_put_contents($temporary . DIRECTORY_SEPARATOR . $name, 'storage-lifecycle-test');
        $stored = $this->storage_manager->move_from_temp($uuid, $name, 'uploads/laporan/');
        $created = $stored && is_file(FCPATH . 'uploads/laporan/' . $stored);
        $deleted = $stored ? $this->storage_manager->delete_if_unreferenced('uploads/laporan/', $stored) : false;
        $removed = $stored && !is_file(FCPATH . 'uploads/laporan/' . $stored);
        $protected = true;
        foreach ($this->db->select('Laporan')->where("TRIM(COALESCE(Laporan, '')) != ''", null, false)->get('laporan')->result() as $row) {
            $path = FCPATH . 'uploads/laporan/' . basename((string) $row->Laporan);
            if (!is_file($path)) continue;
            $this->storage_manager->delete_if_unreferenced('uploads/laporan/', $row->Laporan);
            $protected = is_file($path);
            break;
        }
        echo 'storage_self_test=' . ($created && $deleted && $removed && $protected ? 'OK' : 'FAILED') . PHP_EOL;
    }

    public function excel_self_test()
    {
        $this->load->library('silaris_excel');
        $path = $this->silaris_excel->create_file(
            'Pengujian Excel SILARIS',
            'Format lembar kerja siap cetak',
            array('No.', 'Nama Notaris', 'Nomor Telepon', 'Wilayah'),
            array(array(1, 'Contoh Notaris, S.H., M.Kn.', '081234567890', 'Kota Kendari'))
        );
        try {
            $workbook = PHPExcel_IOFactory::load($path);
            $sheet = $workbook->getActiveSheet();
            $checks = array(
                'title' => $sheet->getCell('A1')->getValue() === 'PENGUJIAN EXCEL SILARIS',
                'body' => $sheet->getCell('B5')->getValue() === 'Contoh Notaris, S.H., M.Kn.',
                'fit_to_width' => (int) $sheet->getPageSetup()->getFitToWidth() === 1,
                'print_area' => $sheet->getPageSetup()->getPrintArea() === 'A1:D5',
            );
            $valid = !in_array(false, $checks, true);
            echo 'excel_self_test=' . ($valid ? 'OK' : 'FAILED') . PHP_EOL;
            if (!$valid) {
                foreach ($checks as $name => $passed) echo $name . '=' . ($passed ? 'OK' : 'FAILED') . PHP_EOL;
                echo 'actual_title=' . $sheet->getCell('A1')->getValue() . PHP_EOL;
                echo 'actual_subtitle=' . $sheet->getCell('A2')->getValue() . PHP_EOL;
                echo 'actual_header=' . $sheet->getCell('A4')->getValue() . PHP_EOL;
                foreach (array('A1', 'B1', 'C1', 'D1', 'A2', 'B2', 'C2', 'D2') as $cell) {
                    $value = $sheet->getCell($cell)->getValue();
                    if ($value !== null && $value !== '') echo $cell . '=' . $value . PHP_EOL;
                }
            }
        } catch (Throwable $exception) {
            echo 'excel_self_test=FAILED ' . $exception->getMessage() . PHP_EOL;
        }
        if (is_file($path)) @unlink($path);
    }

    public function audit_references()
    {
        $sets = array(
            array('users', 'aauth_users', 'avatar', 'uploads/user/'),
            array('notary_photos', 'data_notaris', 'foto', 'profile'),
            array('reports', 'laporan', 'Laporan', 'uploads/laporan/'),
            array('monthly_reports', 'laporan_bulanan', 'file_laporan', 'uploads/laporan_bulanan/'),
            array('blog_images', 'blog', 'image', 'uploads/blog/', 'csv'),
        );
        foreach ($sets as $set) {
            list($label, $table, $field, $directory) = $set;
            $mode = isset($set[4]) ? $set[4] : 'scalar';
            $rows = $this->db->select($field)->where("TRIM(COALESCE(`$field`, '')) != ''", null, false)->get($table)->result();
            $missing = 0;
            $references = 0;
            foreach ($rows as $row) {
                $names = $mode === 'csv'
                    ? array_filter(array_map('trim', explode(',', (string) $row->{$field})))
                    : array($row->{$field});
                foreach ($names as $name) {
                    $references++;
                    $exists = $directory === 'profile'
                        ? $this->resolve_registry_photo($name) !== null
                        : is_file(FCPATH . $directory . basename((string) $name));
                    if (!$exists) $missing++;
                }
            }
            echo $label . '_references=' . $references . PHP_EOL;
            echo $label . '_missing=' . $missing . PHP_EOL;
        }
    }

    /** Inspect one stored report without exposing its content. */
    public function document_status($filename = '')
    {
        $filename = basename(rawurldecode(trim((string) $filename)));
        if ($filename === '') {
            echo 'document_status=FILENAME_REQUIRED' . PHP_EOL;
            return;
        }

        $sources = array(
            array('laporan', 'id', 'Laporan', 'uploads/laporan/'),
            array('laporan_bulanan', 'id_laporan_bulanan', 'file_laporan', 'uploads/laporan_bulanan/'),
        );
        $found = false;
        foreach ($sources as $source) {
            list($table, $id_field, $file_field, $directory) = $source;
            $select = array($id_field);
            if ($this->db->field_exists('nama_notaris', $table)) $select[] = 'nama_notaris';
            if ($this->db->field_exists('Tanggal_Laporan', $table)) $select[] = 'Tanggal_Laporan';
            if ($this->db->field_exists('tanggal_laporan', $table)) $select[] = 'tanggal_laporan';
            foreach ($this->db->select($select)->where($file_field, $filename)->get($table)->result() as $row) {
                $found = true;
                $path = FCPATH . $directory . $filename;
                echo 'table=' . $table . PHP_EOL;
                echo 'id=' . (int) $row->{$id_field} . PHP_EOL;
                if (isset($row->nama_notaris)) echo 'notary=' . $row->nama_notaris . PHP_EOL;
                if (isset($row->Tanggal_Laporan)) echo 'report_date=' . $row->Tanggal_Laporan . PHP_EOL;
                if (isset($row->tanggal_laporan)) echo 'report_date=' . $row->tanggal_laporan . PHP_EOL;
                echo 'exists=' . (is_file($path) ? 'yes' : 'no') . PHP_EOL;
                if (is_file($path)) {
                    echo 'bytes=' . filesize($path) . PHP_EOL;
                    echo 'sha256=' . hash_file('sha256', $path) . PHP_EOL;
                    echo 'pdf_header=' . (strncmp((string) file_get_contents($path, false, null, 0, 5), '%PDF-', 5) === 0 ? 'valid' : 'invalid') . PHP_EOL;
                }
            }
        }
        if (!$found) echo 'document_status=NOT_REFERENCED' . PHP_EOL;
    }

    private function consolidate_registry_photos()
    {
        $result = array('promoted_registry_photos' => 0, 'cleared_registry_references' => 0, 'unresolved_registry_photos' => 0);
        $retired = array();
        $rows = $this->db
            ->select('data_notaris.id_notaris, data_notaris.user_id, data_notaris.foto, users.avatar')
            ->from('data_notaris')
            ->join('aauth_users users', 'users.id=data_notaris.user_id', 'left')
            ->where("TRIM(COALESCE(data_notaris.foto, '')) != ''", null, false)
            ->get()
            ->result();

        foreach ($rows as $row) {
            $registry_path = $this->resolve_registry_photo($row->foto);
            $avatar_path = $this->resolve_account_avatar($row->avatar ?? '');
            if ((int) $row->user_id > 0 && $avatar_path !== null) {
                if ($this->db->where('id_notaris', (int) $row->id_notaris)->update('data_notaris', array('foto' => null))) {
                    $result['cleared_registry_references']++;
                    if ($registry_path !== null) $retired[] = $registry_path;
                }
                continue;
            }

            if ($registry_path === null) {
                $result['unresolved_registry_photos']++;
                continue;
            }
            $staged = $this->stage_registry_photo($row->id_notaris, $registry_path);
            if ($staged === null) {
                $result['unresolved_registry_photos']++;
                continue;
            }
            if (!$this->db->where('id_notaris', (int) $row->id_notaris)->update('data_notaris', array('foto' => $staged))) {
                $this->storage_manager->delete_if_unreferenced('uploads/data_notaris/', $staged);
                $result['unresolved_registry_photos']++;
                continue;
            }
            if ($this->storage_manager->promote_notary_photo((int) $row->id_notaris)) {
                $result['promoted_registry_photos']++;
                if ($registry_path !== FCPATH . 'uploads/data_notaris/' . $staged) $retired[] = $registry_path;
            } else {
                $result['unresolved_registry_photos']++;
            }
        }

        foreach (array_unique($retired) as $path) {
            $this->delete_retired_registry_file($path);
        }
        return $result;
    }

    private function stage_registry_photo($registry_id, $source)
    {
        $source = realpath($source);
        if ($source === false || !$this->inside_photo_directories($source)) return null;
        $data_root = realpath(FCPATH . 'uploads/data_notaris/');
        if ($data_root !== false && strpos($source, $data_root . DIRECTORY_SEPARATOR) === 0) return basename($source);

        $hash = hash_file('sha256', $source);
        $extension = strtolower(pathinfo($source, PATHINFO_EXTENSION));
        $name = 'registry-' . (int) $registry_id . '-' . substr($hash, 0, 16) . ($extension ? '.' . $extension : '');
        $target = FCPATH . 'uploads/data_notaris/' . $name;
        if (!is_dir(dirname($target)) && !mkdir(dirname($target), 0755, true)) return null;
        if (!is_file($target) && !@copy($source, $target)) return null;
        return is_file($target) ? $name : null;
    }

    private function deduplicate_user_references()
    {
        $groups = array();
        $rows = $this->db->select('id, avatar')->get('aauth_users')->result();
        foreach ($rows as $row) {
            $path = $this->resolve_account_avatar($row->avatar);
            if ($path === null) continue;
            $groups[hash_file('sha256', $path)][] = array('id' => (int) $row->id, 'name' => basename($path));
        }

        $updated = 0;
        foreach ($groups as $items) {
            $names = array_values(array_unique(array_column($items, 'name')));
            if (count($names) < 2) continue;
            sort($names, SORT_NATURAL | SORT_FLAG_CASE);
            $canonical = $names[0];
            foreach ($items as $item) {
                if ($item['name'] === $canonical) continue;
                if ($this->db->where('id', $item['id'])->update('aauth_users', array('avatar' => $canonical))) $updated++;
            }
            foreach (array_slice($names, 1) as $name) {
                $this->storage_manager->delete_if_unreferenced('uploads/user/', $name);
            }
        }
        return $updated;
    }

    private function normalize_missing_avatars()
    {
        $updated = 0;
        foreach ($this->db->select('id, avatar')->get('aauth_users')->result() as $row) {
            $name = basename(trim((string) $row->avatar));
            if ($name === '' || strtolower($name) === 'default.png' || is_file(FCPATH . 'uploads/user/' . $name)) continue;
            if ($this->db->where('id', (int) $row->id)->update('aauth_users', array('avatar' => 'default.png'))) $updated++;
        }
        return $updated;
    }

    private function delete_duplicate_files()
    {
        $inventory = $this->inventory();
        $groups = array();
        foreach ($inventory as $file) $groups[$file['hash']][] = $file;
        $referenced = $this->referenced_paths();
        $deleted = 0;
        foreach ($groups as $files) {
            if (count($files) < 2) continue;
            usort($files, function ($a, $b) use ($referenced) {
                $ar = isset($referenced[$a['path']]) ? 0 : 1;
                $br = isset($referenced[$b['path']]) ? 0 : 1;
                if ($ar !== $br) return $ar - $br;
                return $a['priority'] - $b['priority'];
            });
            $keep = $files[0]['path'];
            foreach (array_slice($files, 1) as $file) {
                if ($file['path'] === $keep || isset($referenced[$file['path']])) continue;
                if ($this->safe_unlink($file['path'])) $deleted++;
            }
        }
        return $deleted;
    }

    private function delete_unreferenced_profile_files()
    {
        $referenced = $this->referenced_paths();
        $deleted = 0;
        foreach ($this->inventory() as $file) {
            if (isset($referenced[$file['path']]) || strtolower(basename($file['path'])) === 'default.png') continue;
            if ($this->safe_unlink($file['path'])) $deleted++;
        }
        return $deleted;
    }

    private function referenced_paths()
    {
        $paths = array();
        foreach ($this->db->select('avatar')->get('aauth_users')->result() as $row) {
            $path = $this->resolve_account_avatar($row->avatar);
            if ($path !== null) $paths[$path] = true;
        }
        foreach ($this->db->select('foto')->where("TRIM(COALESCE(foto, '')) != ''", null, false)->get('data_notaris')->result() as $row) {
            $path = $this->resolve_registry_photo($row->foto);
            if ($path !== null) $paths[$path] = true;
        }
        return $paths;
    }

    private function resolve_account_avatar($name)
    {
        $name = basename(trim((string) $name));
        if ($name === '' || strtolower($name) === 'default.png') return null;
        $path = realpath(FCPATH . 'uploads/user/' . $name);
        return $path !== false && $this->inside_photo_directories($path) ? $path : null;
    }

    private function resolve_registry_photo($name)
    {
        $name = basename(trim((string) $name));
        if ($name === '') return null;
        foreach (array('uploads/user/', 'uploads/data_notaris/', 'assets/uploads/foto_profil/', 'assets/uploads/foto_user/') as $directory) {
            $path = realpath(FCPATH . $directory . $name);
            if ($path !== false && is_file($path) && $this->inside_photo_directories($path)) return $path;
        }
        return null;
    }

    private function delete_retired_registry_file($path)
    {
        $real = realpath($path);
        if ($real === false || !$this->inside_photo_directories($real)) return false;
        $name = basename($real);
        if ($this->db->where('foto', $name)->count_all_results('data_notaris') > 0) return false;
        return $this->safe_unlink($real);
    }

    private function safe_unlink($path)
    {
        $real = realpath($path);
        if ($real === false || !$this->inside_photo_directories($real)) return false;
        if (strtolower(basename($real)) === 'default.png') return false;
        return is_file($real) ? @unlink($real) : false;
    }

    private function inside_photo_directories($path)
    {
        foreach ($this->photo_directories as $directory) {
            $root = realpath(FCPATH . $directory);
            if ($root !== false && ($path === $root || strpos($path, $root . DIRECTORY_SEPARATOR) === 0)) return true;
        }
        return false;
    }

    private function inventory()
    {
        $result = array();
        foreach ($this->photo_directories as $priority => $directory) {
            $root = realpath(FCPATH . $directory);
            if ($root === false) continue;
            foreach (glob($root . DIRECTORY_SEPARATOR . '*') ?: array() as $path) {
                if (!is_file($path)) continue;
                $real = realpath($path);
                if ($real === false || !$this->inside_photo_directories($real)) continue;
                $result[] = array('path' => $real, 'hash' => hash_file('sha256', $real), 'size' => filesize($real), 'priority' => $priority);
            }
        }
        return $result;
    }

    private function duplicate_summary()
    {
        $groups = array();
        foreach ($this->inventory() as $file) $groups[$file['hash']][] = $file;
        $summary = array('files' => 0, 'duplicate_groups' => 0, 'redundant_files' => 0, 'redundant_bytes' => 0);
        foreach ($groups as $files) {
            $summary['files'] += count($files);
            if (count($files) < 2) continue;
            $summary['duplicate_groups']++;
            $summary['redundant_files'] += count($files) - 1;
            usort($files, function ($a, $b) { return $a['priority'] - $b['priority']; });
            foreach (array_slice($files, 1) as $file) $summary['redundant_bytes'] += $file['size'];
        }
        return $summary;
    }

    private function print_summary($prefix, array $summary)
    {
        foreach ($summary as $key => $value) echo $prefix . '_' . $key . '=' . (int) $value . PHP_EOL;
    }
}
