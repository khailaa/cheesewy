<?php
session_start();
include '../koneksi.php';

$data = json_decode(file_get_contents('php://input'), true);
$nama = $_SESSION['nama'];
$meja = $_SESSION['no_meja'];
$metode = $data['metode'];
$cart = $data['cart'];

$total = 0;
foreach($cart as $item) { $total += $item['harga'] * $item['qty']; }

$stmt = $conn->prepare("INSERT INTO pesanan (nama_pelanggan, no_meja, metode_pembayaran, total_harga) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $nama, $meja, $metode, $total);
$stmt->execute();
$pesanan_id = $conn->insert_id;

foreach($cart as $id => $item) {
    $stmt_detail = $conn->prepare("INSERT INTO detail_pesanan (pesanan_id, menu_id, jumlah) VALUES (?, ?, ?)");
    $stmt_detail->bind_param("iii", $pesanan_id, $id, $item['qty']);
    $stmt_detail->execute();
}

echo json_encode(['success' => true]);
?>