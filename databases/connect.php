<?php
$local = "localhost";
$user ="root";
$password ="";
$db ="db_laporan_fasilitas";

try {
    // Koneksikan Database
    $conn = new PDO("mysql:host=$local;dbname=$db", $user, $password);
    // Ubah pdo Error mode menjadi exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) { // Tangkap sebuah kondisi bila Koneksi database tidak berhasil
    echo "Connection Failed!" . $e->getMessage();
}

?>