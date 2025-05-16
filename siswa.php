<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'siswa') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "
    SELECT 
        s.nama AS nama_siswa,
        s.jenis_kelamin,
        s.tanggal_lahir,
        s.alamat,
        s.kelas,
        o.nama_ayah, o.pekerjaan_ayah,
        o.nama_ibu, o.pekerjaan_ibu,
        o.penghasilan_ayah, o.penghasilan_ibu
    FROM siswa s
    JOIN akun a ON s.akun_id_user = a.id_user
    JOIN orang_tua o ON s.orang_tua_id_orang_tua = o.id_orang_tua
    WHERE a.id_user = ?
";

$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Data siswa tidak ditemukan.");
}

$data = $result->fetch_assoc();
$stmt->close();

$total_penghasilan = $data['penghasilan_ayah'] + $data['penghasilan_ibu'];

if (!function_exists('kategoriEkonomi')) {
    function kategoriEkonomi($total) {
        if ($total < 2000000) return 'Rendah';
        elseif ($total < 5000000) return 'Sedang';
        else return 'Tinggi';
    }
}

$kategori_ekonomi = kategoriEkonomi($total_penghasilan);
$layak = ($kategori_ekonomi === 'Rendah' && $total_penghasilan < 2000000);

$page = $_GET['page'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Portal Siswa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9fff2;
      display: flex;
      min-height: 100vh;
    }
    .sidebar {
      width: 300px;
      background-color: #202020;
      color: white;
      padding-top: 20px;
      position: fixed;
      top: 0; left: 0; bottom: 0;
    }
    .sidebar .brand {
      text-align: center;
      font-size: 40px;
      font-weight: bold;
      margin-bottom: 20px;
    }
    .sidebar ul { list-style: none; }
    .sidebar ul li a {
      color: white;
      display: block;
      padding: 18px 25px;
      text-decoration: none;
      font-size: 20px;
      transition: all 0.3s ease;
      font-family: 'Fredoka One', cursive;
    }
    .sidebar ul li a:hover {
      background-color: #FFEB3B;
      color: #333;
      transform: scale(1.05);
    }
    .main {
      margin-left: 300px;
      width: calc(100% - 300px);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    .admin-bar {
        background: #FFF8E1;
        padding: 10px 30px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        position: relative;
        z-index: 10;
    }
    .admin-info {
      display: flex;
      align-items: center;
      gap: 10px;
      position: relative;
      cursor: pointer;
    }
    .admin-info i {
      font-size: 30px;
      color: #FF9800;
    }
    .admin-label .username {
      font-size: 18px;
      color: #333;
    }
    .admin-label .school {
      font-size: 16px;
      font-weight: bold;
      color: #2E7D32;
    }
    .dropdown-menu {
      display: none;
      position: absolute;
      top: 50px;
      right: 0;
      background: white;
      color: #333;
      padding: 10px 15px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      font-size: 14px;
      min-width: 120px;
    }
    .dropdown-menu a {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 8px 0;
      font-size: 16px;
      color: #333;
      text-decoration: none;
      font-weight: bold;
    }
    .dropdown-menu i {
      color: #c47100;
      margin-right: 8px;
    }
    .content {
      margin-top: 15px;
      padding: 40px;
    }
    .welcome {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: white;
      padding: 50px 40px;
      border-radius: 12px;
      margin-bottom: 40px;
      box-shadow: 0 6px 14px rgba(0,0,0,0.08);
    }
    .welcome-text h1 {
      font-size: 50px;
      margin-bottom: 10px;
    }
    .welcome-text p {
      color: #555;
      font-size: 30px;
    }
    .banner-image {
      width: 600px;
      height: auto;
      border-top-left-radius: 100px;
      border-bottom-right-radius: 100px;
      object-fit: cover;
      box-shadow: 0 6px 14px rgba(0,0,0,0.1);
    }
    .status-box {
      background: white;
      padding: 100px;
      border-radius: 12px;
      box-shadow: 0 6px 14px rgba(0,0,0,0.08);
      display: flex;
      gap: 70px;
      align-items: center;
    }
    .status-box img {
      width: 350px; 
      height: auto;
    }
    .status-text h2 {
      margin-bottom: 15px;
      font-size: 40px; 
      color: #c62828;
      text-align: justify;
    }
    .status-text p {
      font-size: 30px;
      line-height: 1.6;
      color: #444;
      text-align: justify;
    }
    .status-text {
      flex: 1;
      max-width: 900px;
    }
    .cek-button-container {
      text-align: center;
      margin-bottom: 40px;
    }
    .cek-button {
      padding: 15px 40px;
      font-size: 20px;
      background-color: #2E7D32;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }
  </style>
</head>
  <body>

  <div class="sidebar">
    <div class="brand">SISD</div>
    <ul>
      <li><a href="siswa.php" class="<?= $page === 'dashboard' ? 'active' : '' ?>"><i class="fas fa-home"></i> Dashboard</a></li>
      <li><a href="siswa.php?page=data" class="<?= $page === 'data' ? 'active' : '' ?>"><i class="fas fa-user"></i> Data Siswa</a></li>
    </ul>
  </div>

  <div class="main">
    <div class="admin-bar">
      <div class="admin-info" id="adminInfo">
        <i class="fas fa-user-circle"></i>
        <div class="admin-label">
          <div class="username"><?= $_SESSION['username'] ?? 'siswa'; ?></div>
          <div class="school"><?= $data['nama_siswa']; ?></div>
        </div>
        <div class="dropdown-menu" id="adminDropdown">
          <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
      </div>
    </div>
    <div class="content">
      <?php
      if ($page === 'dashboard') {
          echo '
          <div class="welcome">
            <div class="welcome-text">
              <h1>Halo, ' . strtoupper($data['nama_siswa']) . '</h1>
              <p>Selamat datang di Portal Informasi Bantuan Pendidikan SDN 027 Loa Kulu</p>
            </div>
            <img src="img/foto.jpg" class="banner-image" alt="foto banner">
          </div>

          <div class="cek-button-container">
            <button class="cek-button" id="cekHasilBtn">Cek Hasil Kelayakan</button>
          </div>

          <div class="status-box" id="hasilBox" style="display: none;">
            <img src="img/lovepik.png" alt="Ilustrasi">
            <div class="status-text">';
          if ($layak) {
              echo '
              <h2 style="color: #2e7d32;">Selamat! Anda Layak Menerima Bantuan</h2>
              <p>
                Berdasarkan data yang telah Anda isi serta hasil analisis sistem,<br>
                Anda dinyatakan <strong>layak</strong> untuk menerima bantuan pendidikan dari sekolah.<br>
                Mohon menunggu informasi lebih lanjut dari pihak sekolah mengenai jadwal penyaluran bantuan serta prosedur administrasi yang perlu dilengkapi.
              </p>';
          } else {
              echo '
              <h2 style="color: #c62828;">Mohon Maaf, Anda Belum Layak Menerima Bantuan</h2>
              <p>
                Berdasarkan hasil analisis sistem terhadap data yang Anda isikan,<br>
                saat ini Anda <strong>belum memenuhi</strong> kriteria sebagai penerima bantuan pendidikan.<br>
                Jika terdapat kekeliruan data atau ingin melakukan pembaruan informasi,<br>
                silakan hubungi pihak sekolah untuk klarifikasi lebih lanjut.
              </p>';
          }
          echo '</div></div>';
      } elseif ($page === 'data') {
          $file = 'data_diri_siswa.php';
          if (file_exists($file)) {
              include $file;
          } else {
              echo '<p>Halaman tidak ditemukan.</p>';
          }
      } else {
          echo '<p>Halaman tidak ditemukan.</p>';
      }
      ?>
    </div>
  </div>
    <script>
      const info = document.getElementById('adminInfo');
      const menu = document.getElementById('adminDropdown');
      info.addEventListener('click', () => {
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
      });
      window.addEventListener('click', function (e) {
        if (!info.contains(e.target)) {
          menu.style.display = 'none';
        }
      });

      const cekBtn = document.getElementById('cekHasilBtn');
      const hasilBox = document.getElementById('hasilBox');
      if (cekBtn) {
        cekBtn.addEventListener('click', () => {
          hasilBox.style.display = 'flex';
          cekBtn.style.display = 'none';
        });
      }
    </script>
  </body>
</html>
