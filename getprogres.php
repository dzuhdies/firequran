<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Silakan login"]);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $query = $pdo->prepare("SELECT SUM(pages) AS total FROM progres WHERE user_id = ?");
    $query->execute([$user_id]);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['total' => $result['total'] ?? 0]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Terjadi kesalahan saat mengambil progres: " . $e->getMessage()]);
}
?>