<?php 
    include_once('./databases/connect.php');

    $total = mysqli_query($conn, 'SELECT * from laporan_kerusakan');

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
            <h2 id="judul-form" class="text-center">Form Pelaporan Kerusakan</h2>
            <p class="text-center mb-4">Lengkapi data berikut untuk melaporkan fasilitas yang rusak.</p>

            <!-- TANTANGAN BONUS CREATE: Buat proses PHP agar POST menyimpan data. -->
             <div class="flex justify-center align-center">

                 <form action="lapor.php" method="post" class="border p-4 flex justify-center align-center rounded-sm shadow-md border-gray-300 w-75">
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
                            <!-- Nilai berikut sesuai dengan data awal kategori_fasilitas. -->
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

        <section id="daftar-laporan" aria-labelledby="judul-daftar" class="mt-12">
            <h2 id="judul-daftar" class="font-bold text-center text-xl mb-3">Daftar Laporan Kerusakan</h2>
            <?php
                echo '<p class="text-center">banyaknya laporan masuk: '. '<span class="font-bold">' . mysqli_num_rows($total). '</span>'. '</p>';

            ?>
            <!-- TANTANGAN WAJIB:
                 1. Buat sendiri koneksi PHP ke MySQL.
                 2. Jalankan query SELECT untuk mengambil data dari laporan_kerusakan.
                 3. Ganti baris placeholder pada tbody dengan perulangan hasil query.
                 4. Jangan mengetik data database secara manual di HTML.
            -->
            <table class="border-collapse border border-gray-400 w-full mr-3 ">
                <thead class="bg-gray-800 text-white border-b-2 border-gray-400 ">
                    <tr>
                        <th scope="col" class="border-gray-300 font-semibold">No.</th>
                        <th scope="col" class="border-gray-300 font-semibold">Fasilitas</th>
                        <th scope="col" class="border-gray-300 font-semibold ">Lokasi</th>
                        <th scope="col" class="border-gray-300 font-semibold">Pelapor</th>
                        <th scope="col" class="border-gray-300 font-semibold">Prioritas</th>
                        <th scope="col" class="border-gray-300 font-semibold">Status</th>
                        <th scope="col" class="border-gray-300 font-semibold">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    
                        <?php 
                            $select = "SELECT * FROM laporan_kerusakan ORDER BY id_laporan DESC limit 10";
                                $result = mysqli_query($conn, $select);
                                if($result){
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<tr>';
                                        echo '<td scope="col" class="border-gray-300">'. $row['id_laporan'].'</td>';
                                        echo '<td scope="col" class="border-gray-300">'. $row['nama_fasilitas'].'</td>';
                                        echo '<td scope="col" class="border-gray-300">'. $row['lokasi'].'</td>';
                                        echo '<td scope="col" class="border-gray-300">'. $row['nama_pelapor'].'</td>';
                                        echo '<td scope="col" class="border-gray-300">'. $row['prioritas'].'</td>';
                                        echo '<td scope="col"class="border-gray-300">'. $row['status'].'</td>';
                                        echo '<td scope="col"class="border-gray-300">'. $row['tanggal_laporan'].'</td>';
                                        echo '</tr>';
                                        }
                                        } else{
                                    echo '<td colspan="7">Belum ada data ditampilkan. Lengkapi fitur Read menggunakan PHP dan MySQL.</td>';
                                }
        
                            
                        ?>
                    
                </tbody>
            </table>
        </section>
    </main>
    
    <footer class="bg-gray-800 p-4 border-t-3  border-gray-800  mt-4 text-white">
        <p class="text-center">&copy; 2024 Sistem Pelaporan Kerusakan Fasilitas Sekolah.</p>
    </footer>
</body>
</html>


