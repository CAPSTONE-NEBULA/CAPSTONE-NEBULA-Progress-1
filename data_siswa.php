<?php
include 'koneksi.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id_data_siswa';
$order = (isset($_GET['order']) && strtolower($_GET['order']) === 'desc') ? 'DESC' : 'ASC';
$tampilkan_semua = isset($_GET['semua']) && $_GET['semua'] === '1';
$limit = $tampilkan_semua ? 10000 : (isset($_GET['limit']) ? intval($_GET['limit']) : 25);

$allowed_sort_columns = ['id_data_siswa', 'nama', 'agama', 'kelurahan', 'kategori_ekonomi'];
if (!in_array($sort_by, $allowed_sort_columns)) $sort_by = 'id_data_siswa';

$sql = "
    SELECT ds.*, s.nama 
    FROM data_siswa ds
    JOIN siswa s ON ds.siswa_id_siswa = s.id_siswa
    WHERE (s.nama LIKE ? 
       OR ds.agama LIKE ? 
       OR ds.dusun LIKE ? 
       OR ds.kelurahan LIKE ?)
";
if (!empty($filter_kategori)) {
    $sql .= " AND ds.kategori_ekonomi = ?";
}
$sql .= " ORDER BY $sort_by $order LIMIT $limit";

$search_param = "%$search%";

if (!empty($filter_kategori)) {
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('sssss', $search_param, $search_param, $search_param, $search_param, $filter_kategori);
} else {
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('ssss', $search_param, $search_param, $search_param, $search_param);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<style>
    .toolbar-wrapper {
        background-color: #81c784; /* hijau pastel ceria */
        padding: 10px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .toolbar-section {
        display: flex;
        align-items: center;
        gap: 10px;
        border-right: 2px solid #ffffff;
        padding-right: 15px;
        margin-right: 15px;
    }

    .toolbar-section:last-child {
        border-right: none;
        margin-right: 0;
        padding-right: 0;
    }

    .toolbar-section label,
    .toolbar-section input,
    .toolbar-section select {
        font-size: 14px;
        color: white;
    }

    .toolbar-section input[type="text"],
    .toolbar-section select {
        padding: 6px 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        background-color: #ffffff;
        color: #333;
    }

    .toolbar-section button {
        padding: 6px 10px;
        border-radius: 6px;
        border: none;
        background-color: #ffffff;
        color: #388e3c;
        font-weight: bold;
        cursor: pointer;
    }

    .toolbar-section button:hover {
        background-color: #e8f5e9;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,0.08);
    }

    th, td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
        font-size: 14px;
        text-align: left;
    }

    th {
        background-color: #ffcc80; /* oranye pastel */
        color: #333;
        font-weight: bold;
        text-align: center;
    }

    tbody tr:hover {
        background-color: #f1f8e9; /* efek hover hijau muda */
    }

    .actions a {
        margin-right: 8px;
        text-decoration: none;
        color: #333;
        background-color: #e0f2f1;
        padding: 6px 10px;
        border-radius: 6px;
        transition: background-color 0.3s;
    }

    .actions a:hover {
        background-color: #b2dfdb;
    }
</style>

<form method="get" class="toolbar-wrapper">
    <input type="hidden" name="page" value="data_siswa">

    <div class="toolbar-section">
        <label><input type="checkbox" name="semua" value="1" onchange="this.form.submit()" <?= $tampilkan_semua ? 'checked' : '' ?>> Tampilkan Semua</label>
    </div>

    <div class="toolbar-section">
        <label>Jumlah Baris:</label>
        <select name="limit" onchange="this.form.submit()">
            <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
            <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
            <option value="200" <?= $limit == 200 ? 'selected' : '' ?>>200</option>
        </select>
    </div>

    <div class="toolbar-section">
        <input type="text" name="search" placeholder="Search" value="<?= htmlspecialchars($search) ?>">
        <button type="submit"><i class="fas fa-search"></i></button>
    </div>

    <div class="toolbar-section">
        <select name="kategori" onchange="this.form.submit()">
            <option value="">Kategori Ekonomi</option>
            <option value="Rendah" <?= $filter_kategori === 'Rendah' ? 'selected' : '' ?>>Rendah</option>
            <option value="Sedang" <?= $filter_kategori === 'Sedang' ? 'selected' : '' ?>>Sedang</option>
            <option value="Tinggi" <?= $filter_kategori === 'Tinggi' ? 'selected' : '' ?>>Tinggi</option>
        </select>
    </div>
</form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Agama</th>
            <th>RT</th>
            <th>RW</th>
            <th>Dusun</th>
            <th>Kelurahan</th>
            <th>Kecamatan</th>
            <th>Kode Pos</th>
            <th>Tinggal</th>
            <th>Transportasi</th>
            <th>KPS</th>
            <th>Anak Ke</th>
            <th>Saudara</th>
            <th>Jarak</th>
            <th>BB</th>
            <th>TB</th>
            <th>Penghasilan</th>
            <th>Ekonomi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id_data_siswa'] ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= $row['agama'] ?></td>
                    <td><?= $row['RT'] ?></td>
                    <td><?= $row['RW'] ?></td>
                    <td><?= $row['dusun'] ?></td>
                    <td><?= $row['kelurahan'] ?></td>
                    <td><?= $row['kecamatan'] ?></td>
                    <td><?= $row['kode_pos'] ?></td>
                    <td><?= $row['tinggal_bersama'] ?></td>
                    <td><?= $row['alat_transportasi'] ?></td>
                    <td><?= $row['penerima_kps'] ?></td>
                    <td><?= $row['anak_ke'] ?></td>
                    <td><?= $row['jml_saudara_kandung'] ?></td>
                    <td><?= $row['jarak_rumah'] ?></td>
                    <td><?= $row['berat_badan'] ?></td>
                    <td><?= $row['tinggi_badan'] ?></td>
                    <td><?= $row['total_penghasilan'] ?></td>
                    <td><?= $row['kategori_ekonomi'] ?></td>
                    <td class="actions">
                        <a href="edit.php?id=<?= $row['id_data_siswa'] ?>"><i class="fas fa-pen"></i></a>
                        <a href="hapus.php?id=<?= $row['id_data_siswa'] ?>" onclick="return confirm('Yakin ingin menghapus?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="20">Tidak ada data ditemukan.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
