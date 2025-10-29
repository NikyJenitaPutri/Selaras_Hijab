<?php
require 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Validasi ID sebagai integer
$id = $_GET['id'] ?? null;
if (!$id || !ctype_digit($id)) {
    die("Produk tidak ditemukan.");
}
$id = (int)$id;

$stmt = $pdo->prepare("SELECT * FROM produk WHERE ID = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) {
    die("Produk tidak ditemukan.");
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $harga = trim($_POST['harga'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    // Validasi wajib
    if ($nama === '' || $harga === '') {
        $msg = "Nama dan harga wajib diisi!";
    } elseif (!is_numeric($harga) || $harga <= 0) {
        $msg = "Harga harus berupa angka positif!";
    } else {
        $foto_name = $p['Foto_Produk'];

        // Jika ada file diupload
        if (!empty($_FILES['foto']['name']) && is_uploaded_file($_FILES['foto']['tmp_name'])) {
            $original = basename($_FILES['foto']['name']);
            $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($ext, $allowed_ext)) {
                $msg = "Format gambar tidak diizinkan. Gunakan JPG, PNG, atau WebP.";
            } else {
                // Bersihkan nama file
                $safe_name = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($original, PATHINFO_FILENAME));
                $foto_name_new = $safe_name . '_' . time() . '.' . $ext;
                $upload_path = __DIR__ . '/uploads/' . $foto_name_new;

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                    // Hapus file lama jika berbeda dan ada
                    if (!empty($p['Foto_Produk']) && $p['Foto_Produk'] !== $foto_name_new) {
                        $old_path = __DIR__ . '/uploads/' . $p['Foto_Produk'];
                        if (file_exists($old_path)) {
                            unlink($old_path);
                        }
                    }
                    $foto_name = $foto_name_new;
                } else {
                    $msg = "Gagal mengunggah gambar. Pastikan folder uploads/ dapat ditulis.";
                }
            }
        }

        // Jika tidak ada error, lakukan update
        if ($msg === '') {
            try {
                if ($foto_name !== $p['Foto_Produk']) {
                    $stmt = $pdo->prepare("UPDATE produk SET Nama = ?, Harga = ?, Deskripsi_Produk = ?, Foto_Produk = ? WHERE ID = ?");
                    $stmt->execute([$nama, (float)$harga, $deskripsi, $foto_name, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE produk SET Nama = ?, Harga = ?, Deskripsi_Produk = ? WHERE ID = ?");
                    $stmt->execute([$nama, (float)$harga, $deskripsi, $id]);
                }
                header('Location: dashboard.php?msg=' . urlencode('Produk berhasil diperbarui'));
                exit;
            } catch (PDOException $e) {
                $msg = 'Gagal memperbarui produk: ' . htmlspecialchars($e->getMessage());
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
    <title>Edit Produk - Toko Hijab</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <section class="form-card">
            <h2>Edit Produk</h2>
            <?php if ($msg): ?>
                <div class="alert"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="product-form">
                <div class="form-row">
                    <label for="nama">Nama Produk <span class="required">*</span></label>
                    <input id="nama" type="text" name="nama" value="<?= htmlspecialchars($p['Nama'], ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div class="form-row">
                    <label for="harga">Harga (Rp) <span class="required">*</span></label>
                    <input id="harga" type="number" step="1" min="1" name="harga" value="<?= (int)$p['Harga'] ?>" required>
                    <small class="help">Contoh: 125000 (tanpa titik/format khusus)</small>
                </div>

                <div class="form-row">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi"><?= htmlspecialchars($p['Deskripsi_Produk'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="form-row">
                    <label for="foto">Foto Produk</label>
                    <div class="file-control">
                        <input id="foto" class="file-input" type="file" name="foto" accept="image/*">
                        <div class="file-meta">
                            <span class="file-name"><?= htmlspecialchars($p['Foto_Produk'] ?: 'Belum memilih file', ENT_QUOTES, 'UTF-8') ?></span>
                            <button type="button" class="btn btn-secondary file-clear">Hapus</button>
                        </div>
                        <div class="file-error" aria-live="polite" style="display:none;color:#c0392b;margin-top:6px;font-size:.95rem"></div>
                    </div>
                    <div class="preview">
                        <?php if (!empty($p['Foto_Produk']) && file_exists(__DIR__ . '/uploads/' . $p['Foto_Produk'])): ?>
                            <img id="previewImg" class="preview-img" alt="Preview" src="uploads/<?= htmlspecialchars($p['Foto_Produk'], ENT_QUOTES, 'UTF-8') ?>">
                        <?php else: ?>
                            <img id="previewImg" class="preview-img" alt="Preview" src="">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Produk</button>
                    <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </section>
    </main>

    <script src="script.js"></script>
</body>
</html>