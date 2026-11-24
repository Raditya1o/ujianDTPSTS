<?php
    session_start();

    // Pengecekan user apakah Admin ? 
    if(!isset($_SESSION['admin_id'])){
        header('location: index.php');
        exit();
    }

    // Import Koneksi Database
    include '../databases/connect.php';

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if($id === false || $id === null) {
        http_response_code(400);
        exit('ID laporan tidak valid.');
    }

    // Ambil data laporan berdasarkan id_laporan
    $sql = $conn->prepare('SELECT * from Laporan_kerusakan where id_laporan = ?');
    $sql->execute([$id]);
    
    // Data laporan berdasarkan id disimpan disini
    $data_lama = $sql->fetch(PDO::FETCH_ASSOC);

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
    
    // Kembali ke halaman semulla
    header('Location: ../index.php');
    exit;
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelaporan Kerusakan Fasilitas Sekolah</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- TANTANGAN UI/UX: Tambahkan styling CSS, Bootstrap, atau Tailwind sendiri. -->
</head>
<body>
    <!-- Bagian ini merupakan starter HTML. Belum ada koneksi database ataupun query. -->
    <header class="bg-gray-800 p-3 text-center text-white  border-b-3  border-gray-800">
        <h1 class="text-2xl font-bold text-blue-500">Sistem Pelaporan Kerusakan Fasilitas Sekolah</h1>
        <p>Catat dan pantau laporan kerusakan fasilitas sekolah.</p>
        <nav aria-label="Navigasi utama" class="p-2">
            <a href="#form-laporan" class="bg-gray-500 p-1 pl-2 pr-2 rounded-sm hover:bg-gray-700">Buat Laporan</a>
            <a href="#daftar-laporan" class="bg-gray-500 p-1 pl-1 pr-2 rounded-sm hover:bg-gray-700">Daftar Laporan</a>
        </nav>
    </header>

    <main class="m-3 p-2 gap-3">  
        <section id="form-laporan" aria-labelledby="judul-form" >
            <h2 id="judul-form" class="text-center">Edit Data Pelaporan Kerusakan</h2>
            <p class="text-center mb-4">Edit data laporan berikut</p>

            <!-- TANTANGAN BONUS CREATE: Buat proses PHP agar POST menyimpan data. -->
             <div class="flex justify-center align-center">

             <?php 
                
             ?>
                 <form action="" method="post" class="border p-4 flex justify-center align-center rounded-sm shadow-md border-gray-300 w-75">
                       <input
                            type="hidden"
                            name="id"
                            value="<?= (int) $data_lama['id_laporan'] ?>"
                        >

                     <fieldset class="w-50">
                    <legend class="text-center">Data Pelaporan</legend>

                
                        <label for="nama_pelapor">Nama pelapor</label>
                        <input type="text" id="nama_pelapor" name="nama_pelapor" maxlength="100" value="<?= htmlspecialchars($data_lama['nama_pelapor']) ?>" class="border rounded-sm w-full border-gray-300 p-2 ">
                    
                    
                
                        <label for="nama_fasilitas">Nama fasilitas</label>
                        <input type="text" id="nama_fasilitas" name="nama_fasilitas" maxlength="100" value="<?= htmlspecialchars($data_lama['nama_fasilitas']) ?>" class="border rounded-sm w-full border-gray-300 p-2 ">
                    
                    
                
                        <label for="lokasi">Lokasi kerusakan</label>
                        <input type="text" id="lokasi" name="lokasi" maxlength="100" value="<?= htmlspecialchars($data_lama['lokasi']) ?>" class="border rounded-sm w-full border-gray-300 p-2 ">
                    

                
                        <label for="id_kategori">Kategori fasilitas</label>
                        <select id="id_kategori" name="id_kategori" required class="border rounded-sm w-full border-gray-300 p-2 ">
                            <?php foreach($kategori as $k) {?>
                                <option value="<?= (int) $k['id_kategori']?>" <?= (int) $data_lama['id_kategori'] === (int) $k['id_kategori'] ? 'selected' : '' ?>> <?= htmlspecialchars($k['nama_kategori']) ?></option>
                            <?php } ?>
                        </select>
                    

                
                        <label for="deskripsi">Deskripsi kerusakan</label >
                        <textarea id="deskripsi" name="deskripsi" rows="4" required class="border rounded-sm border-gray-300 p-2 "><?= htmlspecialchars($data_lama['deskripsi']) ?> </textarea>
                    
                    
<!--                 
                        <label for="prioritas">Prioritas</label>
                        <select id="prioritas" name="prioritas" required class="border border-gray-300 p-2 rounded-sm w-full mb-4">
                            <?php foreach(['Rendah', 'Sedang', 'Tinggi'] as $p) {?>
                             <option value="<?= htmlspecialchars($p) ?>" <?= $data_lama['prioritas'] === $p ? 'selected' : '' ?>> <?= htmlspecialchars($p) ?></option>
                             <?php } ?>
                        </select> -->
                    

                    <button name="update" type="submit" value="1" class="border rounded-sm border-gray-300 p-2 w-full bg-gray-800 text-white cursor-pointer hover:shadow-lg">Kirim Laporan</button>
                    <a href="../index.php" class="block text-center">Kembali ke Awal</a>
                </fieldset>
            </form>

        </div>
        </section>
    </main>
    
    <footer class="bg-gray-800 p-4 border-t-3  border-gray-800  mt-4 text-white">
        <p class="text-center">&copy; 2026 Sistem Pelaporan Kerusakan Fasilitas Sekolah.</p>
    </footer>
</body>
</html>


