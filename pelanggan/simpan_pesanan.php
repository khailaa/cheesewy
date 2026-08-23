<?php
session_start();

// 1. Panggil koneksi database
require_once '../koneksi.php';

/** @var mysqli $conn */

// 2. Tangkap data JSON yang dikirim frontend
$data = json_decode(file_get_contents('php://input'), true);

$nama   = isset($_SESSION['nama']) ? $_SESSION['nama'] : '';
$meja   = isset($_SESSION['no_meja']) ? $_SESSION['no_meja'] : '';
$metode = isset($data['metode']) ? $data['metode'] : 'Cash';
$cart   = isset($data['cart']) ? $data['cart'] : [];

// Cek jika keranjang kosong
if (empty($cart)) {
    echo json_encode(['status' => 'error', 'message' => 'Keranjang kosong']);
    exit();
}

// 3. Hitung total harga
$total = 0;
foreach ($cart as $item) {
    $total += $item['harga'] * $item['qty'];
}

// 4. Simpan ke tabel pesanan utama
$stmt = $conn->prepare("INSERT INTO pesanan (nama_pelanggan, no_meja, metode_pembayaran, total_harga) VALUES (?, ?, ?, ?)");
// "sssd" = string, string, string, double/decimal
$stmt->bind_param("sssd", $nama, $meja, $metode, $total);
$stmt->execute();

$pesanan_id = $conn->insert_id; // Ambil ID pesanan baru

// 5. Simpan detail pesanan ke tabel detail_pesanan
$stmt_detail = $conn->prepare("INSERT INTO detail_pesanan (pesanan_id, menu_id, jumlah) VALUES (?, ?, ?)");

foreach ($cart as $id => $item) {
    $qty = $item['qty'];
    // "iii" = integer, integer, integer
    $stmt_detail->bind_param("iii", $pesanan_id, $id, $qty);
    $stmt_detail->execute(); // Jalankan query insert detail
}

// 6. Beri respon ke JavaScript
echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil dikirim ke kasir!']);
?>