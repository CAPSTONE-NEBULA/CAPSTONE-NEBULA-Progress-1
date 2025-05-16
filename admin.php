<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$loggedInUser = $_SESSION['username'] ?? 'Admin';
$sekolah = $_SESSION['sekolah'] ?? 'SDN 027 Loa Kulu';
$page = $_GET['page'] ?? 'dashboard';

include 'koneksi.php';
function getDataGrouped($field) {
    global $connection;
    $data = [];
    $query = "SELECT $field AS label, COUNT(*) as total FROM data_siswa GROUP BY $field";
    $result = $connection->query($query);
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'label' => htmlspecialchars($row['label']),
            'total' => (int)$row['total']
        ];
    }
    return [
        'labels' => array_column($data, 'label'),
        'counts' => array_column($data, 'total')
    ];
}
$ekonomi = getDataGrouped('kategori_ekonomi');
$penghasilan = getDataGrouped('total_penghasilan');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f9fff2; /* sangat terang, hampir putih dengan nuansa hijau muda */
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
    .sidebar ul {
        list-style: none;
    }
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
        background-color: #FFEB3B; /* kuning terang */
        color: #333;
        transform: scale(1.05);
    }
    .submenu {
        display: none;
        background-color: #66bb6a; /* hijau terang */
    }
    .submenu li a {
        font-size: 20px;
        color: #f0f0f0;
        padding-left: 40px;
    }
    .main {
        margin-left: 300px;
        width: calc(100% - 300px);
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    .admin-bar {
        background: #FFF8E1; /* cream */
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
    .dropdown-menu i {
        color: #c47100;
        margin-right: 8px;
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
    .header-banner {
        position: relative;
        background-image: url('img/guru.jpg');
        background-size: cover;
        background-position: center;
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .header-overlay {
        background: rgba(0, 0, 0, 0.5);
        padding: 30px;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: white;
    }
    .header-overlay h1 {
        font-size: 47px;
        font-weight: bold;
        margin-bottom: 12px;
    }
    .header-overlay p {
        font-size: 25px;
    }
    .page-content {
        flex: 1;
        padding: 70px 40px;
    }
    .chart-background {
        background-color: #ffffff;
        padding: 40px 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        width: 92%;
        margin: 0 auto;
    }
    .chart-wrapper {
        display: flex;
        justify-content: center;
        gap: 50px;
        flex-wrap: wrap;
    }
    .chart-box {
        background: #fffde7; /* kuning pastel */
        padding: 50px;
        width: 500px;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        text-align: center;
    }
    .chart-box h3 {
        font-weight: 600;
        margin-bottom: 15px;
        font-size: 25px;
    }
    canvas {
        height: 300px !important;
    }
    footer {
        text-align: center;
        font-size: 13px;
        color: #666;
        padding: 20px;
        background: #f1f8e9; /* hijau pastel */
        border-top: 1px solid #ccc;
        margin-top: auto;
    }
</style>
    </head>
        <body>
        <div class="sidebar">
            <div class="brand">SISD</div>
            <ul>
                <li><a href="?page=dashboard">📊 Dashboard</a></li>
                <li><a href="#" class="dropdown-toggle">📁 Data Siswa</a>
                    <ul class="submenu">
                        <li><a href="?page=data_siswa">Data Siswa</a></li>
                        <li><a href="tambah.php">Input Data Siswa</a></li>
                    </ul>
                </li>
                <li><a href="#" class="dropdown-toggle">📂 Data Orang Tua</a>
                    <ul class="submenu">
                        <li><a href="?page=data_orang_tua">Data Orang Tua</a></li>
                        <li><a href="tambah_data_orang_tua.php">Input Data Orang Tua</a></li>
                    </ul>
                </li>
                <li><a href="tambah_akun.php">👤 Tambah Akun</a></li>
            </ul>
        </div>
        <div class="main">
            <div class="admin-bar">
                <div class="admin-info" id="adminInfo">
                    <i class="fas fa-user-circle"></i>
                    <div class="admin-label">
                        <div class="username"><?= htmlspecialchars($loggedInUser) ?></div>
                        <div class="school"><?= htmlspecialchars($sekolah) ?></div>
                    </div>
                    <div class="dropdown-menu" id="adminDropdown">
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>
            <?php if ($page === 'dashboard') : ?>
                <div class="header-banner">
                    <div class="header-overlay">
                        <h1>Selamat Datang, <?= htmlspecialchars($loggedInUser) ?>!</h1>
                        <p>Jl. Mangga Besar, Desa Margahayu, Kecamatan Loa Kulu<br>Kabupaten Kutai Kartanegara, Provinsi Kalimantan Timur.</p>
                    </div>
                </div>
            <?php endif; ?>
            <div class="page-content">
            <?php
            if ($page === 'dashboard') {
                echo '<div class="chart-background">
                        <div class="chart-wrapper">
                            <div class="chart-box">
                                <h3>Diagram Kategori Ekonomi</h3>
                                <canvas id="chartEkonomi"></canvas>
                            </div>
                            <div class="chart-box">
                                <h3>Grafik penghasilan orang tua</h3>
                                <canvas id="chartPenghasilan"></canvas>
                            </div>
                        </div>
                    </div>';
            } elseif ($page === 'data_siswa') {
                include 'data_siswa.php';
            } elseif ($page === 'data_orang_tua') {
                include 'data_orang_tua.php';
            } else {
                echo "<p>Halaman tidak ditemukan.</p>";
            }
            ?>
        </div>
    </div>
        <script>
            // Sidebar dropdown
            document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelectorAll('.submenu').forEach(menu => {
                        if (menu !== toggle.nextElementSibling) menu.style.display = 'none';
                    });
                    const submenu = toggle.nextElementSibling;
                    submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
                });
            });

            // Admin info dropdown
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

            const ekonomiLabels = <?= json_encode($ekonomi['labels']) ?>;
            const ekonomiCounts = <?= json_encode($ekonomi['counts']) ?>;
            const penghasilanLabels = <?= json_encode($penghasilan['labels']) ?>;
            const penghasilanCounts = <?= json_encode($penghasilan['counts']) ?>;
            new Chart(document.getElementById('chartEkonomi'), {
                type: 'pie',
                data: {
                    labels: ekonomiLabels,
                    datasets: [{
                        data: ekonomiCounts,
                        backgroundColor: ['#e67e22', '#f1c40f', '#2ecc71']
                    }]
                }
            });
            new Chart(document.getElementById('chartPenghasilan'), {
                type: 'bar',
                data: {
                    labels: penghasilanLabels,
                    datasets: [{
                        data: penghasilanCounts,
                        backgroundColor: ['#8e44ad', '#3498db', '#e74c3c']
                    }]
                },
                options: {
                    scales: { y: { beginAtZero: true } }
                }
            });
        </script>
    </body>
</html>