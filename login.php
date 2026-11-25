<?php
session_start();
include './databases/connect.php';

// Siapkan pesan untuk menampilkan kesalahan output
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Cek atau Validasi jika email atau password kosong
    if ($email === '' || $password === '') {
        $error = 'Email dan password harus diisi.';
    } else {

        // Cari Data admin berdasarkan email
        $sql = $conn->prepare(
            'SELECT * from admin where email = ?'
        );
        $sql->execute([$email]);
        $admin = $sql->fetch(PDO::FETCH_ASSOC);

        // Bila tidak ada data, maka tampilkan pesan error
        if(!$admin){
            $error = 'Email tidak ada';
        } elseif ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_nama'] = $admin['nama'];
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_email'] = $admin['email'];

            header('Location: dashboard.php');
            exit;
         } else {
            $error = 'Email atau Password salah!';
         }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-[#f2f2f2] font-sans">
    <main class="mx-auto mt-16 max-w-sm border border-gray-300 bg-white p-5">
        <h1 class="my-4 text-3xl font-bold">Login Admin</h1>

        <?php if ($error !== '') { ?>
            <p class="my-4 text-red-600"><?= htmlspecialchars($error) ?></p>
        <?php } ?>

        <form method="post" action="">
            <label for="email" class="mb-2 block">Email</label>
            <input type="email" id="email" name="email" required class="mb-2 block w-full border border-gray-300 p-2">

            <label for="password" class="mb-2 block">Password</label>
            <input type="password" id="password" name="password" required class="mb-2 block w-full border border-gray-300 p-2">

            <button type="submit" class="w-full border border-gray-300 bg-gray-200 p-2">Masuk</button>
        </form>

        <p class="my-4"><a href="index.php" class="text-blue-600 underline">Kembali ke halaman utama</a></p>
    </main>
</body>
</html>
