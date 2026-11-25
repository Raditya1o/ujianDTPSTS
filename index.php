<?php
    session_start();
    // Import Koneksi Database
    include './databases/connect.php';

    // Ambil message error dari data lapor untuk ditampilkan
    $error = $_SESSION['flash_error'] ?? '';
    unset($_SESSION['flash_error']);

    // Ambil message success dari proses
    $msg = $_SESSION['flash_msg'] ?? '';
    unset($_SESSION['flash_msg']);

    // Variabel Pengecekan user Admin
    $isAdmin = (isset($_SESSION['admin_id']));

    if($isAdmin){
        header('location: dashboard.php');
        exit();
    }

    // Ambil jumlah atau total data yang ada pada database
    $total = 'SELECT count(*) from laporan_kerusakan';
    $sql_total = $conn->query($total);

    $jumlah = $sql_total->fetchColumn();

    // Ambil data yang ada pada database bersamaan dengan kategori_kerusakan untuk join
    $show = 'SELECT laporan_kerusakan.*,kategori_fasilitas.nama_kategori,admin.nama from laporan_kerusakan
            join kategori_fasilitas on laporan_kerusakan.id_kategori = kategori_fasilitas.id_kategori
            left join admin on laporan_kerusakan.admin_id = admin.admin_id
            order by laporan_kerusakan.id_laporan desc
            limit 10';
    $sql_show = $conn->query($show);

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
    <header class="bg-gray-800 p-4 flex justify-evenly text-center text-white  border-b-3  border-gray-800">
        <div>
            <h1 class="text-2xl font-bold ">Sistem Pelaporan Kerusakan Fasilitas Sekolah</h1>
            <p>Catat dan pantau laporan kerusakan fasilitas sekolah.</p>
        </div>
        <nav aria-label="Navigasi utama" class="p-2 flex items-center gap-2">
            <a href="#form-laporan" class="bg-white text-black p-1 pl-2 pr-2 rounded-sm hover:bg-blue-500 hover:text-white">Buat Laporan</a>
            <a href="dashboard.php" class="bg-white text-black p-1 pl-1 pr-2 rounded-sm hover:bg-blue-500 hover:text-white">Daftar Laporan</a>
            <?php if($isAdmin) { ?>
                <a href="logout.php" class="bg-red-500 p-1 pl-2 pr-2 rounded-sm hover:bg-red-700">Logout Admin</a>
            <?php } else {?>
                <a href="login.php" class="bg-white text-black p-1 pl-2 pr-2 rounded-sm hover:bg-blue-500 hover:text-white">Login Admin</a>
            <?php } ?>
        </nav>
    </header>

    <main class="m-3 p-2 gap-3 h-screen ">  

        <!-- Formulir Laporan Barang Hilang (Jika admin mengunjungi halaman ini, maka formulir akan di hide) -->

        <?php if(!$isAdmin) {?>
        <section id="form-laporan" aria-labelledby="judul-form" class >
            <?php if ($error !== '') { ?>
            <p class="text-red-500 text-center mb-2"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
            <?php } ?>
            <?php if ($msg !== '') { ?>
            <p class="text-green-600 text-center mb-2"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></p>
            <?php } ?>
            
            <div class="min-h-[70vh] flex justify-center items-center ">
                
                <form action="./backend/data_lapor.php" method="post" class="border p-4 flex justify-center flex-col items-center rounded-sm shadow-md border-gray-200  bg-white w-1/2 ">
                    <fieldset class="w-full">
                        <legend class="font-bold text-xl mb-2 mt-4 text-left">DATA PELAPORAN</legend>
                        <p class="text-left mb-4 font-medium">Lengkapi data berikut untuk melaporkan fasilitas yang rusak.</p>

                        <div class="flex flex-row w-full gap-3">
                            <div class=" flex-1 min-w-0"> 
                            <label for="nama_pelapor">Nama pelapor</label>
                            <input type="text" id="nama_pelapor" name="nama_pelapor" maxlength="100" required class="border rounded-sm w-full border-gray-300 p-2  ">
                            </div>
                            
                            <div class=" flex-1 min-w-0">
                            <label for="nama_fasilitas">Nama fasilitas</label>
                            <input type="text" id="nama_fasilitas" name="nama_fasilitas" maxlength="100" required class="border rounded-sm w-full border-gray-300 p-2 ">
                            </div>
                        </div>
                    
                        <div class="flex flex-row w-full gap-3">

                            <div class=" flex-1 min-w-0">

                                <label for="lokasi">Lokasi kerusakan</label>
                                <input type="text" id="lokasi" name="lokasi" maxlength="100" required class="border rounded-sm w-full border-gray-300 p-2 ">
                            </div>
                            
                            
                            <div class=" flex-1 min-w-0">

                                <label for="id_kategori">Kategori fasilitas</label>
                                <select id="id_kategori" name="id_kategori" required class="border rounded-sm w-full border-gray-300 p-2 ">
                                    <option value="">-- Pilih kategori --</option>
                                    <option value="1">Elektronik</option>
                                    <option value="2">Furnitur</option>
                                    <option value="3">Jaringan</option>
                                    <option value="4">Peralatan Kelas</option>
                                </select>
                            </div>
                            
                        </div>
                        <div class="flex flex-row w-full gap-3">
                            
                            <div class=" flex-1 min-w-0">
                                <label for="deskripsi" class="block">Deskripsi kerusakan</label>
                                <textarea id="deskripsi" name="deskripsi" required class="border rounded-sm border-gray-300 p-2 w-full resize-none "></textarea>
                                
                            </div>
                            
                            
                            <div class=" flex-1 min-w-0">
                                <label for="prioritas">Prioritas</label>
                                <select id="prioritas" name="prioritas" required class="border border-gray-300 p-2 rounded-sm w-full mb-4">
                                    <option value="">-- Pilih prioritas --</option>
                                    <option value="Rendah">Rendah</option>
                                    <option value="Sedang">Sedang</option>
                                    <option value="Tinggi">Tinggi</option>
                                </select>
                            </div>
                                
                        </div>

                    <button name="submit" type="submit" class="border rounded-sm  hover:bg-blue-700 p-2 w-1/4 mt-4 bg-blue-500 text-white cursor-pointer hover:shadow-lg">Kirim Laporan</button>
                </fieldset>
            </form>

            <!-- Akhir Formulir -->

        </div>
    </section>
    <?php } ?>
        
    </main>
    
    <footer class="bg-gray-800 p-4 border-t-3  border-gray-800  mt-4 text-white">
        <p class="text-center">&copy; 2026 Sistem Pelaporan Kerusakan Fasilitas Sekolah.</p>
    </footer>
</body>
</html>


