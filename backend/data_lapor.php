<?php
session_start();

include "../databases/connect.php";

//Siapkan data untuk menyimpan error
$error = '';

    if($_SERVER["REQUEST_METHOD"] === 'POST') {

        // Data dari formulir akan diambil dan diolah
        $nama_pelapor = trim($_POST['nama_pelapor']);
        $nama_fasilitas = trim($_POST['nama_fasilitas']);
        $lokasi = trim($_POST['lokasi']);
        $id_kategori = trim($_POST['id_kategori']);
        $deskripsi = trim($_POST['deskripsi']);
        $prioritas = trim($_POST['prioritas']);

        // Proses Validasi data
        if(empty($nama_pelapor)) {
            $error = 'Nama belum diisi';
            }elseif(strlen($nama_pelapor) < 3) {
                $error = 'Nama terlalu pendek! (Minimal 3 huruf)';
            }elseif(empty($nama_fasilitas)) {
                $error = 'Nama Fasilitas wajib diisi!';
            }elseif(strlen($nama_fasilitas) < 5) {
                $error = 'Nama Fasilitas Terlalu Pendek! (minimal 5 huruf)';
            }elseif(empty($lokasi)) {
                $error = 'Lokasi wajib diisi!';
            }elseif(strlen($lokasi) < 5){
                $error = 'Lokasi terlalu Pendek! (minimal 5 huruf)';
            }elseif(empty($deskripsi)){
                $error = "Deskripsi Wajib diisi!";
            }elseif(strlen($deskripsi) < 10 ){
                $error = "Deskripsi Terlalu Pendek! (minimal 10 huruf)";
            }elseif(empty($prioritas)) {
                $error = 'Prioritas wajb diisi!';
        }

        if($error === '') {
        try {
            // Gunakan prepared statement untuk memasuki data ke database lebih aman dan mencegah SQL Injection
            $sql = $conn->prepare(
                'INSERT INTO laporan_kerusakan (nama_pelapor, nama_fasilitas, lokasi, id_kategori, deskripsi, prioritas) VALUES( ?, ?, ?, ? ,? ,?); '
            );
            $sql ->execute([$nama_pelapor, $nama_fasilitas, $lokasi, $id_kategori, $deskripsi, $prioritas]); // Execute Perintah sql dengan variabel isi data input pengguna
            $_SESSION['flash_msg'] = "Laporan berhasil dikirim!";
            header('Location: ../index.php');
            exit();
        
        } catch(PDOException $e) { // Tangkap kondisi jika try gagal menjalankan perintah
            echo"Terjadi Kesalahan!" . $e->getMessage();
        }
        }
    }

    
    if($error !== ''){
      $_SESSION['flash_error'] = $error;
      header("Location: ../index.php");
      exit();
    }
?>