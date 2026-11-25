<?php 
session_start();
include '../databases/connect.php';

// Pengecekan user apakah Admin ? 
if(!isset($_SESSION['admin_id'])){
    header('location: ../login.php');
    exit();
}
// Ambil ID dari form lalu filter, pastikan pakai metode post dan bertipe integer
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null) {
    http_response_code(400);
    exit('ID laporan tidak valid.');
}

// Siapkan prepare statement untuk mengeksekusi penghapusan data berdasarkan ID
$sql = $conn->prepare('DELETE FROM laporan_kerusakan WHERE id_laporan = ?');
$sql->execute([$id]); // Jalankan perintah bersama data

// Kirim message berupa laporan berhasil diupdate
$_SESSION['flash_msg'] = "Laporan berhasil dihapus!";

// Kembali ke halaman
header('Location: ../dashboard.php');
exit();
?>