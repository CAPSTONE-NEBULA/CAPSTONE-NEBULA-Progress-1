<?php
include 'koneksi.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'pekerjaan_ayah';
$order = (isset($_GET['order']) && strtolower($_GET['order']) === 'desc') ? 'DESC' : 'ASC';
$tampilkan_semua = isset($_GET['semua']) && $_GET['semua'] === '1';
$limit = $tampilkan_semua ? 10000 : (isset($_GET['limit']) ? intval($_GET['limit']) : 25);

$allowed_sort_columns = [
  'pekerjaan_ayah', 'penghasilan_ayah', 'pekerjaan_ibu', 'penghasilan_ibu'
];
if (!in_array($sort_by, $allowed_sort_columns)) $sort_by = 'pekerjaan_ayah';

$sql = "
    SELECT * FROM orang_tua
    WHERE nama_ayah LIKE ? 
       OR nama_ibu LIKE ? 
       OR pekerjaan_ayah LIKE ? 
       OR CAST(penghasilan_ayah AS CHAR) LIKE ? 
       OR pekerjaan_ibu LIKE ? 
       OR CAST(penghasilan_ibu AS CHAR) LIKE ?
    ORDER BY $sort_by $order
    LIMIT $limit
";
$stmt = $connection->prepare($sql);
$search_param = '%' . $search . '%';
$stmt->bind_param('ssssss', $search_param, $search_param, $search_param, $search_param, $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>
<style>
    .toolbar-wrapper {
        background-color: #64b5f6; /* biru pastel lembut */
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
        color: #1565c0;
        font-weight: bold;
        cursor: pointer;
    }

    .toolbar-section button:hover {
        background-color: #e3f2fd;
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
        background-color: #fff59d; /* kuning pastel */
        color: #333;
        font-weight: bold;
        text-align: center;
    }

    tbody tr:hover {
        background-color: #e3f2fd; /* biru sangat muda saat hover */
    }

    .actions a {
        margin-right: 8px;
        text-decoration: none;
        color: #333;
        background-color: #e1f5fe;
        padding: 6px 10px;
        border-radius: 6px;
        transition: background-color 0.3s;
    }

    .actions a:hover {
        background-color: #b3e5fc;
    }
</style>

<div class="card">
  <h2>Data Orang Tua</h2>
  <form method="get" class="toolbar-wrapper">
    <input type="hidden" name="page" value="data_orang_tua">

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
        <select name="sort_by" onchange="this.form.submit()">
            <option value="">sort by</option>
        <option value="pekerjaan_ayah" <?= $sort_by === 'pekerjaan_ayah' ? 'selected' : '' ?>>Pekerjaan Ayah</option>
        <option value="penghasilan_ayah" <?= $sort_by === 'penghasilan_ayah' ? 'selected' : '' ?>>Penghasilan Ayah</option>
        <option value="pekerjaan_ibu" <?= $sort_by === 'pekerjaan_ibu' ? 'selected' : '' ?>>Pekerjaan Ibu</option>
        <option value="penghasilan_ibu" <?= $sort_by === 'penghasilan_ibu' ? 'selected' : '' ?>>Penghasilan Ibu</option>
      </select>
    </div>
  </form>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nama Ayah</th>
        <th>Lahir Ayah</th>
        <th>Pendidikan Ayah</th>
        <th>Pekerjaan Ayah</th>
        <th>Penghasilan Ayah</th>
        <th>Nama Ibu</th>
        <th>Lahir Ibu</th>
        <th>Pendidikan Ibu</th>
        <th>Pekerjaan Ibu</th>
        <th>Penghasilan Ibu</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id_orang_tua'] ?></td>
            <td><?= htmlspecialchars($row['nama_ayah']) ?></td>
            <td><?= $row['th_lahir_ayah'] ?></td>
            <td><?= $row['pendidikan_ayah'] ?></td>
            <td><?= $row['pekerjaan_ayah'] ?></td>
            <td><?= $row['penghasilan_ayah'] ?></td>
            <td><?= htmlspecialchars($row['nama_ibu']) ?></td>
            <td><?= $row['th_lahir_ibu'] ?></td>
            <td><?= $row['pendidikan_ibu'] ?></td>
            <td><?= $row['pekerjaan_ibu'] ?></td>
            <td><?= $row['penghasilan_ibu'] ?></td>
            <td class="actions">
              <a href="edit_orang_tua.php?id=<?= $row['id_orang_tua'] ?>"><i class="fas fa-pen"></i></a>
              <a href="hapus_ortu.php?id=<?= $row['id_orang_tua'] ?>" onclick="return confirm('Yakin ingin menghapus?')"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="12">Tidak ada data ditemukan.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
