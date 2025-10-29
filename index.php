<?php
session_start();
require 'config.php';

$user = $_SESSION['username'] ?? null;

$stmt = $pdo->query("SELECT * FROM produk ORDER BY created_at DESC");
$produk = $stmt->fetchAll();

function formatHarga(float $harga): string {
    return "Rp " . number_format($harga, 2, ',', '.');
}

$hero_bg = file_exists(__DIR__ . '/img/beranda.jpg') ? 'img/beranda.jpg' : 'img/Background.jpg';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selaras Hijab</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <h1>Selaras Hijab</h1>
                <p>Koleksi Hijab Terbaru & Berkualitas</p>
            </div>
            <ul class="nav-links">
                <li><a href="#beranda">Beranda</a></li>
                <li><a href="#cara-order">Cara Order</a></li>
                <li><a href="#produk-terbaru">Produk</a></li>
                <li><a href="#testimoni">Testimoni</a></li>
                <li><a href="#kontak">Kontak</a></li>
            </ul>
            <div class="button-area"> 
                <?php if ($user): ?> 
                    <a href="logout.php" class="btn btn-secondaryy">Keluar</a> 
                    <a href="dashboard.php" class="btn btn-secondary">Dashboard</a> 
                <?php else: ?> 
                    <a href="login.php" class="btn btn-secondary">Masuk</a> 
                <?php endif; ?> 
            </div>
        </nav>
    </header>

    <main>
        <section id="beranda" class="hero" style="background-image: url('<?= htmlspecialchars($hero_bg) ?>');">
            <div class="hero-content">
                <h2>Temukan Hijab Cantik & Modis</h2>
                <p>Koleksi hijab terbaru, nyaman dan stylish untuk setiap hari!</p>

                <div class="hero-cta">
                    <?php $wa_msg = rawurlencode('Halo, saya ingin memesan hijab. Mohon bantuannya.'); ?>
                    <a class="whatsapp-order-large" href="https://wa.me/62895412818444?text=<?php echo $wa_msg; ?>" target="_blank" rel="noopener noreferrer">
                        <span>Pesan Sekarang</span>
                    </a>
                </div>
            </div>
        </section>
        
        <!-- ===== CARA ORDER ===== -->
        <section id="cara-order" class="cara-order">
            <h2>Cara Order</h2>
            <div class="order-container">
                <div class="order-card">
                    <i class="fa-solid fa-store"></i>
                    <h3>1. Pilih Produk</h3>
                    <p>Pilih hijab favoritmu dari katalog yang tersedia di halaman produk.</p>
                </div>

                <div class="order-card">
                    <i class="fa-solid fa-list"></i>
                    <h3>2. Catat Detail Produk</h3>
                    <p>Tulis ID Produk hijab yang kamu inginkan.</p>
                </div>

                <div class="order-card">
                    <i class="fa-brands fa-whatsapp"></i>
                    <h3>3. Hubungi Admin</h3>
                    <p>Klik tombol <b>Pesan Sekarang</b> untuk menghubungi admin melalui WhatsApp.</p>
                </div>

                <div class="order-card">
                    <i class="fa-solid fa-credit-card"></i>
                    <h3>4. Lakukan Pembayaran</h3>
                    <p>Ikuti instruksi pembayaran, lalu konfirmasi setelah transfer berhasil.</p>
                </div>

                <div class="order-card">
                    <i class="fa-solid fa-truck-fast"></i>
                    <h3>5. Pesanan Dikirim</h3>
                    <p>Tunggu hijab cantikmu dikirim ke alamat tujuan</p>
                </div>
            </div>
        </section>

        <section id="produk-terbaru" class="produk-section">
            <h2>Produk</h2>
            <div class="produk-list">
                <?php if($produk && count($produk) > 0): ?>
                    <?php foreach($produk as $p): ?>
                    <article class="card product-card">
                        <?php
                            $imgSrc = 'img/no-image.png';
                            if(!empty($p['Foto_Produk']) && file_exists(__DIR__ . '/uploads/' . $p['Foto_Produk'])){
                                $imgSrc = 'uploads/' . htmlspecialchars($p['Foto_Produk']);
                            }
                        ?>
                        <div class="card-media">
                            <img src="<?= $imgSrc ?>" loading="lazy" alt="<?= htmlspecialchars($p['Nama']) ?>">
                        </div>
                        <div class="card-body">
                            <h3><?= htmlspecialchars($p['Nama']) ?></h3>
                            <div class="card-meta">
                                <span class="price-badge"><?= formatHarga($p['Harga']) ?></span>
                                <a href="detail_produk.php?id=<?= $p['ID'] ?>" class="btn btn-secondary">Lihat Detail</a>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Produk tidak ditemukan.</p>
                <?php endif; ?>
            </div>
        </section>

        <section class="stats">
            <h2>Toko Hijab dalam Angka</h2>
            <div class="stats-container">
                <div class="stat-box"><h3>10,000+</h3><p>Hijab Terjual</p></div>
                <div class="stat-box"><h3>99%</h3><p>Tingkat Kepuasan</p></div>
                <div class="stat-box"><h3>4.8/5</h3><p>Rating Produk</p></div>
            </div>
        </section>

        <section id="testimoni">
            <h2>Testimoni Pelanggan</h2>
            <div class="testimoni-list">
                <blockquote>
                    <img src="img/pelanggan1.jpg" alt="Foto Aisyah">
                    <p>"Hijabnya cantik dan bahannya nyaman. Pengiriman cepat dan packaging rapi."</p>
                    <cite><strong>Aisyah Rahma</strong> - Mahasiswa, Jakarta</cite>
                </blockquote>

                <blockquote>
                    <img src="img/pelanggan2.jpg" alt="Foto Siti">
                    <p>"Suka banget dengan motif dan warna hijabnya. Harga terjangkau tapi kualitas premium!"</p>
                    <cite><strong>Siti Nurhaliza</strong> - Freelancer, Surabaya</cite>
                </blockquote>
            </div>
        </section>

        <section id="kontak">
            <h2>Kontak Kami</h2>
            <div class="contact-cards">
                <div class="contact-card">
                    <div class="card-body">
                        <div class="card-title">Email</div>
                            <a href="mailto:jenitaputri25062006@gmail.com" class="contact-link" aria-label="Kirim email ke jenitaputri25062006@gmail.com">jenitaputri25062006@gmail.com</a>
                    </div>
                </div>

                <div class="contact-card">
                    <div class="card-body">
                        <div class="card-title">Telepon</div>
                            <a href="tel:+62895412818444" class="contact-link" aria-label="Telepon ke +62 895 4128 18444">+62 895 4128 18444</a>
                    </div>
                </div>

                <div class="contact-card">
                    <div class="card-body">
                        <div class="card-title">WhatsApp</div>
                            <a href="https://wa.me/62895412818444" target="_blank" rel="noopener noreferrer" class="contact-link whatsapp-btn" aria-label="Chat atau pesan via WhatsApp ke +62 895 4128 18444">Chat via WhatsApp</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <aside>
        <section id="tips">
            <h3>Tips Memilih Hijab</h3>
            <ul>
                <li>Pilih bahan yang nyaman</li>
                <li>Sesuaikan warna dengan outfit</li>
                <li>Periksa ukuran dan panjang hijab</li>
                <li>Rawat hijab agar tahan lama</li>
            </ul>
        </section>
    </aside>

    <footer>
        <p>&copy; 2025 <strong>Selaras Hijab</strong>. All rights reserved.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
