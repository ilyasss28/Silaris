<?php
define('BASEPATH', __DIR__);
define('ENVIRONMENT', 'development');
require 'application/config/database.php';
$mysqli = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);

$res = $mysqli->query("SHOW FULL TABLES WHERE Table_type = 'VIEW'");
$views = [];
while($row = $res->fetch_array()) {
    $views[] = $row[0];
}

foreach ($views as $view) {
    // Get the view definition (the SELECT part only)
    $res2 = $mysqli->query("SHOW CREATE VIEW `$view`");
    if ($res2) {
        $row2 = $res2->fetch_array();
        $create_sql = $row2[1];

        // Extract just the SELECT statement from the CREATE VIEW
        // Pattern: ... VIEW `name` AS <select_statement>
        if (preg_match('/\bVIEW\s+`[^`]+`\s+AS\s+(.+)$/is', $create_sql, $matches)) {
            $select_sql = trim($matches[1]);
            
            // Drop the view
            $mysqli->query("DROP VIEW IF EXISTS `$view`");
            
            // Recreate without DEFINER
            $new_sql = "CREATE VIEW `$view` AS $select_sql";
            $res3 = $mysqli->query($new_sql);
            if ($res3) {
                echo "Successfully recreated view: $view\n";
            } else {
                echo "Failed to recreate $view: " . $mysqli->error . "\n";
                echo "Query: $new_sql\n";
            }
        } else {
            echo "Could not parse view definition for: $view\n";
        }
    }
}
echo "Done.\n";
