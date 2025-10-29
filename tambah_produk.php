<?php
require 'config.php';
session_start();
if (!isset($_SESSION['username'])) header('Location: login.php');

$msg = '';
$namaVal = '';
$hargaVal = '';
$deskripsiVal = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $harga = trim($_POST['harga']);
    $deskripsi = trim($_POST['deskripsi']);

    // simpan nilai agar tidak hilang jika gagal submit
    $namaVal = $nama;
    $hargaVal = $harga;
    $deskripsiVal = $deskripsi;

    if (!$nama || $harga === '' || !is_numeric($harga)) {
        $msg = "Nama dan harga wajib diisi, dan harga harus berupa angka!";
    } else {
        // Upload file aman
        $foto_name = null;
        if (!empty($_FILES['foto']['name']) && is_uploaded_file($_FILES['foto']['tmp_name'])) {
            $original = basename($_FILES['foto']['name']);
            $ext = pathinfo($original, PATHINFO_EXTENSION);
            $safe = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($original, PATHINFO_FILENAME));
            $foto_name = $safe . '_' . time() . ($ext ? '.' . $ext : '');

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES['foto']['tmp_name']);
            finfo_close($finfo);
            $allowed = ['image/jpeg','image/png','image/webp','image/gif'];

            if (!in_array($mime, $allowed)) {
                $foto_name = null;
                $msg = 'File tidak valid, gunakan JPG/PNG/WEBP/GIF';
            } elseif ($_FILES['foto']['size'] > 3 * 1024 * 1024) {
                $foto_name = null;
                $msg = 'Ukuran file terlalu besar, maksimal 3MB';
            } else {
                if (!move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . "/uploads/" . $foto_name)) {
                    $foto_name = null;
                    $msg = 'Gagal mengupload file';
                }
            }
        }

        if (!$msg) {
            try {
                $stmt = $pdo->prepare("INSERT INTO produk (Nama,Harga,Deskripsi_Produk,Foto_Produk) VALUES (?,?,?,?)");
                $stmt->execute([$nama, $harga, $deskripsi, $foto_name]);
                header('Location: dashboard.php?msg=' . urlencode('Produk berhasil ditambahkan'));
                exit;
            } catch (PDOException $e) {
                $msg = 'Gagal menambahkan produk: ' . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Tambah Produk - Toko Hijab</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <nav class="navbar">
        <div class="logo">
            <h1>Selaras Hijab</h1>
            <p>Koleksi Hijab Terbaru & Berkualitas</p>
        </div>
    </nav>
</header>

<main class="container">
<section class="form-card">
    <h2>Tambah Produk</h2>
    <?php if($msg): ?>
        <div class="alert"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form id="addForm" method="POST" enctype="multipart/form-data" class="product-form">
        <div class="form-row">
            <label for="nama">Nama Produk <span class="required">*</span></label>
            <input id="nama" type="text" name="nama" value="<?= htmlspecialchars($namaVal) ?>" required>
        </div>

        <div class="form-row">
            <label for="harga">Harga (Rp) <span class="required">*</span></label>
            <input id="harga" type="number" step="0.01" name="harga" value="<?= htmlspecialchars($hargaVal) ?>" required>
            <small class="help">Contoh: 125000 (tanpa titik/format khusus)</small>
        </div>

        <div class="form-row">
            <label for="deskripsi">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi"><?= htmlspecialchars($deskripsiVal) ?></textarea>
        </div>

        <div class="form-row">
            <label for="foto">Foto Produk</label>
            <div class="file-control">
                <input id="foto" class="file-input" type="file" name="foto" accept="image/*">
                <div class="file-meta">
                    <span class="file-name">Belum memilih file</span>
                    <button type="button" class="btn btn-secondary file-clear">Hapus</button>
                </div>
                <div class="file-error" aria-live="polite" style="display:none;color:#c0392b;margin-top:6px;font-size:.95rem"></div>
            </div>
            <div class="preview">
                <img id="previewImg" class="preview-img" alt="Preview" src="">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Produk</button>
            <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</section>
</main>

<footer>
<p>&copy; 2025 <strong>Selaras Hijab</strong>. All rights reserved.</p>
</footer>

<script src="script.js"></script>
</body>
</html>
