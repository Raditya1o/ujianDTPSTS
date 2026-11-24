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

            header('Location: index.php');
            exit;
         } else {
            $error = 'Email dan Password salah!';
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
        }

        main {
            max-width: 350px;
            margin: 60px auto;
            padding: 20px;
            background: white;
            border: 1px solid #ccc;
        }

        label,
        input {
            display: block;
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        input,
        button {
            padding: 8px;
        }

        button {
            width: 100%;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
    <main>
        <h1>Login Admin</h1>

        <?php if ($error !== '') { ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php } ?>

        <form method="post" action="">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Masuk</button>
        </form>

        <p><a href="index.php">Kembali ke halaman utama</a></p>
    </main>
</body>
</html>
