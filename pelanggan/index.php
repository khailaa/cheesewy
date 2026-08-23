<?php
session_start();
require_once '../koneksi.php';

// Menangkap parameter meja dari URL jika ada (contoh: index.php?meja=06)
$no_meja_url = isset($_GET['meja']) ? $_GET['meja'] : '';

if (isset($_POST['masuk'])) {
    $nama = $_POST['nama_pelanggan'];
    $meja = $_POST['no_meja'];
    $antri = $_POST['no_antri'];

    if (!empty($nama) && !empty($meja)) {
        $_SESSION['nama'] = $nama;
        $_SESSION['no_meja'] = $meja;
        $_SESSION['no_antri'] = $antri;
        header("Location: menu.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cheesyway - Login Pelanggan</title>
  
  <!-- Font Serif & Sans-Serif Modern -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Poppins:wght@400;600;700&family=Playfair+Display:ital,wght@0,600;1,400&display=swap" rel="stylesheet">

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background-color: #ffd562; /* Warna krem muda background atas */
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      position: relative;
    }

    /* Elemen Hiasan Hijau di Bagian Bawah */
    body::after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 120px;
      background-color: #b98a33; /* Hijau gelap sesuai gambar 2 */
      z-index: 1;
    }

    /* Kartu Utama Login */
    .card {
      background-color: #927b51e5; /* Warna cokelat emas karton */
      width: 800px;
      max-width: 90%;
      border-radius: 25px;
      display: flex;
      padding: 40px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
      z-index: 2;
      position: relative;
    }

    /* Sisi Kiri (Branding & Deskripsi) */
    .brand {
      flex: 1;
      padding-right: 30px;
      color: #ffffff;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .brand h1 {
      font-family: 'Cinzel', serif;
      font-size: 28px;
      letter-spacing: 3px;
      margin-bottom: 30px;
      font-weight: 700;
      color: #ffffff;
    }

    .brand h3 {
      font-family: 'Playfair Display', serif;
      font-size: 22px;
      margin-bottom: 12px;
      font-weight: 600;
    }

    .brand p {
      font-size: 14px;
      line-height: 1.6;
      opacity: 0.95;
    }

    /* Sisi Kanan (Form Login) */
    .form-box {
      flex: 1;
      padding-left: 30px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .form-box h2 {
      color: #ffffff;
      font-size: 32px;
      text-align: center;
      font-weight: 700;
      letter-spacing: 2px;
      margin-bottom: 5px;
    }

    /* Garis Bawah di Teks LOGIN */
    .form-box .underline {
      width: 120px;
      height: 3px;
      background-color: #333333;
      margin: 0 auto 25px auto;
      border-radius: 2px;
    }

    /* Input Field */
    .input-group {
      margin-bottom: 15px;
    }

    .input-group input {
      width: 100%;
      padding: 12px 20px;
      border: none;
      border-radius: 20px;
      background-color: #e6dac6; /* Warna input cokelat krem muda */
      color: #555555;
      font-size: 14px;
      font-weight: 600;
      outline: none;
    }

    .input-group input::placeholder {
      color: #777777;
      font-size: 13px;
    }

    /* Tombol Masuk */
    .btn-submit {
      width: 100%;
      padding: 12px;
      background-color: #e6dac6;
      color: #444444;
      border: none;
      border-radius: 20px;
      font-weight: 700;
      font-size: 14px;
      cursor: pointer;
      margin-top: 10px;
      transition: all 0.3s ease;
    }

    .btn-submit:hover {
      background-color: #d8c5a8;
    }
  </style>
</head>
<body>

  <div class="card">
    <!-- Sisi Kiri -->
    <div class="brand">
      <h1>CHEESYWAY</h1>
      <h3>The Only Way</h3>
      <p>
        Menjadi "jalan" atau rute utama bagi para pecinta keju ketika mencari tempat makan dan nongkrong yang memuaskan.
      </p>
    </div>

    <!-- Sisi Kanan -->
    <div class="form-box">
      <h2>LOGIN</h2>
      <div class="underline"></div>

      <form action="" method="POST">
        <div class="input-group">
          <input type="text" name="nama_pelanggan" placeholder="NAMA ;" required autocomplete="off">
        </div>
        
        <div class="input-group">
          <input type="text" name="no_meja" value="<?= htmlspecialchars($no_meja_url); ?>" placeholder="NO MEJA :" required>
        </div>

        <button type="submit" name="masuk" class="btn-submit">MASUK & PESAN</button>

      </form>
      
    </div>
  </div>

</body>
</html>