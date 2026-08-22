<?php
session_start();
include '../koneksi.php';

if (isset($_SESSION['kasir'])) {
    header("Location: dashboard_kasir.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);

    $query = "SELECT * FROM kasir WHERE username='$username' AND nama_lengkap='$nama_lengkap'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            $_SESSION['kasir'] = $user['username'];
            $_SESSION['nama_kasir'] = $user['nama_lengkap'];
            header("Location: dashboard_kasir.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Data kasir tidak cocok!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CheesyWay - Login Kasir</title>
    <style>
        body { background-color: #4a7c7d; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: #5d4841; padding: 40px 35px; border-radius: 25px; width: 320px; box-shadow: 0 8px 20px rgba(0,0,0,0.2); text-align: center; }
        .login-card h2 { color: #ffffff; margin-bottom: 25px; font-size: 24px; letter-spacing: 2px; border-bottom: 2px solid #ffffff; display: inline-block; padding-bottom: 5px; }
        .input-group { margin-bottom: 18px; text-align: left; }
        .input-group label { display: block; color: #ffffff; font-weight: bold; font-size: 13px; margin-bottom: 5px; }
        .input-group input { width: 100%; padding: 10px 15px; border-radius: 20px; border: none; background: #a39287; color: #ffffff; font-size: 14px; box-sizing: border-box; outline: none; }
        .btn-login { width: 100%; padding: 10px; border-radius: 20px; border: none; background: #a39287; color: #ffffff; font-weight: bold; font-size: 16px; cursor: pointer; margin-top: 15px; }
        .btn-login:hover { background: #8c7b70; }
        .error-msg { color: #ff6b6b; font-size: 13px; margin-bottom: 15px; }
    </style>
</head>
<body>
<div class="login-card">
    <h2>LOGIN</h2>
    <?php if ($error): ?><div class="error-msg"><?= $error ?></div><?php endif; ?>
    <form method="POST">
        <div class="input-group">
            <label>Username :</label>
            <input type="text" name="username" required autocomplete="off">
        </div>
        <div class="input-group">
            <label>Password :</label>
            <input type="password" name="password" required>
        </div>
        <div class="input-group">
            <label>Nama lengkap :</label>
            <input type="text" name="nama_lengkap" required autocomplete="off">
        </div>
        <button type="submit" class="btn-login">Login</button>
    </form>
</div>
</body>
</html>