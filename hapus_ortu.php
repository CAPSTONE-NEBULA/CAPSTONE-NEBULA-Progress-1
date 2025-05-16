

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'koneksi.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Validasi ID orang tua
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: data_orang_tua.php?message=id_tidak_valid");
    exit;
}

$id_orang_tua = (int) $_GET['id'];

// Cek apakah data orang tua dengan ID tersebut ada
$stmt = $connection->prepare("SELECT id_orang_tua FROM orang_tua WHERE id_orang_tua = ?");
if (!$stmt) {
    die("Prepare failed (SELECT orang_tua): " . $connection->error);
}
$stmt->bind_param("i", $id_orang_tua);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $stmt->close();

    // Hapus data siswa yang terkait dulu
    $hapus_siswa = $connection->prepare("DELETE FROM siswa WHERE orang_tua_id_orang_tua = ?");
    if (!$hapus_siswa) {
        die("Prepare failed (DELETE siswa): " . $connection->error);
    }
    $hapus_siswa->bind_param("i", $id_orang_tua);
    if (!$hapus_siswa->execute()) {
        $hapus_siswa->close();
        header("Location: data_orang_tua.php?message=hapus_siswa_gagal");
        exit;
    }
    $hapus_siswa->close();

    // Baru hapus data orang tua
    $hapus_ortu = $connection->prepare("DELETE FROM orang_tua WHERE id_orang_tua = ?");
    if (!$hapus_ortu) {
        die("Prepare failed (DELETE orang_tua): " . $connection->error);
    }
    $hapus_ortu->bind_param("i", $id_orang_tua);

    if ($hapus_ortu->execute()) {
        $hapus_ortu->close();
        header("Location: admin.php?message=hapus_ortu_berhasil");
        exit;
    } else {
        header("Location: admin.php?message=hapus_ortu_gagal");
        exit;
    }

} else {
    header("Location:admin.php?message=ortu_tidak_ditemukan");
    exit;
}