<?php
include '../koneksi.php';

$id = $_GET['id'] ?? 0;
$query = $conn->query("SELECT * FROM pesanan WHERE id = '$id'");
$pesanan = $query->fetch_assoc();

if (!$pesanan) {
    echo "Pesanan tidak ditemukan.";
    exit();
}

$details = $conn->query("SELECT d.*, m.nama_menu, m.harga FROM detail_pesanan d JOIN menu m ON d.menu_id = m.id WHERE d.pesanan_id = '$id'");
?>

<div style="display: flex; justify-content: space-between; align-items: center;">
    <h3 style="margin:0;">Detail Transaksi (#ORD-<?= sprintf('%03d', $pesanan['id']) ?>)</h3>
    <span style="background: #e3d5d5; color: #333; padding: 2px 8px; border-radius: 5px; font-size: 12px; font-weight: bold;">DINE-IN</span>
</div>
<hr style="border-color: rgba(255,255,255,0.2); margin: 15px 0;">

<div style="min-height: 150px;">
    <?php while($row = $details->fetch_assoc()): ?>
        <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
            <span><?= $row['nama_menu'] ?> x<?= $row['jumlah'] ?></span>
            <span>Rp<?= number_format($row['harga'] * $row['jumlah'], 0, ',', '.') ?></span>
        </div>
    <?php endwhile; ?>
</div>

<hr style="border-color: rgba(255,255,255,0.2); margin: 15px 0;">

<div style="font-size: 12px; color: #ddd;">
    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
        <span>Metode Pembayaran</span>
        <strong><?= strtoupper($pesanan['metode_pembayaran']) ?></strong>
    </div>
    <div style="display: flex; justify-content: space-between; font-size: 18px; color: #fff; font-weight: bold; margin-top: 10px;">
        <span>Total Bayar</span>
        <span>Rp<?= number_format($pesanan['total_harga'], 0, ',', '.') ?></span>
    </div>
</div>

<button onclick="konfirmasiTransaksi(<?= $pesanan['id'] ?>)" 
        style="width: 100%; margin-top: 15px; padding: 10px; background: #22b14c; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
    KONFIRMASI TRANSAKSI
</button>