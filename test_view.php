<?php
define('BASEPATH', __DIR__);
define('ENVIRONMENT', 'development');
require 'application/config/database.php';
$mysqli = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);
if ($mysqli->connect_errno) {
    echo "Conn error: " . $mysqli->connect_error . "\n";
    exit;
}
$res = $mysqli->query('SELECT * FROM jumlah_per_wilayah LIMIT 1');
if (!$res) {
    echo "Query error: " . $mysqli->error . "\n";
} else {
    $row = $res->fetch_assoc();
    print_r($row);
}
?>
