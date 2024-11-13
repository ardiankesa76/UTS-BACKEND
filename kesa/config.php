<?php
$server = "localhost";
$user = "root";
$password = "";
$nama_database = "ardiankesa1";

// Koneksi ke database
$sambung = mysqli_connect($server, $user, $password, $nama_database);

// Cek koneksi
if (!$sambung) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>