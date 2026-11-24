<?php
    session_start();

    // Variabel Pengecekan user Admin
    $isAdmin = (isset($_SESSION['admin_id']));

    // Import Koneksi Database
    include './databases/connect.php';

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
<body>
    <header class="bg-gray-800 p-3 text-center text-white  border-b-3  border-gray-800">
        <h1 class="text-2xl font-bold text-blue-500">Sistem Pelaporan Kerusakan Fasilitas Sekolah</h1>
        <p>Catat dan pantau laporan kerusakan fasilitas sekolah.</p>
        <nav aria-label="Navigasi utama" class="p-2">
            <a href="#form-laporan" class="bg-gray-500 p-1 pl-2 pr-2 rounded-sm hover:bg-gray-700">Buat Laporan</a>
            <a href="#daftar-laporan" class="bg-gray-500 p-1 pl-1 pr-2 rounded-sm hover:bg-gray-700">Daftar Laporan</a>
            <?php if($isAdmin) { ?>
                <a href="logout.php" class="bg-red-500 p-1 pl-2 pr-2 rounded-sm hover:bg-red-700">Logout Admin</a>
            <?php } else {?>
                <a href="login.php" class="bg-gray-500 p-1 pl-2 pr-2 rounded-sm hover:bg-gray-700">Login Admin</a>
            <?php } ?>
        </nav>
    </header>

    <main class="m-3 p-2 gap-3">  
        <?php if(!$isAdmin) {?>
        <section id="form-laporan" aria-labelledby="judul-form" >
            <h2 id="judul-form" class="text-center">Form Pelaporan Kerusakan</h2>
            <p class="text-center mb-4">Lengkapi data berikut untuk melaporkan fasilitas yang rusak.</p>

             <div class="flex justify-center align-center">

                 <form action="./backend/data_lapor.php" method="post" class="border p-4 flex justify-center align-center rounded-sm shadow-md border-gray-300 w-75">
                     <fieldset class="w-50">
                    <legend class="text-center">Data Pelaporan</legend>

                
                        <label for="nama_pelapor">Nama pelapor</label>
                        <input type="text" id="nama_pelapor" name="nama_pelapor" maxlength="100" required class="border rounded-sm w-full border-gray-300 p-2 ">
                    
                    
                
                        <label for="nama_fasilitas">Nama fasilitas</label>
                        <input type="text" id="nama_fasilitas" name="nama_fasilitas" maxlength="100" required class="border rounded-sm w-full border-gray-300 p-2 ">
                    
                    
                
                        <label for="lokasi">Lokasi kerusakan</label>
                        <input type="text" id="lokasi" name="lokasi" maxlength="100" required class="border rounded-sm w-full border-gray-300 p-2 ">
                    

                
                        <label for="id_kategori">Kategori fasilitas</label>
                        <select id="id_kategori" name="id_kategori" required class="border rounded-sm w-full border-gray-300 p-2 ">
                            <option value="">-- Pilih kategori --</option>
                            <option value="1">Elektronik</option>
                            <option value="2">Furnitur</option>
                            <option value="3">Jaringan</option>
                            <option value="4">Peralatan Kelas</option>
                        </select>
                    

                
                        <label for="deskripsi">Deskripsi kerusakan</label >
                        <textarea id="deskripsi" name="deskripsi" rows="4" required class="border rounded-sm border-gray-300 p-2 "></textarea>
                    
                    
                
                        <label for="prioritas">Prioritas</label>
                        <select id="prioritas" name="prioritas" required class="border border-gray-300 p-2 rounded-sm w-full mb-4">
                            <option value="">-- Pilih prioritas --</option>
                            <option value="Rendah">Rendah</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                        </select>
                    

                    <button name="submit" type="submit" class="border rounded-sm border-gray-300 p-2 w-full bg-gray-800 text-white cursor-pointer hover:shadow-lg">Kirim Laporan</button>
                </fieldset>
            </form>
        </div>
        </section>
        <?php } ?>
        <section id="daftar-laporan" aria-labelledby="judul-daftar" class="mt-12">
            <h2 id="judul-daftar" class="font-bold text-center text-xl mb-3">Daftar Laporan Kerusakan</h2>
            <?php
                echo '<p class="text-center">banyaknya laporan masuk: '. '<span class="font-bold">' . $jumlah . '</span>'. '</p>';
            ?>
            <table class="border-collapse border border-gray-400 w-full mr-3 ">
                <thead class="bg-gray-800 text-white border-b-2 border-gray-400 ">
                    <tr>
                        <th scope="col" class="border p-2 text-left border-gray-300 font-semibold">No.</th>
                        <th scope="col" class="border p-2 text-left border-gray-300 font-semibold">Fasilitas</th>
                        <th scope="col" class="border p-2 text-left border-gray-300 font-semibold ">Lokasi</th>
                        <th scope="col" class="border p-2 text-left border-gray-300 font-semibold">Pelapor</th>
                        <th scope="col" class="border p-2 text-left border-gray-300 font-semibold">kategori</th>
                        <th scope="col" class="border p-2 text-left border-gray-300 font-semibold">deskripsi</th>
                        <th scope="col" class="border p-2 text-left border-gray-300 font-semibold">Prioritas</th>
                        <th scope="col" class="border p-2 text-left border-gray-300 font-semibold">Status</th>
                        <th scope="col" class="border p-2 text-left border-gray-300 font-semibold">Tanggal</th>
                        <th scope="col" class="border p-2 text-left border-gray-300 font-semibold">Penanggung Jawab</th>
                        <?php  if($isAdmin) {?>
                            <th scope="col" class="border p-2 text-left border-gray-300 font-semibold">Aksi</th>
                        <?php }?>
                    </tr>
                </thead>
                <tbody>
                    
                        <?php 
                                if($sql_show){
                                    while ($row = $sql_show->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                                        <tr>
                                        <td scope="col" class="border p-2 text-left border-gray-300"><?= $row['id_laporan'] ?></td>
                                        <td scope="col" class="border p-2 text-left border-gray-300"><?= $row['nama_fasilitas']?></td>
                                        <td scope="col" class="border p-2 text-left border-gray-300"><?= $row['lokasi']?></td>
                                        <td scope="col" class="border p-2 text-left border-gray-300"><?= $row['nama_pelapor']?></td>
                                        <td scope="col" class="border p-2 text-left border-gray-300"><?= $row['nama_kategori']?></td>
                                        <td scope="col" class="border p-2 text-left border-gray-300"><?= $row['deskripsi']?></td>
                                        <td scope="col" class="border p-2 text-left border-gray-300"><?= $row['prioritas']?></td>
                                        <td scope="col" class="border p-2 text-left border-gray-300"><?= $row['status']?></td>
                                        <td scope="col" class="border p-2 text-left border-gray-300"><?= $row['tanggal_laporan'] ?></td> 
                                        <td scope="col" class="border p-2 text-left border-gray-300"><?=  $row['nama'] ?? '-' ?> </td> 
                                    <?php if($isAdmin) {?>
                                        <td scope="col"class=" border p-2 text-left border-gray-300">
                                            <div class="mb-4">
                                                <form action="./backend/edit_data.php" method="post">
                                                    <input type="hidden" name="id" value="<?= (int) $row['id_laporan'] ?>">
                                                     <button class="bg-blue-400 text-white border-0 pl-2 pr-2 pt-1 pb-1 mb-1 mt-1 rounded-xl w-25 ">Edit</button>
                                                </form>
                                                <form action="./backend/hapus_data.php" method="post" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                                    <input type="hidden" name="id" value="<?= (int) $row['id_laporan'] ?>">
                                                    <button class="bg-red-600 text-white border-0 pl-2 pr-2 pt-1 pb-1 mb-1 mt-1 rounded-xl w-25"> Delete </button>
                                                </form>
                                                <?php if($row['status'] === "Menunggu") {?>
                                                    <form action="./backend/proses_laporan.php" method="post">
                                                    <input type="hidden" name="id" value="<?= (int) $row['id_laporan'] ?>">
                                                     <button class="bg-gray-800 text-white border-0 pl-2 pr-2 pt-1 pb-1 mb-1 mt-1 rounded-xl  w-25 ">Mulai Proses</button>
                                                </form>
                                                <?php } elseif($row['status'] === "Diproses") { ?>
                                                    <form action="./backend/selesai_laporan.php" method="post">
                                                    <input type="hidden" name="id" value="<?= (int) $row['id_laporan'] ?>">
                                                     <button class="bg-gray-800 text-white border-0 pl-2 pr-2 pt-1 pb-1 mb-1 mt-1 rounded-xl  w-25 ">Laporan Selesai</button>
                                                </form>
                                                <?php }?>
                                            </div>
                                        </td>
                                        <?php }?>
                                        </tr>
                                        <?php
                                        }
                                    } else{
                                    echo '<td colspan="7">Belum ada laporan yang ditampilkan</td>';
                                }

                            
                        ?>
                    
                </tbody>
            </table>
        </section>
    </main>
    
    <footer class="bg-gray-800 p-4 border-t-3  border-gray-800  mt-4 text-white">
        <p class="text-center">&copy; 2026 Sistem Pelaporan Kerusakan Fasilitas Sekolah.</p>
    </footer>
</body>
</html>


