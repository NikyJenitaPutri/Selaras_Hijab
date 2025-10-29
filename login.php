<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Username atau password salah.';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Selaras Hijab</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <h1>Selaras Hijab</h1>
                <p>Koleksi Hijab Terbaru & Berkualitas</p>
            </div>
    </header>

    <main class="login-page">
        <div class="login-container" role="main" aria-labelledby="login-title">
            <div class="brand-logo" aria-hidden="true">SH</div>
                <h2 id="login-title">Masuk ke Dashboard</h2>
                    <?php if($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
                    <form method="POST" aria-describedby="login-help">
                        <label class="sr-only" for="username">Username</label>
                        <input id="username" type="text" name="username" placeholder="Username" required autocomplete="username">

                        <label class="sr-only" for="password">Password</label>
                        <input id="password" type="password" name="password" placeholder="Password" required autocomplete="current-password">

                        <div class="login-actions">
                            <button type="submit" class="btn btn-primary">Masuk</button>
                            <a href="index.php" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 <strong>Selaras Hijab</strong>. All rights reserved.</p>
    </footer>
</body>
</html>
