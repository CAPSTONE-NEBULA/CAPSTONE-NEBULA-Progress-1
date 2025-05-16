hapus.php (punyanya haapus siswa)

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_data_siswa = (int) $_GET['id'];

    $hapus_siswa = $connection->prepare("DELETE FROM data_siswa WHERE id_data_siswa = ?");
    if (!$hapus_siswa) {
        die("Prepare failed (DELETE data_siswa): " . $connection->error);
    }
    $hapus_siswa->bind_param("i", $id_data_siswa);

    if ($hapus_siswa->execute()) {
        $hapus_siswa->close();
        header("Location: admin.php?message=hapus_siswa_berhasil");
        exit;
    } else {
        header("Location: admin.php?message=hapus_siswa_gagal");
        exit;
    }
} else {
    header("Location: admin.php?message=id_tidak_valid");
    exit;
}