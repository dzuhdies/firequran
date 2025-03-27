<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Silakan login"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$pages_read = $_POST['pages'];

if ($pages_read > 0) {
    try {
        $query = $pdo->prepare("INSERT INTO progres (user_id, pages) VALUES (?, ?)");
        $query->execute([$user_id, $pages_read]);
        echo json_encode(["status" => "success"]);
    } catch (PDOException $e) {
        // Tangani error query
        echo json_encode(["status" => "error", "message" => "Terjadi kesalahan saat menyimpan progres: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Jumlah halaman tidak valid"]);
}
?>
