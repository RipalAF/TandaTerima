<?php
$host = "localhost";
$user = "root";  // Sesuaikan dengan username MySQL kamu
$pass = "";      // Jika ada password, isi di sini
$dbname = "surat";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
