<?php
session_start();
include 'koneksi.php';


$user = trim($_POST['user']);
$password = $_POST['password'];
$role = $_POST['role'];

if (empty($user) || empty($password) || empty($role)) {
    echo "<script>alert('Semua kolom wajib diisi.'); window.location='index.php';</script>";
    exit;
}

// Gunakan prepared statement untuk keamanan
$sql = "SELECT * FROM akun WHERE username = ? AND password = ? AND role = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("sss", $user, $password, $role);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $akun = $result->fetch_assoc();

    $_SESSION['login'] = true;
    $_SESSION['user_id'] = $akun['id_user'];
    $_SESSION['username'] = $akun['username'];
    $_SESSION['role'] = $akun['role'];

    if ($role === 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: siswa.php");
    }
    exit;
} else {
    echo "<script>alert('Username atau password salah!'); window.location='index.php';</script>";
    exit;
}
?>
