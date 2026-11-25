<?php
session_start();

// Jika bukan admin yang mengakses, maka arahkan ke login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

include '../databases/connect.php';

// Ambil id dari form lalu filter, pastikan input pakai metode post dan id bertipe int
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

// jika id nilainya false / null / id tidak masuk akal
if ($id === false || $id === null || $id < 1) {
    http_response_code(400);
    exit('ID laporan tidak valid.');
}

// Update status dan id_admin laporan berdasarkan id laporan dan status (menunggu)
$sql = $conn->prepare(
    'UPDATE laporan_kerusakan
     SET status = ?, admin_id = ?
     WHERE id_laporan = ? AND status = ?'
);

// Eksekusi perintah prepared
$sql->execute([
    'Selesai',
    $_SESSION['admin_id'],
    $id,
    'Diproses'
]);


// menghitung jumlah baris yang terpengaruh dari execute, 
// jika 0 artinya tidak ada yang berubah dari execute tadi
// Kode mengembalikan response 409 dan memberi pesan laporan tidak ditemukan atau sebenarnya sudah diproses
if ($sql->rowCount() === 0) {
    http_response_code(409);
    exit('Laporan tidak ditemukan atau sudah diproses.');
}

header('Location: ../dashboard.php');
exit;