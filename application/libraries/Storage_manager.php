<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Transaction companion for application-managed uploads.
 *
 * Database changes remain the source of truth: callers first persist the new
 * filename, then ask this service to remove the previous file only when it is
 * no longer referenced by another record.
 */
class Storage_manager
{
    private $CI;

    private $references = array(
        'uploads/user/' => array(array('aauth_users', 'avatar'), array('data_notaris', 'foto')),
        'uploads/data_notaris/' => array(array('data_notaris', 'foto')),
        'uploads/laporan/' => array(array('laporan', 'Laporan')),
        'uploads/laporan_bulanan/' => array(array('laporan_bulanan', 'file_laporan')),
        'uploads/blog/' => array(array('blog', 'image', 'csv')),
    );

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function move_from_temp($uuid, $filename, $directory)
    {
        $uuid = basename(trim((string) $uuid));
        $filename = basename(trim((string) $filename));
        $directory = $this->managed_directory($directory);
        if ($uuid === '' || $filename === '' || $directory === null) {
            return false;
        }

        $source = FCPATH . 'uploads/tmp/' . $uuid . '/' . $filename;
        $target_directory = FCPATH . $directory;
        if (!is_file($source)) {
            return false;
        }
        if (!is_dir($target_directory) && !mkdir($target_directory, 0755, true)) {
            return false;
        }

        $target_name = date('YmdHis') . '-' . substr(sha1(uniqid('', true)), 0, 8) . '-' . $filename;
        $target = $target_directory . $target_name;
        if (is_file($target) || !@rename($source, $target)) {
            return false;
        }

        $temp_directory = dirname($source);
        if (is_dir($temp_directory)) {
            @rmdir($temp_directory);
        }
        return is_file($target) ? $target_name : false;
    }

    public function supports($directory)
    {
        return $this->managed_directory($directory) !== null;
    }

    public function delete_if_unreferenced($directory, $filename)
    {
        $directory = $this->managed_directory($directory);
        $filename = basename(trim((string) $filename));
        if ($directory === null || $filename === '' || $this->is_protected($directory, $filename)) {
            return true;
        }

        foreach ($this->references[$directory] as $reference) {
            list($table, $field) = $reference;
            if (!$this->CI->db->table_exists($table) || !$this->CI->db->field_exists($field, $table)) {
                continue;
            }
            $mode = isset($reference[2]) ? $reference[2] : 'scalar';
            if ($mode === 'csv') {
                $escaped = $this->CI->db->escape($filename);
                $this->CI->db->where("FIND_IN_SET(" . $escaped . ", REPLACE(" . $field . ", ' ', '')) > 0", null, false);
            } else {
                $this->CI->db->where($field, $filename);
            }
            if ($this->CI->db->count_all_results($table) > 0) {
                return true;
            }
        }

        return $this->delete_managed_file($directory, $filename);
    }

    public function delete_managed_file($directory, $filename)
    {
        $directory = $this->managed_directory($directory);
        $filename = basename(trim((string) $filename));
        if ($directory === null || $filename === '' || $this->is_protected($directory, $filename)) {
            return true;
        }

        $base = realpath(FCPATH . $directory);
        if ($base === false) {
            return true;
        }
        $path = $base . DIRECTORY_SEPARATOR . $filename;
        if (!is_file($path)) {
            return true;
        }

        return @unlink($path);
    }

    /** Promote a Data Notaris upload into the linked account avatar source. */
    public function promote_notary_photo($registry_id)
    {
        $row = $this->CI->db
            ->select('data_notaris.id_notaris, data_notaris.foto, data_notaris.user_id, users.avatar')
            ->from('data_notaris')
            ->join('aauth_users users', 'users.id = data_notaris.user_id', 'left')
            ->where('data_notaris.id_notaris', (int) $registry_id)
            ->get()
            ->row();
        if (!$row || empty($row->foto)) {
            return true;
        }

        $source_name = basename((string) $row->foto);
        $source = FCPATH . 'uploads/data_notaris/' . $source_name;
        if (!is_file($source)) {
            return false;
        }

        $hash = hash_file('sha256', $source);
        $target_name = $this->find_file_by_hash('uploads/user/', $hash);
        $created = false;
        if ($target_name === null) {
            $extension = strtolower(pathinfo($source_name, PATHINFO_EXTENSION));
            $identity = (int) $row->user_id > 0 ? 'user-' . (int) $row->user_id : 'registry-' . (int) $row->id_notaris;
            $target_name = 'notary-' . $identity . '-' . substr($hash, 0, 16)
                . ($extension !== '' ? '.' . $extension : '');
            $target = FCPATH . 'uploads/user/' . $target_name;
            if (!is_dir(dirname($target)) && !mkdir(dirname($target), 0755, true)) {
                return false;
            }
            if (!is_file($target) && !@copy($source, $target)) {
                return false;
            }
            $created = true;
        }

        $this->CI->db->trans_begin();
        $old_avatar = isset($row->avatar) ? $row->avatar : null;
        $ok = true;
        if ((int) $row->user_id > 0) {
            $ok = $this->CI->db->where('id', (int) $row->user_id)->update('aauth_users', array('avatar' => $target_name));
        }
        $registry_photo = (int) $row->user_id > 0 ? null : $target_name;
        $ok = $ok && $this->CI->db->where('id_notaris', (int) $registry_id)->update('data_notaris', array('foto' => $registry_photo));
        if ($ok && $this->CI->db->trans_status() !== false) {
            $this->CI->db->trans_commit();
            if ($old_avatar) {
                $this->delete_if_unreferenced('uploads/user/', $old_avatar);
            }
            $this->delete_if_unreferenced('uploads/data_notaris/', $source_name);
            return true;
        }

        $this->CI->db->trans_rollback();
        if ($created) {
            $this->delete_if_unreferenced('uploads/user/', $target_name);
        }
        return false;
    }

    private function find_file_by_hash($directory, $hash)
    {
        $directory = $this->managed_directory($directory);
        if ($directory === null || !is_dir(FCPATH . $directory)) {
            return null;
        }
        foreach (glob(FCPATH . $directory . '*') ?: array() as $path) {
            if (is_file($path) && hash_file('sha256', $path) === $hash) {
                return basename($path);
            }
        }
        return null;
    }

    private function managed_directory($directory)
    {
        $directory = trim(str_replace('\\', '/', (string) $directory), '/') . '/';
        return array_key_exists($directory, $this->references) ? $directory : null;
    }

    private function is_protected($directory, $filename)
    {
        return $directory === 'uploads/user/' && strtolower($filename) === 'default.png';
    }
}
