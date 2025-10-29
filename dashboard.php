<?php
session_start();
require 'config.php';
if (!isset($_SESSION['username'])) header('Location: login.php');

// Pagination
$perPage = 5;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

// Search berdasarkan ID atau Nama produk
$keyword = trim($_GET['search'] ?? '');
if ($keyword !== '') {
    $stmt = $pdo->prepare("
        SELECT * FROM produk 
        WHERE ID LIKE :keyword OR Nama LIKE :keyword 
        ORDER BY ID DESC 
        LIMIT :offset, :perPage
    ");
    $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
} else {
    $stmt = $pdo->prepare("SELECT * FROM produk ORDER BY ID DESC LIMIT :offset, :perPage");
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
$stmt->execute();
$produk = $stmt->fetchAll();

// Total data untuk pagination
$totalStmt = $pdo->prepare($keyword !== '' ? 
    "SELECT COUNT(*) FROM produk WHERE ID LIKE :keyword OR Nama LIKE :keyword" : 
    "SELECT COUNT(*) FROM produk"
);
if ($keyword !== '') $totalStmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
$totalStmt->execute();
$totalData = $totalStmt->fetchColumn();
$totalPage = ceil($totalData / $perPage);

// Pesan sukses/error
$msg = $_GET['msg'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard Admin</title>
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
        <header class="admin-header">
            <div>
                <h2>Dashboard Admin</h2>
                <p>Halo, <?= htmlspecialchars($_SESSION['username']) ?></p>
            </div>
            <div class="admin-actions">
                <a href="tambah_produk.php" class="btn btn-primary">Tambah Produk</a>
                <a href="logout.php" class="btn btn-secondary">Keluar</a>
                <a href="index.php" class="btn btn-secondary">Kembali</a>
            </div>
        </header>

        <?php if($msg): ?>
            <div class="alert"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Cari produk berdasarkan ID atau nama..." value="<?= htmlspecialchars($keyword) ?>">
            <button type="submit">Cari</button>
        </form>

        <section class="admin-table">
            <!-- Tabel: hanya tampil di desktop/tablet -->
            <table class="table-admin">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Foto</th>
                        <th>Tanggal Upload</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if($produk && count($produk) > 0): ?>
                    <?php foreach($produk as $p): ?>
                    <tr>
                        <td><?= $p['ID'] ?></td>
                        <td><?= htmlspecialchars($p['Nama']) ?></td>
                        <td>Rp <?= number_format($p['Harga'],0,',','.') ?></td>
                        <td>
                            <?php if(!empty($p['Foto_Produk']) && file_exists(__DIR__ . '/uploads/' . $p['Foto_Produk'])): ?>
                                <img src="uploads/<?= htmlspecialchars($p['Foto_Produk']) ?>" alt="<?= htmlspecialchars($p['Nama']) ?>" class="thumb">
                            <?php else: ?>
                                <div class="thumb-placeholder">Tidak Ada</div>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($p['created_at'] ?? '-') ?></td>
                        <td>
                            <a href="detail_produk.php?id=<?= $p['ID'] ?>" class="btn btn-secondary" title="Detail">Detail</a>
                            <a href="edit_produk.php?id=<?= $p['ID'] ?>" class="btn btn-secondary" title="Edit">Edit</a>
                            <a href="hapus_produk.php?id=<?= $p['ID'] ?>" class="btn btn-secondary" onclick="return confirm('Yakin ingin hapus produk ini?')" title="Hapus">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="no-data">Tidak ada produk ditemukan</td></tr>
                <?php endif; ?>
                </tbody>
            </table>

            <!-- Card Mobile: hanya tampil di HP (< 768px) -->
            <?php if($produk && count($produk) > 0): ?>
                <?php foreach($produk as $p): ?>
                <div class="product-card-mobile">
                    <div class="row">
                        <span class="col-label">ID:</span>
                        <span class="col-value"><?= $p['ID'] ?></span>
                    </div>
                    <div class="row">
                        <span class="col-label">Nama:</span>
                        <span class="col-value"><?= htmlspecialchars($p['Nama']) ?></span>
                    </div>
                    <div class="row">
                        <span class="col-label">Harga:</span>
                        <span class="col-value">Rp <?= number_format($p['Harga'],0,',','.') ?></span>
                    </div>
                    <div class="row">
                        <span class="col-label">Foto:</span>
                        <span class="col-value">
                            <?php if(!empty($p['Foto_Produk']) && file_exists(__DIR__ . '/uploads/' . $p['Foto_Produk'])): ?>
                                <img src="uploads/<?= htmlspecialchars($p['Foto_Produk']) ?>" alt="<?= htmlspecialchars($p['Nama']) ?>" class="thumb-small">
                            <?php else: ?>
                                <span>-</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="row">
                        <span class="col-label">Tanggal:</span>
                        <span class="col-value"><?= htmlspecialchars($p['created_at'] ?? '-') ?></span>
                    </div>
                    <div class="actions">
                        <a href="detail_produk.php?id=<?= $p['ID'] ?>" class="btn btn-secondary">Detail</a>
                        <a href="edit_produk.php?id=<?= $p['ID'] ?>" class="btn btn-secondary">Edit</a>
                        <a href="hapus_produk.php?id=<?= $p['ID'] ?>" class="btn btn-secondary" onclick="return confirm('Yakin ingin hapus produk ini?')">Hapus</a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="pagination">
                <?php for($i = 1; $i <= $totalPage; $i++): ?>
                    <a href="?page=<?= $i ?><?= $keyword !== '' ? '&search=' . urlencode($keyword) : '' ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 <strong>Selaras Hijab</strong>. All rights reserved.</p>
    </footer>
</body>
</html>