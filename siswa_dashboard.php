<?php
// Pastikan file ini dipanggil setelah $data dan $layak dideklarasikan di siswa.php
?>

<div class="welcome">
  <div class="welcome-text">
    <h1>Halo, <?= strtoupper($data['nama_siswa']); ?></h1>
    <p>Selamat datang di Portal Informasi Bantuan Pendidikan SDN 027 Loa Kulu.</p>
  </div>
  <img src="img/foto.jpg" class="banner-image" alt="foto banner">
</div>

<div class="status-box">
  <img src="img/lovepik.png" alt="Ilustrasi">
  <div class="status-text">
    <?php if ($layak): ?>
      <h2 style="color: #2e7d32;">Selamat! Anda Layak Menerima Bantuan</h2>
      <p>Berdasarkan hasil evaluasi sistem, Anda masuk dalam kriteria prioritas penerima bantuan pendidikan.</p>
    <?php else: ?>
      <h2>Mohon Maaf, Anda Belum Layak Menerima Bantuan</h2>
      <p>Berdasarkan hasil evaluasi sistem, kondisi Anda belum memenuhi kriteria prioritas penerima bantuan sesuai indikator yang ditetapkan.<br>
      Jika Anda merasa ada kesalahan data, silakan hubungi wali kelas atau pihak sekolah untuk klarifikasi.</p>
    <?php endif; ?>
  </div>
</div>

<footer>
  Portal ini dikelola oleh SDN 027 Loa Kulu | Kontak: admin@sdn027.sch.id
</footer>
