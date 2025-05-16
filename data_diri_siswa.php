<?php
?>

<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background: #f9fff2;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .container {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 60px 20px;
    }

    .data-box {
        background: white;
        border-radius: 12px;
        padding: 60px;
        max-width: 1000px;
        width: 100%;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
    }

    .data-box h2 {
        text-align: center;
        margin-bottom: 40px;
        font-size: 28px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 18px;
    }

    td {
        padding: 14px 10px;
        border-bottom: 1px solid #ddd;
        vertical-align: top;
    }

    td:first-child {
        font-weight: bold;
        width: 35%;
    }

    td .icon {
        margin-right: 10px;
        color: #2e7d32;
        font-size: 18px;
    }

    footer {
        background-color: #006666;
        color: white;
        display: flex;
        justify-content: space-between; /* rata kiri-kanan */
        align-items: flex-start;
        padding: 30px 60px;
        font-size: 16px;
        width: 100%;
        box-sizing: border-box;
        position: relative; /* pastikan tidak melayang */
        margin-top: auto;   /* dorong ke bawah bila pakai flex column */
    }

    .footer-left,
    .footer-right {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .footer-right a {
        color: white;
        text-decoration: none;
    }

    .footer-right a:hover {
        text-decoration: underline;
    }
</style>


<div class="container">
    <div class="data-box">
        <h2><?= htmlspecialchars($data['nama_siswa']) ?></h2>
        <table>
            <tr><td><i class="fas fa-venus-mars icon"></i>Jenis Kelamin</td><td><?= $data['jenis_kelamin'] ?></td></tr>
            <tr><td><i class="fas fa-calendar-alt icon"></i>Tanggal Lahir</td><td><?= $data['tanggal_lahir'] ?></td></tr>
            <tr><td><i class="fas fa-map-marker-alt icon"></i>Alamat</td><td><?= $data['alamat'] ?></td></tr>
            <tr><td><i class="fas fa-school icon"></i>Kelas</td><td><?= $data['kelas'] ?></td></tr>
            <tr><td><i class="fas fa-male icon"></i>Nama Ayah</td><td><?= $data['nama_ayah'] ?></td></tr>
            <tr><td><i class="fas fa-briefcase icon"></i>Pekerjaan Ayah</td><td><?= $data['pekerjaan_ayah'] ?></td></tr>
            <tr><td><i class="fas fa-female icon"></i>Nama Ibu</td><td><?= $data['nama_ibu'] ?></td></tr>
            <tr><td><i class="fas fa-briefcase icon"></i>Pekerjaan Ibu</td><td><?= $data['pekerjaan_ibu'] ?></td></tr>
            <tr><td><i class="fas fa-wallet icon"></i>Total Penghasilan</td><td>Rp <?= number_format($total_penghasilan, 0, ',', '.') ?></td></tr>
        </table>
    </div>
</div>

<footer>
    <div class="footer-left">
        <div>Jl. Mangga Besar, Desa Margahayu</div>
        <div>Kecamatan Loa Kulu, Kutai Kartanegara</div>
        <div>Provinsi Kalimantan Timur</div>
    </div>
    <div class="footer-right">
        <div><i class="fas fa-envelope"></i> Email: <a href="sdn027loakulu@gmail.com">sdn027loakulu@gmail.com/a></div>
        <div><i class="fab fa-whatsapp"></i> WhatsApp: <a href="https://wa.me/6282349556499" target="_blank">+62 823-4955-6499</a></div>
        <div><i class="fab fa-instagram"></i> Instagram: <a href="https://www.instagram.com/sdn027loakulu?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank">@sdn027loakulu</a></div>
    </div>
</footer>

</body>
</html>