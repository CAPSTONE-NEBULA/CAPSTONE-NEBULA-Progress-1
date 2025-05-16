<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role     = $_POST['role'];
    $nama     = trim($_POST['nama']);
    $jk       = $_POST['jenis_kelamin'];
    $tgl      = $_POST['tanggal_lahir'];
    $alamat   = trim($_POST['alamat']);
    $kelas    = $_POST['kelas'];

    if (empty($username) || empty($password) || empty($role) || empty($nama) || empty($jk) || empty($tgl) || empty($alamat) || ($role === 'siswa' && empty($kelas))) {
        $error = "Semua field wajib diisi (kelas hanya wajib jika siswa).";
    } else {
        $check = $connection->prepare("SELECT id_user FROM akun WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Username sudah digunakan!";
        } else {
            $connection->begin_transaction();
            try {
                $stmt1 = $connection->prepare("INSERT INTO data_siswa () VALUES ()");
                $stmt1->execute();
                $id_data_siswa = $stmt1->insert_id;
                $stmt1->close();

                $stmt2 = $connection->prepare("INSERT INTO siswa (nama, jenis_kelamin, tanggal_lahir, alamat, kelas, data_siswa_id_data_siswa) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt2->bind_param("sssssi", $nama, $jk, $tgl, $alamat, $kelas, $id_data_siswa);
                $stmt2->execute();
                $id_siswa = $stmt2->insert_id;
                $stmt2->close();

                $stmt3 = $connection->prepare("INSERT INTO akun (username, password, role, siswa_id_siswa) VALUES (?, ?, ?, ?)");
                $stmt3->bind_param("sssi", $username, $password, $role, $id_siswa);
                $stmt3->execute();
                $id_user = $stmt3->insert_id;
                $stmt3->close();

                $stmt4 = $connection->prepare("UPDATE siswa SET akun_id_user = ? WHERE id_siswa = ?");
                $stmt4->bind_param("ii", $id_user, $id_siswa);
                $stmt4->execute();
                $stmt4->close();

                $stmt5 = $connection->prepare("UPDATE data_siswa SET siswa_id_siswa = ? WHERE id_data_siswa = ?");
                $stmt5->bind_param("ii", $id_siswa, $id_data_siswa);
                $stmt5->execute();
                $stmt5->close();

                $connection->commit();
                $success = "✅ Akun berhasil dibuat.";
            } catch (Exception $e) {
                $connection->rollback();
                $error = "❌ Gagal membuat akun: " . $e->getMessage();
            }
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Akun</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #e3f2fd; /* biru pastel lembut */
        margin: 0;
        padding: 40px;
    }

    .container {
        max-width: 720px;
        margin: 0 auto;
        background: white;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    }

    h2 {
        margin-bottom: 24px;
        font-weight: bold;
        color: #1565c0; /* biru tua */
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
    }

    input[type="text"],
    input[type="date"],
    input[type="password"],
    select {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        background-color: #fafafa;
    }

    input:focus,
    select:focus {
        border-color: #64b5f6;
        outline: none;
        box-shadow: 0 0 5px rgba(100, 181, 246, 0.5);
    }

    .button-group {
        display: flex;
        gap: 12px;
        width: 100%;
    }

    .button-group input[type="radio"] {
        display: none;
    }

    .button-group label {
        flex: 1;
        text-align: center;
        background-color: #e3f2fd;
        border: 1px solid #90caf9;
        border-radius: 8px;
        padding: 10px 0;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        color: #1565c0;
    }

    .button-group input[type="radio"]:checked + label {
        background-color: #ce93d8;
        color: white;
        font-weight: bold;
        border-color: #ab47bc;
    }

    .form-actions {
        text-align: center;
        margin-top: 30px;
    }

    .form-actions button {
        background-color:  #43b581;
        color: white;
        padding: 10px 30px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: bold;
        margin: 0 10px;
        transition: background-color 0.3s ease, transform 0.2s;
    }

    .form-actions button:hover {
        background-color: #388e3c;
        transform: scale(1.05);
    }

    .error {
        color: red;
        margin-top: 20px;
        text-align: center;
    }

    .success {
        color: green;
        margin-top: 20px;
        text-align: center;
    }
</style>

</head>
<body>
<div class="container">
    <h2>Tambah Akun</h2>
    <form method="post">
        <div class="form-group">
            <label>Username :</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Password :</label>
            <input type="text" name="password" required>
        </div>
        <div class="form-group">
            <label>Role :</label>
            <div class="button-group">
                <input type="radio" id="role_siswa" name="role" value="siswa" required>
                <label for="role_siswa">Siswa</label>
            </div>
        </div>
        <div class="form-group">
            <label>Nama :</label>
            <input type="text" name="nama" required>
        </div>
        <div class="form-group">
            <label>Tanggal Lahir :</label>
            <input type="date" name="tanggal_lahir" required>
        </div>
        <div class="form-group">
            <label>Alamat :</label>
            <input type="text" name="alamat" required>
        </div>
        <div class="form-group">
            <label>Kelas :</label>
            <select name="kelas" id="kelas">
                <option value="">-- Pilih Kelas --</option>
                <?php
                    for ($i = 1; $i <= 6; $i++) {
                        echo "<option value='{$i}A'>{$i}A</option>";
                        echo "<option value='{$i}B'>{$i}B</option>";
                    }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Jenis Kelamin :</label>
            <div class="button-group">
                <input type="radio" id="laki" name="jenis_kelamin" value="Laki-laki" required>
                <label for="laki">Laki-laki</label>

                <input type="radio" id="perempuan" name="jenis_kelamin" value="Perempuan">
                <label for="perempuan">Perempuan</label>
            </div>
        <div class="form-actions">
            <button type="submit">Tambahkan</button>
            <a href="admin.php" style="text-decoration: none;">
                <button type="button" style="background-color: #ccc; color: black; margin-left: 12px;">Kembali</button>
            </a>
        </div>
    </form>
</div>

<script>
document.querySelector("form").addEventListener("submit", function(event) {
    const role = document.querySelector('input[name="role"]:checked')?.value;
    const kelas = document.querySelector('select[name="kelas"]');
    if (role === 'siswa' && kelas.value.trim() === '') {
        alert("Kelas wajib dipilih jika Role adalah Siswa.");
        kelas.focus();
        event.preventDefault();
    }
});
</script>
</body>
</html>
