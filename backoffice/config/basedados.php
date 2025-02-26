<?php
require 'credentials.php';

$servername = "127.0.0.1";
$username = 'root';
$password = '';
$dbname = "technart";
$charset = "utf8mb4";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, $charset);
?>