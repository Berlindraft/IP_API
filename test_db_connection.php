<?php
// filepath: /c:/xampp/htdocs/php_api/test_db_connection.php


require 'app/config/database.php';

$db = Database::getInstance();
$conn = $db->getConnection();

if ($conn) {
    echo "Database connection successful!";
} else {
    echo "Database connection failed.";
}

?>