<?php
include "./databases/connect.php";

    if(isset($_POST['submit'])) {
        $nama_pelapor = $_POST['nama_pelapor'];
        $nama_fasilitas = $_POST['nama_fasilitas'];
        $lokasi = $_POST['lokasi'];
        $id_kategori = $_POST['id_kategori'];
        $deskripsi = $_POST['deskripsi'];
        $prioritas = $_POST['prioritas'];


        $sql_insert = "INSERT INTO laporan_kerusakan (nama_pelapor, nama_fasilitas, lokasi, id_kategori, deskripsi, prioritas) VALUE ('$nama_pelapor','$nama_fasilitas','$lokasi','$id_kategori','$deskripsi', '$prioritas')";
        $mysqli = mysqli_query($conn, $sql_insert);

        if($mysqli){
            header("Location: index.php");
            exit();
        }
    }
?>