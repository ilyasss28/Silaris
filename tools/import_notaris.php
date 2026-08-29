<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "This importer may only be run from the command line.\n");
    exit(1);
}

$source = $argv[1] ?? '';
$apply = in_array('--apply', $argv, true);
if ($source === '' || !is_file($source)) {
    fwrite(STDERR, "Usage: php tools/import_notaris.php <file.xlsx> [--apply]\n");
    exit(1);
}

function cellValue(DOMElement $cell, DOMXPath $xpath, array $shared): string
{
    $type = $cell->getAttribute('t');
    if ($type === 'inlineStr') {
        $parts = [];
        foreach ($xpath->query('.//x:t', $cell) as $text) $parts[] = $text->textContent;
        return trim(implode('', $parts));
    }
    $node = $xpath->query('./x:v', $cell)->item(0);
    $value = $node ? trim($node->textContent) : '';
    return $type === 's' && $value !== '' ? trim($shared[(int) $value] ?? '') : $value;
}

function readSheet(ZipArchive $zip, int $number, array $shared): array
{
    $xml = $zip->getFromName("xl/worksheets/sheet{$number}.xml");
    if ($xml === false) return [];
    $document = new DOMDocument();
    $document->loadXML($xml);
    $xpath = new DOMXPath($document);
    $xpath->registerNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
    $rows = [];
    foreach ($xpath->query('//x:sheetData/x:row') as $row) {
        $values = [];
        foreach ($xpath->query('./x:c', $row) as $cell) {
            preg_match('/^[A-Z]+/', $cell->getAttribute('r'), $match);
            $values[$match[0]] = cellValue($cell, $xpath, $shared);
        }
        if (array_filter($values, static fn ($value) => trim((string) $value) !== '')) $rows[] = $values;
    }
    return $rows;
}

function canonicalName(string $name): string
{
    $name = mb_strtolower(trim($name), 'UTF-8');
    $name = preg_replace('/\b(s\.?\s*h|m\.?\s*kn|s\.?\s*h\.?\s*i|dr|hj|h)\b/u', ' ', $name);
    $name = trim(preg_replace('/[^a-z0-9]+/u', ' ', $name));
    return preg_replace('/\b(?:muh|muhamad)\b/u', 'muhammad', $name);
}

function phoneDigits(?string $phone): string
{
    return preg_replace('/\D+/', '', (string) $phone);
}

function cleanText(string $value): ?string
{
    $value = trim(preg_replace('/\s+/u', ' ', $value));
    return $value === '' ? null : $value;
}

function excelDate(string $value): ?string
{
    $value = trim($value);
    if ($value === '') return null;
    if (is_numeric($value)) {
        $serial = (float) $value;
        if ($serial < 1 || $serial > 100000) return null;
        $date = new DateTimeImmutable('1899-12-30');
        $formatted = $date->modify('+' . (int) floor($serial) . ' days')->format('Y-m-d');
        return (int) substr($formatted, 0, 4) >= 1900 ? $formatted : null;
    }
    $months = [
        'januari'=>'January','februari'=>'February','maret'=>'March','april'=>'April',
        'mei'=>'May','juni'=>'June','juli'=>'July','agustus'=>'August','september'=>'September',
        'oktober'=>'October','november'=>'November','desember'=>'December'
    ];
    $translated = str_ireplace(array_keys($months), array_values($months), $value);
    $timestamp = strtotime($translated);
    if ($timestamp === false) return null;
    $formatted = date('Y-m-d', $timestamp);
    $year = (int) substr($formatted, 0, 4);
    return $year >= 1900 && $year <= 2200 ? $formatted : null;
}

function gender(string $value): ?string
{
    $value = mb_strtolower(trim($value), 'UTF-8');
    if ($value === 'pria' || $value === 'laki-laki') return 'Laki-Laki';
    if ($value === 'wanita' || $value === 'perempuan') return 'Perempuan';
    return null;
}

function region(string $value): ?string
{
    $value = mb_strtoupper(trim($value), 'UTF-8');
    $value = preg_replace('/\s+/u', ' ', $value);
    if ($value === 'KOTA BAU BAU' || $value === 'BAU BAU') $value = 'KOTA BAUBAU';
    if ($value !== '' && !str_starts_with($value, 'KOTA ') && !str_starts_with($value, 'KABUPATEN ')) {
        $value = 'KABUPATEN ' . $value;
    }
    return $value === '' ? null : $value;
}

$zip = new ZipArchive();
if ($zip->open($source) !== true) throw new RuntimeException('Unable to open workbook.');
$shared = [];
$sharedXml = $zip->getFromName('xl/sharedStrings.xml');
if ($sharedXml !== false) {
    $document = new DOMDocument();
    $document->loadXML($sharedXml);
    $xpath = new DOMXPath($document);
    $xpath->registerNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
    foreach ($xpath->query('//x:si') as $item) {
        $parts = [];
        foreach ($xpath->query('.//x:t', $item) as $text) $parts[] = $text->textContent;
        $shared[] = implode('', $parts);
    }
}
$mainRows = readSheet($zip, 1, $shared);
$extraRows = readSheet($zip, 2, $shared);
$zip->close();
array_shift($mainRows);
array_shift($extraRows);

$records = [];
foreach ($mainRows as $row) {
    $name = cleanText($row['B'] ?? '');
    if (!$name) continue;
    $records[] = [
        'source'=>'Sheet1', 'nama_notaris'=>$name, 'tanggal_lahir'=>excelDate($row['C'] ?? ''),
        'tempat_lahir'=>cleanText($row['D'] ?? ''), 'npwp'=>cleanText($row['E'] ?? ''),
        'nomor_ktp'=>cleanText($row['F'] ?? ''), 'wilayah'=>region($row['H'] ?? ''),
        'jenis_kelamin'=>gender($row['I'] ?? ''), 'alamat_kantor'=>cleanText($row['J'] ?? ''),
        'no_telepon'=>cleanText($row['K'] ?? ''), 'nomor_bap'=>cleanText($row['L'] ?? ''),
        'tanggal_bap'=>excelDate($row['M'] ?? ''), 'pemegang_protokol'=>cleanText($row['N'] ?? ''),
        'status_notaris'=>cleanText($row['O'] ?? ''), 'surat_keputusan'=>cleanText($row['P'] ?? '')
    ];
}
foreach ($extraRows as $row) {
    $name = cleanText($row['B'] ?? '');
    if (!$name) continue;
    $extra = ['source'=>'Sheet2', 'nama_notaris'=>$name, 'wilayah'=>region($row['C'] ?? ''), 'no_telepon'=>trim($row['D'] ?? '', " \t\n\r\0\x0B[]\"") ?: null];
    $key = canonicalName($name);
    $indices = [];
    foreach ($records as $index => $record) {
        if (canonicalName($record['nama_notaris']) === $key) $indices[] = $index;
    }
    if (count($indices) === 1) {
        foreach ($extra as $column => $value) {
            if ($column !== 'source' && $value !== null && $value !== '') $records[$indices[0]][$column] = $value;
        }
    } else {
        $records[] = $extra;
    }
}

$db = new mysqli('localhost', 'root', '', 'silaris');
if ($db->connect_error) throw new RuntimeException($db->connect_error);
$db->set_charset('utf8mb4');
$existing = [];
$result = $db->query('SELECT * FROM data_notaris ORDER BY id_notaris');
while ($row = $result->fetch_assoc()) $existing[canonicalName((string) $row['nama_notaris'])][] = $row;

$stats = ['source'=>count($records), 'insert'=>0, 'update'=>0, 'ambiguous'=>0, 'unchanged'=>0];
$plan = [];
foreach ($records as $record) {
    $key = canonicalName($record['nama_notaris']);
    $matches = $existing[$key] ?? [];
    if (!$matches) {
        $candidates = [];
        $limit = strlen($key) >= 8 ? 2 : 1;
        foreach ($existing as $existingKey => $rows) {
            $distance = levenshtein($key, $existingKey);
            if ($distance <= $limit) {
                foreach ($rows as $row) $candidates[] = ['distance'=>$distance, 'row'=>$row];
            }
        }
        usort($candidates, static fn ($a, $b) => $a['distance'] <=> $b['distance']);
        if ($candidates && (count($candidates) === 1 || $candidates[0]['distance'] < $candidates[1]['distance'])) {
            $matches = [$candidates[0]['row']];
        }
    }
    if (count($matches) > 1 && phoneDigits($record['no_telepon'] ?? null) !== '') {
        $phone = phoneDigits($record['no_telepon']);
        $phoneMatches = array_values(array_filter($matches, static fn ($row) => phoneDigits($row['no_telepon'] ?? null) === $phone));
        if (count($phoneMatches) === 1) $matches = $phoneMatches;
    }
    if (count($matches) > 1 && !empty($record['tanggal_lahir'])) {
        $matches = array_values(array_filter($matches, static fn ($row) => substr((string) $row['tanggal_lahir'], 0, 10) === $record['tanggal_lahir']));
    }
    if (count($matches) > 1) {
        $stats['ambiguous']++;
        $plan[] = ['action'=>'AMBIGUOUS', 'record'=>$record, 'id'=>null];
        continue;
    }
    $action = count($matches) === 1 ? 'UPDATE' : 'INSERT';
    $stats[strtolower($action)]++;
    $plan[] = ['action'=>$action, 'record'=>$record, 'id'=>$matches[0]['id_notaris'] ?? null];
    if ($action === 'INSERT') $existing[$key][] = ['id_notaris'=>null, 'nama_notaris'=>$record['nama_notaris'], 'tanggal_lahir'=>$record['tanggal_lahir'] ?? null];
}

echo json_encode($stats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), PHP_EOL;
foreach ($plan as $item) {
    if ($item['action'] !== 'UPDATE') echo $item['action'], "\t", $item['record']['source'], "\t", $item['record']['nama_notaris'], PHP_EOL;
}
if (!$apply) {
    echo "DRY RUN: no database changes were made.\n";
    exit($stats['ambiguous'] > 0 ? 2 : 0);
}
if ($stats['ambiguous'] > 0) throw new RuntimeException('Import cancelled because ambiguous matches require review.');

$columns = [
    'npwp'=>'VARCHAR(50) NULL', 'nomor_ktp'=>'VARCHAR(32) NULL', 'nomor_bap'=>'VARCHAR(150) NULL',
    'tanggal_bap'=>'DATE NULL', 'pemegang_protokol'=>'VARCHAR(150) NULL', 'status_notaris'=>'VARCHAR(50) NULL'
];
foreach ($columns as $name => $definition) {
    $check = $db->query("SHOW COLUMNS FROM data_notaris LIKE '{$name}'");
    if ($check->num_rows === 0) $db->query("ALTER TABLE data_notaris ADD COLUMN {$name} {$definition}");
}
$db->query('ALTER TABLE data_notaris MODIFY COLUMN alamat_kantor TEXT NULL');

$regionCodes = [];
$regions = $db->query('SELECT kd_wilayah, UPPER(nama_wilayah) nama FROM wil');
while ($row = $regions->fetch_assoc()) $regionCodes[$row['nama']] = $row['kd_wilayah'];
$db->begin_transaction();
try {
    foreach ($plan as $item) {
        $record = $item['record'];
        unset($record['source']);
        $record = array_filter($record, static fn ($value) => $value !== null && $value !== '');
        if (isset($record['wilayah'])) $record['kode_wilayah'] = $regionCodes[mb_strtoupper($record['wilayah'], 'UTF-8')] ?? null;
        if ($item['action'] === 'INSERT') {
            $record += ['password'=>'', 'long'=>''];
            $columnsSql = implode(',', array_map(static fn ($column) => "`{$column}`", array_keys($record)));
            $valuesSql = implode(',', array_fill(0, count($record), '?'));
            $stmt = $db->prepare("INSERT INTO data_notaris ({$columnsSql}) VALUES ({$valuesSql})");
        } else {
            $sets = implode(',', array_map(static fn ($column) => "`{$column}`=?", array_keys($record)));
            $stmt = $db->prepare("UPDATE data_notaris SET {$sets} WHERE id_notaris=?");
            $record['id_notaris'] = $item['id'];
        }
        $values = array_values($record);
        $types = str_repeat('s', count($values));
        $stmt->bind_param($types, ...$values);
        $stmt->execute();
        $stmt->close();
    }
    $db->commit();
    echo "IMPORT COMPLETE\n";
} catch (Throwable $error) {
    $db->rollback();
    throw $error;
}
