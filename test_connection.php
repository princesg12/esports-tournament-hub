<?php
// Include the Database class file
require_once 'Database.php';

// Instantiate the object and get the connection
$db = new Database();
$connection = $db->getConnection();

if ($connection) {
    echo "<h1>Success!</h1>";
    echo "<p>Connected to the esports_tournament database successfully.</p>";
}
?>