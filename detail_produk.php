<?php
session_start();
require 'config.php';
if (!isset($_GET['id'])) die('Produk tidak ditemukan.');
$id = (int) $_GET['id'];

$stmt = $pdo->prepare('SELECT * FROM produk WHERE ID = ?');
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) die('Produk tidak ditemukan.');

function formatHarga(float $harga): string {
    return "Rp " . number_format($harga, 0, ',', '.');
}

$wa_text = rawurlencode("Halo, saya tertarik dengan produk: " . $p['Nama'] . " (ID: " . $p['ID'] . "). Apakah masih tersedia? Saya ingin memesan.");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= htmlspecialchars($p['Nama']) ?> — Toko Hijab</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <h1>Selaras Hijab</h1>
            <p>Koleksi Hijab Terbaru & Berkualitas</p>
        </div>
    </header>

    <main class="container product-detail-page">
        <nav class="breadcrumb"><a href="index.php">Beranda</a> › <a href="index.php">Produk</a> › <span><?= htmlspecialchars($p['Nama']) ?></span></nav>
        <section class="product-detail card">
            <div class="gallery">
                <?php if(!empty($p['Foto_Produk']) && file_exists(__DIR__ . '/uploads/' . $p['Foto_Produk'])): ?>
                    <img id="mainImage" src="uploads/<?= htmlspecialchars($p['Foto_Produk']) ?>" alt="<?= htmlspecialchars($p['Nama']) ?>">
                <?php else: ?>
                    <div class="no-image">Tidak ada gambar</div>
                <?php endif; ?>
            </div>

            <div class="info">
                <small>ID Produk: <?= $p['ID'] ?></small>
                <h2 class="product-title"><?= htmlspecialchars($p['Nama']) ?></h2>

                <div class="price-row">
                    <span class="price"><?= formatHarga((float)$p['Harga']) ?></span>
                </div>
                <p class="product-desc"><?= nl2br(htmlspecialchars($p['Deskripsi_Produk'])) ?></p>

                <div class="actions">
                    <a id="waOrder" class="btn btn-primary" href="https://wa.me/62895412818444?text=<?= $wa_text ?>" target="_blank" rel="noopener noreferrer">Pesan Sekarang</a>
                    <a class="btn btn-secondary" href="javascript:history.back()" role="button" aria-label="Kembali">Kembali</a>
                </div>

                <div class="meta">
                    <small>Terbit: <?= htmlspecialchars($p['created_at'] ?? '') ?></small>
                </div>
            </div>

        </section>
    </main>

    <script src="script.js"></script>
    
</body>
</html>
