<?php
session_start();
include '../koneksi.php';
if (!isset($_SESSION['nama'])) { header("Location: index.php"); exit(); }

$makanan = $conn->query("SELECT * FROM menu WHERE kategori='Makanan'");
$minuman = $conn->query("SELECT * FROM menu WHERE kategori='Minuman'");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CheesyWay - Menu Pelanggan</title>
    <style>
        body { background-color: #4a7c7d; font-family: 'Segoe UI', sans-serif; margin: 20px; }
        .container { background: #e3d5d5; border-radius: 20px; padding: 25px; display: flex; gap: 20px; box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
        .sidebar { width: 180px; background: #cbb9ba; padding: 20px; border-radius: 15px; }
        .sidebar h2 { margin-top: 0; font-size: 20px; color: #4a3e3e; }
        .sidebar a { text-decoration: none; color: #6e5449; font-weight: bold; }
        .sidebar p { margin-top: 15px; }
        .main { flex: 1; }
        .cart { width: 280px; background: #dcd0d0; padding: 20px; border-radius: 15px; display: flex; flex-direction: column; justify-content: space-between; }
        .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 25px; }
        
        /* Layout Card Menu dengan Gambar */
        .card-menu { background: #fdfdfd; padding: 10px 12px; border-radius: 12px; display: flex; align-items: center; gap: 12px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .menu-img { width: 55px; height: 55px; border-radius: 10px; object-fit: cover; background-color: #e0e0e0; flex-shrink: 0; }
        .menu-details { flex: 1; }
        .btn-add-menu { background: #7c9391; color: white; border: none; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; font-weight: bold; font-size: 16px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        
        /* Style Ringkasan Pemesanan */
        .cart-item { display: flex; justify-content: space-between; align-items: center; background: #ffffff; padding: 8px 12px; border-radius: 10px; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: #333; }
        .qty-control { display: flex; align-items: center; gap: 6px; }
        .btn-qty { width: 22px; height: 22px; border-radius: 5px; border: none; color: white; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 14px; }
        .btn-minus { background-color: #e74c3c; }
        .btn-plus { background-color: #2ecc71; }
        .qty-num { font-size: 13px; font-weight: bold; width: 14px; text-align: center; }
        
        .buy-btn { width: 100%; padding: 12px; background: #2ecc71; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: bold; font-size: 15px; margin-top: 15px; }
        .buy-btn:hover { background: #27ae60; }
        .radio-group { margin: 15px 0; font-size: 13px; font-weight: 500; color: #444; }
        .radio-group label { display: block; margin-bottom: 6px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>CHEESYWAY</h2>
            <p><a href="menu.php" style="color: #000;">DASHBOARD</a></p>
            <p><a href="reviews.php">REVIEWS</a></p>
        </div>

        <!-- Main Content -->
        <div class="main">
            <h3 style="color: #4a3e3e; margin-top: 0;">MAKANAN</h3>
            <div class="grid">
                <?php while($row = $makanan->fetch_assoc()): 
                    $img_path = "../img/" . $row['gambar'];
                    $img_src = (!empty($row['gambar']) && file_exists($img_path)) 
                               ? $img_path 
                               : "https://via.placeholder.com/55?text=Food";
                ?>
                    <div class="card-menu">
                        <img src="<?= $img_src ?>" alt="<?= $row['nama_menu'] ?>" class="menu-img">
                        <div class="menu-details">
                            <strong style="color: #333; font-size: 14px;"><?= $row['nama_menu'] ?></strong><br>
                            <small style="color: #666;">Rp <?= number_format($row['harga'], 0, ',', '.') ?></small>
                        </div>
                        <button class="btn-add-menu" onclick="addToCart(<?= $row['id'] ?>, '<?= addslashes($row['nama_menu']) ?>', <?= $row['harga'] ?>)">+</button>
                    </div>
                <?php endwhile; ?>
            </div>

            <h3 style="color: #4a3e3e;">MINUMAN</h3>
            <div class="grid">
                <?php while($row = $minuman->fetch_assoc()): 
                    $img_path = "../img/" . $row['gambar'];
                    $img_src = (!empty($row['gambar']) && file_exists($img_path)) 
                               ? $img_path 
                               : "https://via.placeholder.com/55?text=Drink";
                ?>
                    <div class="card-menu">
                        <img src="<?= $img_src ?>" alt="<?= $row['nama_menu'] ?>" class="menu-img">
                        <div class="menu-details">
                            <strong style="color: #333; font-size: 14px;"><?= $row['nama_menu'] ?></strong><br>
                            <small style="color: #666;">Rp <?= number_format($row['harga'], 0, ',', '.') ?></small>
                        </div>
                        <button class="btn-add-menu" onclick="addToCart(<?= $row['id'] ?>, '<?= addslashes($row['nama_menu']) ?>', <?= $row['harga'] ?>)">+</button>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Ringkasan Pemesanan -->
        <div class="cart">
            <div>
                <h3 style="margin-top: 0; color: #333; font-size: 18px;">Ringkasan Pemesanan</h3>
                <div id="cart-items" style="max-height: 220px; overflow-y: auto;">
                    <small style="color: #777;">Belum ada menu yang dipilih.</small>
                </div>
            </div>

            <div>
                <hr style="border: none; border-top: 1px solid #bbb; margin: 15px 0;">
                <div class="radio-group">
                    <label><input type="radio" name="pay" value="Kasir" checked> BAYAR DI KASIR</label>
                    <label><input type="radio" name="pay" value="QRIS"> TRANSFER / QRIS</label>
                </div>
                <h4 style="margin: 10px 0; color: #333; font-size: 16px;">TOTAL: Rp <span id="total-price">0</span></h4>
                <button class="buy-btn" onclick="submitOrder()">BUY NOW</button>
            </div>
        </div>
    </div>

<script>
let cart = {};

function addToCart(id, nama, harga) {
    if (cart[id]) {
        cart[id].qty++;
    } else {
        cart[id] = { nama, harga, qty: 1 };
    }
    renderCart();
}

function reduceQuantity(id) {
    if (cart[id]) {
        cart[id].qty--;
        if (cart[id].qty <= 0) {
            delete cart[id];
        }
    }
    renderCart();
}

function increaseQuantity(id) {
    if (cart[id]) {
        cart[id].qty++;
    }
    renderCart();
}

function renderCart() {
    let container = document.getElementById('cart-items');
    let total = 0;
    
    if (Object.keys(cart).length === 0) {
        container.innerHTML = '<small style="color: #777;">Belum ada menu yang dipilih.</small>';
        document.getElementById('total-price').innerText = '0';
        return;
    }

    container.innerHTML = '';
    for (let id in cart) {
        let item = cart[id];
        total += item.harga * item.qty;
        container.innerHTML += `
            <div class="cart-item">
                <span>${item.nama}</span>
                <div class="qty-control">
                    <button class="btn-qty btn-minus" onclick="reduceQuantity(${id})">-</button>
                    <span class="qty-num">${item.qty}</span>
                    <button class="btn-qty btn-plus" onclick="increaseQuantity(${id})">+</button>
                </div>
            </div>
        `;
    }
    document.getElementById('total-price').innerText = total.toLocaleString('id-ID');
}

function submitOrder() {
    if (Object.keys(cart).length === 0) {
        alert('Pilih minimal satu menu sebelum memesan!');
        return;
    }

    let metode = document.querySelector('input[name="pay"]:checked').value;
    fetch('simpan_pesanan.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cart, metode })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert('Pesanan berhasil dikirim!');
            cart = {};
            renderCart();
        } else {
            alert('Gagal mengirim pesanan. Silakan coba lagi.');
        }
    });
}
</script>
</body>
</html>