<?php
session_start();
include '../koneksi.php';

$query = "SELECT * FROM pesanan WHERE status = 'pending' ORDER BY id DESC";
$result = $conn->query($query);

$orders = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
header('Content-Type: application/json');
echo json_encode($orders);
?>