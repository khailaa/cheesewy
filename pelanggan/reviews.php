<?php
session_start();
/**@var mysqli $conn */
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_SESSION['nama'] ?? 'Anonim';
    $rating = $_POST['rating'];
    $komentar = $_POST['komentar'];
    $stmt = $conn->prepare("INSERT INTO ulasan (nama_pelanggan, rating, komentar) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $nama, $rating, $komentar);
    $stmt->execute();
}

$reviews = $conn->query("SELECT * FROM ulasan ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CheesyWay - Reviews</title>
    <style>
        body { background-color: #9b8969; font-family: sans-serif; margin: 20px; }
        .container { background: #f8d593; border-radius: 20px; padding: 20px; display: flex; gap: 20px; }
        .sidebar { width: 200px; background: #806b40; padding: 15px; border-radius: 15px; }
        .main { flex: 1; }
        .review-card { background: white; padding: 10px; margin-bottom: 10px; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h3 style="color: #f5f3f3">CHEESYWAY</h3>
            <b><a href="menu.php" style="color: #f5f3f3;">MENU</a></b>
            <p><b style="color: #f5f3f3"> REVIEWS</b></p>
        </div>
        <div class="main">
            <h3>Beri Ulasan Kamu</h3>
            <form method="POST">
                <select name="rating">
                    <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                    <option value="4">⭐⭐⭐⭐ (4/5)</option>
                    <option value="3">⭐⭐⭐ (3/5)</option>
                </select><br><br>
                <textarea name="komentar" placeholder="Tulis tanggapan atau saran kamu di sini" style="width: 100%; height: 80px;"></textarea><br><br>
                <button type="submit" style="padding: 10px 20px;">Kirim Ulasan</button>
            </form>
            <hr>
            <h3>Ulasan Pelanggan</h3>
            <?php while($r = $reviews->fetch_assoc()): ?>
                <div class="review-card">
                    <strong><?= htmlspecialchars($r['nama_pelanggan']) ?></strong> - <?= str_repeat('⭐', $r['rating']) ?>
                    <p><?= htmlspecialchars($r['komentar']) ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>