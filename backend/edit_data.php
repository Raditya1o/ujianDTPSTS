<?php
session_start();

//Siapkan data untuk menyimpan error
$error = '';

    // Pengecekan user apakah Admin ? 
    if(!isset($_SESSION['admin_id'])){
        header('location: ../login.php');
        exit();
    }

    // Import Koneksi Database
    include '../databases/connect.php';

    // Ambil id dari form kemudian filter, pastikan memakai metode post dan bertipe int
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    // Jika id tidak ada/tidak berinteger
    if($id === false || $id === null) {
        http_response_code(400);
        exit('ID laporan tidak valid.');
    }

    // Ambil data laporan berdasarkan id_laporan
    $sql = $conn->prepare('SELECT * from Laporan_kerusakan where id_laporan = ?');
    $sql->execute([$id]);
    
    // Data laporan berdasarkan id disimpan disini
    $data_lama = $sql->fetch(PDO::FETCH_ASSOC);

    // Jika data id ada, tetapi isinya tidak ada (tidak ada data di id tersebut), return error 404
    if($data_lama === false) {
        http_response_code(404);
        exit('Data laporan tidak ditemukan');
    }

    // Ambil data kategori 
    $sql_kategori = $conn->query('SELECT * from kategori_fasilitas');
    $kategori = $sql_kategori->fetchAll(PDO::FETCH_ASSOC);


    // lakukan update ketika tombol 'update' di klik
    if(isset($_POST['update'])) {

        $nama_pelapor = trim($_POST['nama_pelapor'] ?? '');
        $nama_fasilitas = trim($_POST['nama_fasilitas'] ?? '');
        $lokasi = trim($_POST['lokasi'] ?? '');
        $id_kategori = $_POST['id_kategori']; 
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $prioritas = $_POST['prioritas'] ?? '';

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
        // Prepare statement untuk mempersiapakan perintah SQL
        $sql = $conn->prepare(
            'UPDATE laporan_kerusakan
            SET nama_pelapor = ?,
             nama_fasilitas = ?,
             lokasi = ?,
             id_kategori = ?,
             deskripsi = ?,
             prioritas = ?
         WHERE id_laporan = ?'
    );

    // Jalankan perintah dengan data yang diinput
    $sql->execute([
        $nama_pelapor,
        $nama_fasilitas,
        $lokasi,
        $id_kategori,
        $deskripsi,
        $prioritas,
        $id
    ]);
    
    // Kirim message berupa laporan berhasil diupdate
    $_SESSION['flash_msg'] = "Laporan berhasil diupdate!";
    // Kembali ke halaman semulla
    header('Location: ../dashboard.php');
    exit;
    }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelaporan Kerusakan Fasilitas Sekolah</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-[#edf0f6]">
   <header class="bg-gray-800 p-4 flex flex-col justify-evenly items-center text-center text-white border-b-3  border-gray-800 sm:flex-row ">
        <div>
            <h1 class="text-2xl font-bold">Sistem Pelaporan Kerusakan Fasilitas Sekolah</h1>
            <p>Catat dan pantau laporan kerusakan fasilitas sekolah.</p>
        </div>
        <nav aria-label="Navigasi utama" class="p-2 flex items-center gap-2">
            <a href="#form-laporan" class="bg-white text-black p-1 pl-2 pr-2 rounded-sm hover:bg-blue-500 hover:text-white">Buat Laporan</a>
            <a href="#daftar-laporan" class="bg-white text-black p-1 pl-2 pr-2 rounded-sm hover:bg-blue-500 hover:text-white">Daftar Laporan</a>
        </nav>
    </header>

    <main class="m-3 p-2 gap-3 h-screen">  
        <section id="form-laporan" aria-labelledby="judul-form" >
             <?php if ($error !== '') { ?>
                <p class="text-red-500 text-center mb-2"><?= htmlspecialchars($error) ?></p>
            <?php } ?>

            <!-- TANTANGAN BONUS CREATE: Buat proses PHP agar POST menyimpan data. -->
             <div class="min-h-[70vh] flex justify-center items-center">

             <?php 
                
             ?>
                 <form action="" method="post" class="border p-4 flex justify-center flex-col items-center rounded-sm shadow-md border-gray-200  bg-white w-full sm:w-1/2 mb-1 ">
                       <input
                            type="hidden"
                            name="id"
                            value="<?= (int) $data_lama['id_laporan'] ?>"
                        >

                     <fieldset class="w-full">
                        <legend class="font-bold text-xl mb-2 mt-4 text-left">DATA PELAPORAN</legend>
                        <p class="text-left mb-4 font-medium">Edit data laporan berikut</p>

                        <div class="flex flex-col w-full gap-3 sm:flex-row">
                            <div class="flex-1 min-w-0">
                                <label for="nama_pelapor">Nama pelapor</label>
                                <input type="text" id="nama_pelapor" name="nama_pelapor" maxlength="100" value="<?= htmlspecialchars($data_lama['nama_pelapor']) ?>" class="border rounded-sm w-full border-gray-300 p-2">
                            </div>

                            <div class="flex-1 min-w-0">
                                <label for="nama_fasilitas">Nama fasilitas</label>
                                <input type="text" id="nama_fasilitas" name="nama_fasilitas" maxlength="100" value="<?= htmlspecialchars($data_lama['nama_fasilitas']) ?>" class="border rounded-sm w-full border-gray-300 p-2">
                            </div>
                        </div>

                        <div class="flex flex-col w-full gap-3 sm:flex-row">
                            <div class="flex-1 min-w-0">
                                <label for="lokasi">Lokasi kerusakan</label>
                                <input type="text" id="lokasi" name="lokasi" maxlength="100" value="<?= htmlspecialchars($data_lama['lokasi']) ?>" class="border rounded-sm w-full border-gray-300 p-2">
                            </div>

                            <div class="flex-1 min-w-0">
                                <label for="id_kategori">Kategori fasilitas</label>
                                <select id="id_kategori" name="id_kategori" required class="border rounded-sm w-full border-gray-300 p-2">
                                    <?php foreach($kategori as $k) {?>
                                        <option value="<?= (int) $k['id_kategori']?>" <?= (int) $data_lama['id_kategori'] === (int) $k['id_kategori'] ? 'selected' : '' ?>> <?= htmlspecialchars($k['nama_kategori']) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col w-full gap-3 sm:flex-row">
                            <div class="flex-1 min-w-0">
                                <label for="deskripsi" class="block">Deskripsi kerusakan</label>
                                <textarea id="deskripsi" name="deskripsi" rows="4" required class="border rounded-sm border-gray-300 p-2 w-full resize-none"><?= htmlspecialchars($data_lama['deskripsi']) ?> </textarea>
                            </div>

                            <div class="flex-1 min-w-0">
                                <label for="prioritas">Prioritas</label>
                                <select id="prioritas" name="prioritas" required class="border border-gray-300 p-2 rounded-sm w-full mb-4">
                                    <?php foreach(['Rendah', 'Sedang', 'Tinggi'] as $p) {?>
                                        <option value="<?= htmlspecialchars($p) ?>" <?= $data_lama['prioritas'] === $p ? 'selected' : '' ?>> <?= htmlspecialchars($p) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <button name="update" type="submit" value="1" class="border rounded-sm hover:bg-blue-700 p-2 w-1/2 md:1/4 mt-4 bg-blue-500 text-white cursor-pointer hover:shadow-lg">Edit Laporan</button>
                        <a href="../dashboard.php" class="block text-left ml-6.5">Kembali ke Awal</a>
                    </fieldset>
            </form>

        </div>
        </section>
    </main>
    
    <footer class="bg-gray-800 p-4 border-t-3  border-gray-800 mt-22.5 text-white sm:mt-4">
        <p class="text-center">&copy; 2026 Sistem Pelaporan Kerusakan Fasilitas Sekolah.</p>
    </footer>
</body>
</html>


