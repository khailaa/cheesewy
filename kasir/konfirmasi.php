<?php
session_start();
include '../koneksi.php';

$id = $_POST['id'] ?? null;

if ($id) {
    $query = "UPDATE pesanan SET status = 'selesai' WHERE id = '$id'";
    if ($conn->query($query)) {
        echo json_encode(['success' => true]);
        exit();
    }
}
echo json_encode(['success' => false]);
?>