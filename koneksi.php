<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cheesyway"; // sesuaikan nama database lokalmu

// Ubah $koneksi menjadi $conn agar sesuai dengan file lain
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>