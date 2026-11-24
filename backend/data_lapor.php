<?php
include "../databases/connect.php";

    if($_SERVER["REQUEST_METHOD"] === 'POST') {

        // Data dari formulir akan diambil dan diolah
        $nama_pelapor = trim($_POST['nama_pelapor']);
        $nama_fasilitas = trim($_POST['nama_fasilitas']);
        $lokasi = trim($_POST['lokasi']);
        $id_kategori = trim($_POST['id_kategori']);
        $deskripsi = trim($_POST['deskripsi']);
        $prioritas = trim($_POST['prioritas']);

        try {
            // Gunakan prepared statement untuk memasuki data ke database lebih aman dan mencegah SQL Injection
            $sql = $conn->prepare(
                'INSERT INTO laporan_kerusakan (nama_pelapor, nama_fasilitas, lokasi, id_kategori, deskripsi, prioritas) VALUES( ?, ?, ?, ? ,? ,?); '
            );
            $sql ->execute([$nama_pelapor, $nama_fasilitas, $lokasi, $id_kategori, $deskripsi, $prioritas]); // Execute Perintah sql dengan variabel isi data input pengguna
            header('Location: ./index.php'); 
            exit();
        
        } catch(PDOException $e) { // Tangkap kondisi jika try gagal menjalankan perintah
            echo"Terjadi Kesalahan!" . $e->getMessage();
        }

    }
?>