<?php
session_start();
if (!isset($_SESSION['kasir'])) {
    header("Location: index.php");
    exit();
}
$nama_kasir = $_SESSION['nama_kasir'] ?? 'Kasir';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CheesyWay - Dashboard Kasir</title>
    <style>
        body { background-color: #3b2e22; font-family: 'Segoe UI', sans-serif; margin: 20px; }
        .container { background: #e3d5d5; border-radius: 20px; padding: 25px; box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .header h2 { color: #4a3e3e; margin: 0; }
        .user-info { display: flex; align-items: center; gap: 10px; }
        .badge-online { background: #2ecc71; color: white; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .logout-btn { background: #e74c3c; color: white; border: none; padding: 6px 12px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: bold; }
        .main-layout { display: flex; gap: 20px; }
        .order-list-box { flex: 1; background: #cbb9ba; padding: 15px; border-radius: 15px; min-height: 350px; }
        .order-detail-box { flex: 1; background: #5d4841; color: white; padding: 20px; border-radius: 15px; min-height: 350px; display: flex; flex-direction: column; justify-content: space-between; }
        .order-card { background: #6e5449; color: white; padding: 12px; border-radius: 10px; margin-bottom: 10px; cursor: pointer; }
        .btn-confirm { background: #2ecc71; color: white; border: none; padding: 12px; border-radius: 10px; width: 100%; font-weight: bold; font-size: 15px; cursor: pointer; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Cheesyway - Dashboard Kasir</h2>
        <div class="user-info">
            <span class="badge-online">online</span>
            <strong style="color: #4a3e3e;"><?= htmlspecialchars($nama_kasir) ?></strong>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="main-layout">
        <div class="order-list-box">
            <h4 style="margin-top: 0; color: #4a3e3e;">Daftar Pesanan Masuk</h4>
            <div id="orders-container">Belum ada pesanan.</div>
        </div>

        <div class="order-detail-box" id="detail-container">
            <p style="text-align: center; margin-top: auto; margin-bottom: auto; color: #d1c7c2;">Pilih salah satu pesanan di sebelah kiri untuk melihat detail transaksi.</p>
        </div>
    </div>
</div>

<script>
let activeOrderId = null;

function checkNewOrders() {
    fetch('get_orders.php')
    .then(res => res.json())
    .then(data => {
        let container = document.getElementById('orders-container');
        if (data.length === 0) {
            container.innerHTML = '<small style="color: #666;">Belum ada pesanan.</small>';
            document.getElementById('detail-container').innerHTML = '<p style="text-align: center; margin-top: auto; margin-bottom: auto; color: #d1c7c2;">Pilih salah satu pesanan di sebelah kiri untuk melihat detail transaksi.</p>';
            return;
        }

        container.innerHTML = '';
        data.forEach(order => {
            container.innerHTML += `
                <div class="order-card" onclick="loadDetail(${order.id})">
                    <strong>#ORD-${String(order.id).padStart(3, '0')}</strong> (${order.status})<br>
                    <span>Meja ${order.no_meja} - ${order.nama_pelanggan.toUpperCase()}</span><br>
                    <small>Total: Rp ${Number(order.total_harga).toLocaleString('id-ID')}</small>
                </div>
            `;
        });
    });
}

function loadDetail(id) {
    activeOrderId = id;
    fetch('get_detail.php?id=' + id)
    .then(res => res.text())
    .then(html => {
        document.getElementById('detail-container').innerHTML = html;
    });
}

function konfirmasiTransaksi(id) {
    fetch('konfirmasi.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + id
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert('Transaksi Berhasil Dikonfirmasi!');
            checkNewOrders();
        } else {
            alert('Gagal mengonfirmasi.');
        }
    });
}

setInterval(checkNewOrders, 3000);
checkNewOrders();
</script>
</body>
</html>