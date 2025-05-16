<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

$siswa = mysqli_query($connection, "SELECT id_siswa, nama FROM siswa");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siswa_id = $_POST['siswa_id'];
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


    $cek = mysqli_query($connection, "SELECT * FROM data_siswa WHERE siswa_id_siswa = '$siswa_id'");
    if (mysqli_num_rows($cek) > 0) {
        $query = "UPDATE data_siswa SET
            agama = '$agama',
            RT = '$RT',
            RW = '$RW',
            dusun = '$dusun',
            kelurahan = '$kelurahan',
            kecamatan = '$kecamatan',
            kode_pos = '$kode_pos',
            tinggal_bersama = '$tinggal_bersama',
            alat_transportasi = '$alat_transportasi',
            penerima_kps = '$penerima_kps',
            anak_ke = '$anak_ke',
            jml_saudara_kandung = '$jml_saudara_kandung',
            jarak_rumah = '$jarak_rumah',
            berat_badan = '$berat_badan',
            tinggi_badan = '$tinggi_badan',
            total_penghasilan = '$total_penghasilan',
            kategori_ekonomi = '$kategori_ekonomi'
            WHERE siswa_id_siswa = '$siswa_id'";
    } else {
        $query = "INSERT INTO data_siswa (
            siswa_id_siswa, agama, RT, RW, dusun, kelurahan, kecamatan, kode_pos,
            tinggal_bersama, alat_transportasi, penerima_kps, anak_ke, jml_saudara_kandung,
            jarak_rumah, berat_badan, tinggi_badan, total_penghasilan, kategori_ekonomi
        ) VALUES (
            '$siswa_id', '$agama', '$RT', '$RW', '$dusun', '$kelurahan', '$kecamatan', '$kode_pos',
            '$tinggal_bersama', '$alat_transportasi', '$penerima_kps', '$anak_ke', '$jml_saudara_kandung',
            '$jarak_rumah', '$berat_badan', '$tinggi_badan', '$total_penghasilan', '$kategori_ekonomi'
        )";
    }

    mysqli_query($connection, $query);
    header("Location: admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Data Siswa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #e8f5ff;
        margin: 0;
        padding: 40px;
        display: flex;
        justify-content: center;
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
        margin-bottom: 20px;
        font-weight: bold;
        color: #0277bd;
    }

    .section-header {
        background-color: #4fc3f7;
        color: #333;
        padding: 12px;
        border-radius: 10px;
        font-weight: bold;
        margin-bottom: 25px;
        font-size: 15px;
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
        margin-bottom: 8px;
        font-weight: 500;
        font-size: 14px;
        color: #444;
    }

    label i {
        margin-right: 6px;
        color: #0288d1;
    }

    input,
    select {
        width: 100%;
        padding: 10px 14px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-bottom: 16px;
        box-sizing: border-box;
    }

    input:focus,
    select:focus {
        outline: none;
        border-color: #4fc3f7;
        box-shadow: 0 0 4px rgba(79, 195, 247, 0.5);
    }

    .form-actions {
        margin-top: 30px;
        text-align: right;
    }

    .form-actions button {
        background-color: #0277bd;
        color: white;
        padding: 10px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
        font-size: 14px;
        margin-left: 10px;
        transition: background-color 0.3s ease, transform 0.2s;
    }

    .form-actions button:hover {
        background-color: #01579b;
        transform: scale(1.05);
    }
  </style>
</head>
<body>
<div class="container">
    <h2>Tambah Data Siswa</h2>
    <div class="section-header">Nama Siswa</div>
    <form method="POST">
        <select name="siswa_id" required>
            <option value="">-- Pilih Siswa --</option>
            <?php while ($row = mysqli_fetch_assoc($siswa)) : ?>
                <option value="<?= $row['id_siswa'] ?>"><?= htmlspecialchars($row['nama']) ?></option>
            <?php endwhile; ?>
        </select>

        <div style="margin-top: 30px" class="section-header"><i class="fas fa-pen"></i> Masukkan Data Siswa</div>
        <div class="form-group">
            <div>
                <label><i class="fas fa-praying-hands"></i> Agama :</label>
                <select name="agama" required>
                    <option value="Islam">Islam</option>
                    <option value="Kristen">Kristen</option>
                    <option value="Katolik">Katolik</option>
                    <option value="Hindu">Hindu</option>
                    <option value="Buddha">Buddha</option>
                    <option value="Konghucu">Konghucu</option>
                </select>

                <label><i class="fas fa-sort-numeric-up"></i> RT :</label>
                <input type="number" name="RT" required>

                <label><i class="fas fa-sort-numeric-down"></i> RW :</label>
                <input type="number" name="RW" required>

                <label><i class="fas fa-map-pin"></i> Dusun :</label>
                <input type="text" name="dusun" required>

                <label><i class="fas fa-map-marker-alt"></i> Kelurahan :</label>
                <input type="text" name="kelurahan" required>

                <label><i class="fas fa-city"></i> Kecamatan :</label>
                <input type="text" name="kecamatan" required>

                <label><i class="fas fa-mail-bulk"></i> Kode Pos :</label>
                <input type="number" name="kode_pos" required>

                <label><i class="fas fa-home"></i> Tinggal Bersama :</label>
                <input type="text" name="tinggal_bersama" required>

                <label><i class="fas fa-bus"></i> Transportasi :</label>
                <input type="text" name="alat_transportasi" required>
            </div>

            <div>
                <label><i class="fas fa-id-card"></i> Penerima KPS :</label>
                <select name="penerima_kps" required>
                    <option value="Y">Y</option>
                    <option value="T">T</option>
                </select>

                <label><i class="fas fa-child"></i> Anak Ke- :</label>
                <input type="number" name="anak_ke" required>

                <label><i class="fas fa-users"></i> Jumlah Saudara Kandung :</label>
                <input type="number" name="jml_saudara_kandung" required>

                <label><i class="fas fa-road"></i> Jarak Rumah (km) :</label>
                <input type="number" name="jarak_rumah" required>

                <label><i class="fas fa-weight"></i> Berat Badan (kg) :</label>
                <input type="number" name="berat_badan" required>

                <label><i class="fas fa-ruler-vertical"></i> Tinggi Badan (cm) :</label>
                <input type="number" name="tinggi_badan" required>

                <label><i class="fas fa-wallet"></i> Total Penghasilan Orang Tua :</label>
                <input type="number" name="total_penghasilan" required>

                <label><i class="fas fa-chart-line"></i> Kategori Ekonomi :</label>
                <select name="kategori_ekonomi" required>
                    <option value="Rendah">Rendah</option>
                    <option value="Sedang">Sedang</option>
                    <option value="Tinggi">Tinggi</option>
                </select>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit"><i class="fas fa-save"></i> Simpan</button>
            <button type="button" onclick="window.location.href='admin.php'"><i class="fas fa-arrow-left"></i> Kembali</button>
        </div>
    </form>
</div>
</body>
</html>
