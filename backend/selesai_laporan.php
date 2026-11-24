<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

include '../databases/connect.php';

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id < 1) {
    http_response_code(400);
    exit('ID laporan tidak valid.');
}

$sql = $conn->prepare(
    'UPDATE laporan_kerusakan
     SET status = ?, admin_id = ?
     WHERE id_laporan = ? AND status = ?'
);

$sql->execute([
    'Selesai',
    $_SESSION['admin_id'],
    $id,
    'Diproses'
]);

if ($sql->rowCount() === 0) {
    http_response_code(409);
    exit('Laporan tidak ditemukan atau sudah diproses.');
}

header('Location: ../index.php');
exit;