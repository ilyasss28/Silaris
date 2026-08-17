<?php
define('BASEPATH', __DIR__);
define('ENVIRONMENT', 'development');
require 'application/config/database.php';

$mysqli = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);
if ($mysqli->connect_errno) {
    echo 'Connection error: ' . $mysqli->connect_error . "\n";
    exit(1);
}

// Recreate jumlah_per_wilayah view without DEFINER
$mysqli->query('DROP VIEW IF EXISTS `jumlah_per_wilayah`');
$create = "CREATE VIEW `jumlah_per_wilayah` AS 
SELECT `data_notaris`.`wilayah` AS `wilayah`, 
       COUNT(`data_notaris`.`nama_notaris`) AS `jumlah`, 
       `data_notaris`.`kode_wilayah` AS `kode_wilayah`
FROM `data_notaris`
GROUP BY `data_notaris`.`wilayah`
ORDER BY `data_notaris`.`wilayah` DESC";
if ($mysqli->query($create)) {
    echo "View jumlah_per_wilayah created successfully.\n";
} else {
    echo "Error creating view: " . $mysqli->error . "\n";
}
?>
