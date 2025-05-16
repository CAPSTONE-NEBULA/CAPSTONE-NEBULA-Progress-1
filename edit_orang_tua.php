<?php
include 'koneksi.php';

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan.";
    exit;
}

$id = intval($_GET['id']);

$stmt = $connection->prepare("SELECT * FROM orang_tua WHERE id_orang_tua = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Data tidak ditemukan.";
    exit;
}

$data = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_ayah = $_POST['nama_ayah'];
    $th_lahir_ayah = $_POST['th_lahir_ayah'];
    $pendidikan_ayah = $_POST['pendidikan_ayah'];
    $pekerjaan_ayah = $_POST['pekerjaan_ayah'];
    $penghasilan_ayah = $_POST['penghasilan_ayah'];

    $nama_ibu = $_POST['nama_ibu'];
    $th_lahir_ibu = $_POST['th_lahir_ibu'];
    $pendidikan_ibu = $_POST['pendidikan_ibu'];
    $pekerjaan_ibu = $_POST['pekerjaan_ibu'];
    $penghasilan_ibu = $_POST['penghasilan_ibu'];

    $update = $connection->prepare("UPDATE orang_tua SET nama_ayah = ?, th_lahir_ayah = ?, pendidikan_ayah = ?, pekerjaan_ayah = ?, penghasilan_ayah = ?, nama_ibu = ?, th_lahir_ibu = ?, pendidikan_ibu = ?, pekerjaan_ibu = ?, penghasilan_ibu = ? WHERE id_orang_tua = ?");
    $update->bind_param("ssssssssssi", $nama_ayah, $th_lahir_ayah, $pendidikan_ayah, $pekerjaan_ayah, $penghasilan_ayah, $nama_ibu, $th_lahir_ibu, $pendidikan_ibu, $pekerjaan_ibu, $penghasilan_ibu, $id);

    if ($update->execute()) {
        echo "<script>alert('Data berhasil diperbarui'); window.location='data_orang_tua.php';</script>";
    } else {
        echo "Gagal memperbarui data: " . $connection->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Data Orang Tua</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #e3f2fd;
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
      color: #1976d2; 
    }
    .section-header {
      background-color: #fff176; 
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
      border-color: #64b5f6; 
      box-shadow: 0 0 4px rgba(100, 181, 246, 0.5);
    }
    .form-actions {
      margin-top: 30px;
      text-align: center;
    }
    .form-actions button {
      background-color: #1976d2; 
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
      background-color: #1565c0;
      transform: scale(1.05);
    }
</style>

</head>
  <body>
    <div class="container">
      <h2>Edit Data Orang Tua</h2>
      <div class="section-header">Formulir Data Orang Tua</div>
      <form method="post">
        <div class="form-group">
      <div>
        <label><i class="fas fa-male" style="margin-right:6px;"></i> Nama Ayah</label>
        <input type="text" name="nama_ayah" value="<?= htmlspecialchars($data['nama_ayah']) ?>" required>

        <label><i class="fas fa-calendar-alt" style="margin-right:6px;"></i> Tahun Lahir Ayah</label>
        <input type="number" name="th_lahir_ayah" value="<?= htmlspecialchars($data['th_lahir_ayah']) ?>" required>

        <label><i class="fas fa-graduation-cap" style="margin-right:6px;"></i> Pendidikan Ayah</label>
        <input type="text" name="pendidikan_ayah" value="<?= htmlspecialchars($data['pendidikan_ayah']) ?>" required>

        <label><i class="fas fa-briefcase" style="margin-right:6px;"></i> Pekerjaan Ayah</label>
        <input type="text" name="pekerjaan_ayah" value="<?= htmlspecialchars($data['pekerjaan_ayah']) ?>" required>

        <label><i class="fas fa-wallet" style="margin-right:6px;"></i> Penghasilan Ayah</label>
        <input type="number" name="penghasilan_ayah" value="<?= htmlspecialchars($data['penghasilan_ayah']) ?>" required>
      </div>

      <div>
        <label><i class="fas fa-female" style="margin-right:6px;"></i> Nama Ibu</label>
        <input type="text" name="nama_ibu" value="<?= htmlspecialchars($data['nama_ibu']) ?>" required>

        <label><i class="fas fa-calendar-alt" style="margin-right:6px;"></i> Tahun Lahir Ibu</label>
        <input type="number" name="th_lahir_ibu" value="<?= htmlspecialchars($data['th_lahir_ibu']) ?>" required>

        <label><i class="fas fa-graduation-cap" style="margin-right:6px;"></i> Pendidikan Ibu</label>
        <input type="text" name="pendidikan_ibu" value="<?= htmlspecialchars($data['pendidikan_ibu']) ?>" required>

        <label><i class="fas fa-briefcase" style="margin-right:6px;"></i> Pekerjaan Ibu</label>
        <input type="text" name="pekerjaan_ibu" value="<?= htmlspecialchars($data['pekerjaan_ibu']) ?>" required>

        <label><i class="fas fa-wallet" style="margin-right:6px;"></i> Penghasilan Ibu</label>
        <input type="number" name="penghasilan_ibu" value="<?= htmlspecialchars($data['penghasilan_ibu']) ?>" required>
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
      </form>
    </div>
  </body>
</html>
