<?php
// Στοιχεία σύνδεσης από το WP Config σου
define('DB_NAME', 'hophopg_wp_gu1za');
define('DB_USER', 'hophopg_wp_p99rc');
define('DB_PASS', 'X6_#_z0vjqC6%n&w');
define('DB_HOST', 'localhost'); // Το :3306 συνήθως δεν χρειάζεται στο host string

try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Σφάλμα σύνδεσης: " . $e->getMessage());
}
?>
