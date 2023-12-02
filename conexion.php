<?php
$host = "localhost";
$user = "Admin";
$pass = "12345!";
$db   = "biblioteca";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
