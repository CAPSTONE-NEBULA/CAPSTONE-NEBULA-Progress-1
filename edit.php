<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$result = mysqli_query($connection, "SELECT * FROM data_siswa WHERE id_data_siswa = $id");
$data_siswa = mysqli_fetch_assoc($result);

if (!$data_siswa) {
    echo "Data siswa tidak ditemukan.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $agama = $_POST['agama'];
    $RT = $_POST['RT'];
    $RW = $_POST['RW'];
    $dusun = $_POST['dusun'];
    $kelurahan = $_POST['kelurahan'];
    $kecamatan = $_POST['kecamatan'];
    $kode_pos = $_POST['kode_pos'];
    $tinggal_bersama = $_POST['tinggal_bersama'];
    $alat_transportasi = $_POST['alat_transportasi'];
    $penerima_kps = $_POST['penerima_kps'];
    $anak_ke = $_POST['anak_ke'];
    $jml_saudara_kandung = $_POST['jml_saudara_kandung'];
    $jarak_rumah = $_POST['jarak_rumah'];
    $berat_badan = $_POST['berat_badan'];
    $tinggi_badan = $_POST['tinggi_badan'];
    $total_penghasilan = $_POST['total_penghasilan'];
    $kategori_ekonomi = $_POST['kategori_ekonomi'];

    $query = "UPDATE data_siswa SET 
        agama = '$agama', RT = '$RT', RW = '$RW', dusun = '$dusun', kelurahan = '$kelurahan', 
        kecamatan = '$kecamatan', kode_pos = '$kode_pos', tinggal_bersama = '$tinggal_bersama', 
        alat_transportasi = '$alat_transportasi', penerima_kps = '$penerima_kps', anak_ke = '$anak_ke', 
        jml_saudara_kandung = '$jml_saudara_kandung', jarak_rumah = '$jarak_rumah', 
        berat_badan = '$berat_badan', tinggi_badan = '$tinggi_badan', 
        total_penghasilan = '$total_penghasilan', kategori_ekonomi = '$kategori_ekonomi'
        WHERE id_data_siswa = $id";

    if (mysqli_query($connection, $query)) {
        header("Location: admin.php");
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Update Data Siswa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #f0fff4; /* hijau muda pastel */
    margin: 0;
    padding: 40px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
  }

  .container {
    width: 100%;
    max-width: 1100px;
    background: white;
    padding: 40px 50px;
    border-radius: 16px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
  }

  h2 {
    font-family: 'Fredoka', sans-serif;
    margin-bottom: 20px;
    font-weight: bold;
    color: #2e7d32; /* hijau tua */
  }

  .section-header {
    background-color: #ffcc80; /* oranye pastel */
    color: #333;
    padding: 12px;
    border-radius: 10px;
    font-weight: bold;
    margin-bottom: 25px;
    text-align: center;
    font-size: 16px;
  }

  .form-group {
    display: flex;
    flex-wrap: wrap;
    column-gap: 40px;
    row-gap: 20px;
    justify-content: space-between;
  }

  .form-group > div {
    flex: 1;
    min-width: 350px;
  }

  label {
    display: block;
    margin-bottom: 10px;
    font-weight: 500;
    font-size: 15px;
    color: #444;
  }

  input, select {
    width: 100%;
    padding: 10px 14px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 6px;
    margin-bottom: 20px;
    box-sizing: border-box;
  }

  input:focus, select:focus {
    outline: none;
    border-color: #43b581; /* hijau lembut */
    box-shadow: 0 0 4px rgba(67, 181, 129, 0.5);
  }

  .form-actions {
    margin-top: 30px;
    text-align: center;
  }

  .form-actions button {
    background-color: #43b581; /* hijau lembut */
    color: white;
    padding: 12px 60px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: bold;
    font-size: 16px;
    transition: background-color 0.3s ease;
  }

  .form-actions button:hover {
    background-color: #388e3c; /* hijau tua */
    transform: scale(1.05);
  }
</style>

</head>
<body>
  <div class="container">
    <h2>Update Data Siswa</h2>
    <div class="section-header">Masukkan Data Siswa</div>
    <form method="POST">
        <div class="form-group">
    <div>
        <label><i class="fas fa-praying-hands" style="margin-right:6px;"></i> Agama :</label>
        <select name="agama" required>
        <option value="Islam" <?= $data_siswa['agama'] === 'Islam' ? 'selected' : '' ?>>Islam</option>
        <option value="Kristen" <?= $data_siswa['agama'] === 'Kristen' ? 'selected' : '' ?>>Kristen</option>
        <option value="Katolik" <?= $data_siswa['agama'] === 'Katolik' ? 'selected' : '' ?>>Katolik</option>
        <option value="Hindu" <?= $data_siswa['agama'] === 'Hindu' ? 'selected' : '' ?>>Hindu</option>
        <option value="Buddha" <?= $data_siswa['agama'] === 'Buddha' ? 'selected' : '' ?>>Buddha</option>
        <option value="Konghucu" <?= $data_siswa['agama'] === 'Konghucu' ? 'selected' : '' ?>>Konghucu</option>
        </select>

        <label><i class="fas fa-sort-numeric-up" style="margin-right:6px;"></i> RT :</label>
        <input type="number" name="RT" value="<?= $data_siswa['RT'] ?>" required>

        <label><i class="fas fa-sort-numeric-down" style="margin-right:6px;"></i> RW :</label>
        <input type="number" name="RW" value="<?= $data_siswa['RW'] ?>" required>

        <label><i class="fas fa-map-pin" style="margin-right:6px;"></i> Dusun :</label>
        <input type="text" name="dusun" value="<?= $data_siswa['dusun'] ?>" required>

        <label><i class="fas fa-map-marker-alt" style="margin-right:6px;"></i> Kelurahan :</label>
        <input type="text" name="kelurahan" value="<?= $data_siswa['kelurahan'] ?>" required>

        <label><i class="fas fa-city" style="margin-right:6px;"></i> Kecamatan :</label>
        <input type="text" name="kecamatan" value="<?= $data_siswa['kecamatan'] ?>" required>

        <label><i class="fas fa-mail-bulk" style="margin-right:6px;"></i> Kode Pos :</label>
        <input type="number" name="kode_pos" value="<?= $data_siswa['kode_pos'] ?>" required>

        <label><i class="fas fa-home" style="margin-right:6px;"></i> Tinggal Bersama :</label>
        <input type="text" name="tinggal_bersama" value="<?= $data_siswa['tinggal_bersama'] ?>" required>

        <label><i class="fas fa-bus" style="margin-right:6px;"></i> Transportasi :</label>
        <input type="text" name="alat_transportasi" value="<?= $data_siswa['alat_transportasi'] ?>" required>
    </div>

    <div>
        <label><i class="fas fa-id-card" style="margin-right:6px;"></i> Penerima KPS :</label>
        <select name="penerima_kps" required>
        <option value="Y" <?= $data_siswa['penerima_kps'] === 'Y' ? 'selected' : '' ?>>Y</option>
        <option value="T" <?= $data_siswa['penerima_kps'] === 'T' ? 'selected' : '' ?>>T</option>
        </select>

        <label><i class="fas fa-child" style="margin-right:6px;"></i> Anak Ke- :</label>
        <input type="number" name="anak_ke" value="<?= $data_siswa['anak_ke'] ?>" required>

        <label><i class="fas fa-users" style="margin-right:6px;"></i> Jumlah Saudara Kandung :</label>
        <input type="number" name="jml_saudara_kandung" value="<?= $data_siswa['jml_saudara_kandung'] ?>" required>

        <label><i class="fas fa-road" style="margin-right:6px;"></i> Jarak Rumah (km) :</label>
        <input type="number" name="jarak_rumah" value="<?= $data_siswa['jarak_rumah'] ?>" required>

        <label><i class="fas fa-weight" style="margin-right:6px;"></i> Berat Badan (kg) :</label>
        <input type="number" name="berat_badan" value="<?= $data_siswa['berat_badan'] ?>" required>

        <label><i class="fas fa-ruler-vertical" style="margin-right:6px;"></i> Tinggi Badan (cm) :</label>
        <input type="number" name="tinggi_badan" value="<?= $data_siswa['tinggi_badan'] ?>" required>

        <label><i class="fas fa-wallet" style="margin-right:6px;"></i> Total Penghasilan Orang Tua :</label>
        <input type="number" name="total_penghasilan" value="<?= $data_siswa['total_penghasilan'] ?>" required>

        <label><i class="fas fa-chart-line" style="margin-right:6px;"></i> Kategori Ekonomi :</label>
        <select name="kategori_ekonomi" required>
        <option value="Rendah" <?= $data_siswa['kategori_ekonomi'] === 'Rendah' ? 'selected' : '' ?>>Rendah</option>
        <option value="Sedang" <?= $data_siswa['kategori_ekonomi'] === 'Sedang' ? 'selected' : '' ?>>Sedang</option>
        <option value="Tinggi" <?= $data_siswa['kategori_ekonomi'] === 'Tinggi' ? 'selected' : '' ?>>Tinggi</option>
        </select>
    </div>
    </div>
    <div class="form-actions" style="display: flex; justify-content: center; gap: 20px;">
        <a href="admin.php" style="
            background-color: #ccc;
            color: #333;
            padding: 12px 40px;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            transition: background-color 0.3s ease;
            display: inline-block;
            text-align: center;
        ">Kembali</a>
        <button type="submit">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</body>
</html>
