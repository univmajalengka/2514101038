<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_toko";

// Menghubungkan PHP dengan MySQL
$conn = new mysqli(
    $servername,
    $username,
    $password,
    $dbname
);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>