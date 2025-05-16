<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "sekolah_sd");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
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

// Query insert
$sql = "INSERT INTO orang_tua (
    nama_ayah, th_lahir_ayah, pendidikan_ayah, pekerjaan_ayah, penghasilan_ayah,
    nama_ibu, th_lahir_ibu, pendidikan_ibu, pekerjaan_ibu, penghasilan_ibu
) VALUES (
    '$nama_ayah', '$th_lahir_ayah', '$pendidikan_ayah', '$pekerjaan_ayah', '$penghasilan_ayah',
    '$nama_ibu', '$th_lahir_ibu', '$pendidikan_ibu', '$pekerjaan_ibu', '$penghasilan_ibu'
)";

// Eksekusi query
if ($conn->query($sql) === TRUE) {
    echo "<script>
        alert('Data orang tua berhasil disimpan');
        window.history.back();
    </script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
