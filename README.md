
# Selaras Hijab

Selaras Hijab – Toko Hijab Online

Aplikasi web sederhana untuk menampilkan katalog produk hijab dan memungkinkan admin mengelola produk melalui dashboard. Pengguna dapat melihat detail produk dan langsung menghubungi admin via WhatsApp untuk pemesanan.

## Fitur yang tersedia
### Pengunjung :
    
    1. Tampilan utama dengan hero section dan navigasi responsif.
    
    2. Daftar produk terbaru dengan gambar, nama produk, harga dan tombol detail produk.
    
    3. Halaman detail produk berisi ID, nama produk, harga, deskripsi dan lengkap dengan tombol pesan via WhatsApp.
    
    4. Cara order langkah demi langkah.
    
    5. Testimoni pelanggan & kontak (email, telepon, WhatsApp).
    
    6. Tips memilih hijab.
    
### Admin :
    1. Login admin sederhana (username: admin, password: admin123).
    
    2. Dashboard dengan tabel produk (desktop) dan tampilan kartu (mobile).
    
    3. Pencarian produk berdasarkan ID atau nama.
    
    4. Pagination otomatis.
    
    5. Fitur tambah produk, lihat detail produk, edit produk dan hapus produk
        # Tambah
            Halaman ini adalah formulir admin yang digunakan untuk menambahkan produk hijab baru ke dalam database toko online Selaras Hijab. Hanya pengguna yang sudah login (admin) yang dapat mengakses halaman ini.

            Formulir ini memungkinkan admin untuk:
            - Mengisi nama produk

            - Menentukan harga (dalam Rupiah)

            - Menambahkan deskripsi

            - Mengunggah foto produk

            - Setelah data dikirim: Sistem memvalidasi input (nama dan harga wajib diisi, harga harus angka).

            - Jika ada foto, sistem memeriksa:
                => Jenis file (hanya gambar: JPG, PNG, WEBP, GIF),
                => Ukuran file (maksimal 3 MB),
                => Keamanan nama file (karakter khusus dihilangkan).

            - Jika semua valid, data disimpan ke database, lalu admin dialihkan kembali ke dashboard dengan pesan sukses.

            - Jika terjadi kesalahan (misal: input tidak lengkap atau upload gagal), pesan error ditampilkan tanpa menghilangkan data yang sudah diketik (form tetap terisi).

            - Tampilan halaman ini responsif dan menyertakan:
                => Preview gambar saat memilih file,
                => Tombol hapus file,
                => Navigasi kembali ke dashboard.

        # Lihat Detail
            Halaman ini menampilkan informasi lengkap satu produk hijab. Halaman ini dirancang untuk fokus pada penyajian informasi produk dan kemudahan pemesanan

            - Tampilan Informasi Produk:
                => Nama produk dan ID unik
                => Harga yang diformat rapi dalam format 
                => Foto produk — jika tersedia; jika tidak, muncul teks "Tidak ada gambar".
                => Deskripsi produk — mendukung format paragraf.
                => Tanggal terbit/upload produk

            - Pemesanan via WhatsApp:
                => Tombol "Pesan Sekarang" mengarah ke chat WhatsApp 
                => Pesan otomatis yang dikirim:
                "Halo, saya tertarik dengan produk: [Nama Produk] (ID: [ID]). Apakah masih tersedia? Saya ingin memesan."
 
            - Navigasi Pengguna:
                => Breadcrumb di atas: memandu pengguna dari Beranda → Produk → Nama Produk.
                => Tombol "Kembali"

        # Edit
            Halaman ini adalah formulir admin yang digunakan untuk mengubah data produk hijab yang sudah ada di database. Hanya pengguna yang telah login (admin) yang dapat mengakses halaman ini.

            - Pre-populasi Data
              Formulir secara otomatis diisi dengan data produk saat ini:
                => Nama produk
                => Harga
                => Deskripsi
                => Nama file foto (dan pratinjau gambar jika tersedia)


        # Hapus
            Halaman ini berfungsi menghapus produk tertentu dari database secara permanen, termasuk menghapus file foto produk dari server jika tersedia. Ada pesan konfirmasi apakah yakin untuk menghapus produk. Jika berhasil maka akan menampilkan pesan sukses dan mengarahkan kembali ke dashboard. 

## Kebutuhan sistem 
    - Web server (Laragon)
    - PHP ≥ 8.0
    - MySQL
    - Browser modern (Chrome, Firefox, Safari, Edge)

## Cara instalasi dan konfigurasi
    1. Clone atau unduh repositori ini ke direktori web server Anda
    2. Buat database di MySQL:
        CREATE DATABASE selaras_hijab;
        USE selaras_hijab;
    3. Import struktur tabel : 
        CREATE TABLE produk (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            Nama VARCHAR(100) NOT NULL,
            Harga DECIMAL(15,2) NOT NULL,
            Deskripsi_Produk TEXT,
            Foto_Produk VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    4. Buat file konfigurasi config.php di root folder
    5. Buat folder uploads
    6. Akses aplikasi via browser: 
        http://localhost/selaras-hijab
    
## Struktur folder 
    /selaras-hijab/
    ├── config.php 
    ├── index.php   
    ├── login.php   
    ├── dashboard.php 
    ├── tambah_produk.php 
    ├── edit_produk.php 
    ├── hapus_produk.php 
    ├── detail_produk.php 
    ├── logout.php              
    ├── style.css       
    ├── script.js     
    ├── uploads/ 
    ├── img/                    
        # Gambar statis (hero, testimoni, dll)
    └── README.md


## Contoh environment config.
''' $host = 'localhost';
$db   = 'selaras_hijab';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
'''

## Screenshot aplikasi minimal satu
![alt text](/Tampilan_Web/Beranda.png)

![alt text](/Tampilan_Web/Cara%20Order.png)

![alt text](/Tampilan_Web/Produk.png)

![alt text](/Tampilan_Web/Stastika%20dan%20Testimoni.png)

![alt text](/Tampilan_Web/Kontak,%20Tips,%20Footer.png)

![alt text](/Tampilan_Web/Login.png)

![alt text](/Tampilan_Web/Pencarian.png)

![alt text](/Tampilan_Web/Dashboard1.png)

![alt text](/Tampilan_Web/Dashboard2.png)

![alt text](/Tampilan_Web/Tambah%20Produk.png)

![alt text](/Tampilan_Web/Detail%20Produk.png)

![alt text](/Tampilan_Web/Edit%20Produk.png)

![alt text](/Tampilan_Web/Hapus%20Produk.png)
