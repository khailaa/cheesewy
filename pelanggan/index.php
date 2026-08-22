<?php
session_start();

// Tangkap nomor meja dari link URL (contoh: ?meja=05)
$no_meja = isset($_GET['meja']) ? $_GET['meja'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['nama'] = $_POST['nama'];
    $_SESSION['no_meja'] = $_POST['no_meja'];
    header("Location: menu.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CheesyWay - Login Pelanggan</title>
    <style>
        body { background-color: #4a7c7d; font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: #e3d5d5; padding: 40px; border-radius: 20px; display: flex; width: 600px; gap: 20px; }
        .brand { flex: 1; border-right: 2px solid #ccc; padding-right: 20px; }
        .form-box { flex: 1; text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: none; border-radius: 15px; box-sizing: border-box; }
        input[readonly] { background-color: #d1c4c4; color: #555; cursor: not-allowed; }
        button { width: 100%; padding: 10px; background: #a89a9b; border: none; border-radius: 15px; color: white; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand">
            <h2>CHEESYWAY</h2>
            <p><b>The Only Way</b></p>
        </div>
        <div class="form-box">
            <h3>LOGIN</h3>
            <form method="POST">
                <input type="text" name="nama" placeholder="NAMA :" required>
                
                <!-- Input nomor meja akan terisi otomatis dari URL jika ada -->
                <input type="text" name="no_meja" placeholder="NO MEJA :" 
                       value="<?= htmlspecialchars($no_meja) ?>" 
                       <?= !empty($no_meja) ? 'readonly' : '' ?> required>
                
                <button type="submit">MASUK & PESAN</button>
            </form>
        </div>
    </div>
</body>
</html>