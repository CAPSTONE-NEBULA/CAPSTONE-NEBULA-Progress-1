<?php
// Proses simpan data jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli("localhost", "root", "", "sekolah_sd");
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $siswa_id = $_POST['siswa_id'];

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

    $stmt = $conn->prepare("INSERT INTO orang_tua (
        nama_ayah, th_lahir_ayah, pendidikan_ayah, pekerjaan_ayah, penghasilan_ayah,
        nama_ibu, th_lahir_ibu, pendidikan_ibu, pekerjaan_ibu, penghasilan_ibu
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssdsssd",
        $nama_ayah, $th_lahir_ayah, $pendidikan_ayah, $pekerjaan_ayah, $penghasilan_ayah,
        $nama_ibu, $th_lahir_ibu, $pendidikan_ibu, $pekerjaan_ibu, $penghasilan_ibu
    );

    if ($stmt->execute()) {
        $id_orang_tua = $conn->insert_id;

        $update = $conn->prepare("UPDATE siswa SET orang_tua_id_orang_tua = ? WHERE id_siswa = ?");
        $update->bind_param("ii", $id_orang_tua, $siswa_id);
        $update->execute();
        $update->close();

        echo "<script>alert('Data orang tua berhasil disimpan dan dikaitkan dengan siswa.'); window.location.href='admin.php?page=data_orang_tua';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data orang tua');</script>";
    }

    $stmt->close();
    $conn->close();
}

// Ambil data siswa untuk dropdown
$conn = new mysqli("localhost", "root", "", "sekolah_sd");
$siswa = $conn->query("SELECT id_siswa, nama FROM siswa");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Data Orang Tua</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0fff4;
      margin: 0;
      padding: 40px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
    }

    .container {
      width: 100%;
      max-width: 900px;
      background: white;
      padding: 40px 50px;
      border-radius: 16px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    }

    h2 {
      margin-bottom: 20px;
      font-weight: bold;
      color: #2e7d32;
    }

    .section-header {
      background-color: #a5d6a7;
      color: #1b5e20;
      padding: 12px;
      border-radius: 10px;
      font-weight: bold;
      font-size: 15px;
      margin-top: 30px;
      margin-bottom: 25px;
    }

    .form-row {
      display: flex;
      flex-wrap: wrap;
      gap: 24px 40px;
      justify-content: space-between;
    }

    .form-row label {
      flex: 1 1 calc(50% - 20px);
      display: flex;
      flex-direction: column;
      font-weight: 500;
      font-size: 14px;
      color: #444;
    }

    .label-icon {
      display: flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 6px;
      color: #2e7d32;
      font-weight: 600;
    }

    input[type="text"],
    input[type="number"],
    select {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
      margin-bottom: 20px;
    }

    input:focus, select:focus {
      outline: none;
      border-color: #81c784;
      box-shadow: 0 0 4px rgba(129, 199, 132, 0.5);
    }

    .form-actions {
      margin-top: 30px;
      text-align: center;
    }

    .form-actions button,
    .form-actions input[type="submit"] {
      background-color: #43b581;
      color: white;
      padding: 10px 30px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      margin: 0 10px;
      transition: background-color 0.3s ease, transform 0.2s;
    }

    .form-actions button:hover,
    .form-actions input[type="submit"]:hover {
      background-color: #2e7d32;
      transform: scale(1.05);
    }

    .form-actions a button {
      background-color: #aaa;
      color: white;
    }

    .form-actions a button:hover {
      background-color: #888;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Tambah Data Orang Tua</h2>
    <form method="POST">
      <div class="section-header">Nama Siswa</div>
      <label>
        <span class="label-icon"><i class="fas fa-user-graduate"></i> Pilih Nama Siswa</span>
        <select name="siswa_id" required>
          <option value="">-- Pilih Siswa --</option>
          <?php while ($row = $siswa->fetch_assoc()) : ?>
            <option value="<?= htmlspecialchars($row['id_siswa']) ?>">
              <?= htmlspecialchars($row['nama']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </label>

      <div class="section-header">Data Ayah</div>
      <div class="form-row">
        <label>
          <span class="label-icon"><i class="fas fa-user"></i> Nama</span>
          <input type="text" name="nama_ayah" required>
        </label>
        <label>
          <span class="label-icon"><i class="fas fa-calendar-alt"></i> Tahun Lahir</span>
          <input type="number" name="th_lahir_ayah" required>
        </label>
        <label>
          <span class="label-icon"><i class="fas fa-graduation-cap"></i> Pendidikan Terakhir</span>
          <input type="text" name="pendidikan_ayah" required>
        </label>
        <label>
          <span class="label-icon"><i class="fas fa-briefcase"></i> Pekerjaan</span>
          <input type="text" name="pekerjaan_ayah" required>
        </label>
        <label>
          <span class="label-icon"><i class="fas fa-wallet"></i> Penghasilan</span>
          <input type="number" name="penghasilan_ayah" required>
        </label>
      </div>

      <div class="section-header">Data Ibu</div>
      <div class="form-row">
        <label>
          <span class="label-icon"><i class="fas fa-user"></i> Nama</span>
          <input type="text" name="nama_ibu" required>
        </label>
        <label>
          <span class="label-icon"><i class="fas fa-calendar-alt"></i> Tahun Lahir</span>
          <input type="number" name="th_lahir_ibu" required>
        </label>
        <label>
          <span class="label-icon"><i class="fas fa-graduation-cap"></i> Pendidikan Terakhir</span>
          <input type="text" name="pendidikan_ibu" required>
        </label>
        <label>
          <span class="label-icon"><i class="fas fa-briefcase"></i> Pekerjaan</span>
          <input type="text" name="pekerjaan_ibu" required>
        </label>
        <label>
          <span class="label-icon"><i class="fas fa-wallet"></i> Penghasilan</span>
          <input type="number" name="penghasilan_ibu" required>
        </label>
      </div>

      <div class="form-actions">
        <a href="admin.php"><button type="button"><i class="fas fa-arrow-left"></i> Kembali</button></a>
        <input type="submit" value="Simpan">
      </div>
    </form>
  </div>
</body>
</html>
