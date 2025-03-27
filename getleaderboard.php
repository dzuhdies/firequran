<?php
session_start();
include 'config.php';

try {
    $query = $pdo->query("
        SELECT u.username, SUM(p.pages) AS total_pages
        FROM users u
        JOIN progres p ON u.id = p.user_id
        GROUP BY u.username
        ORDER BY total_pages DESC
        LIMIT 5
    ");
    $leaderboard = $query->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($leaderboard);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Terjadi kesalahan saat mengambil leaderboard: " . $e->getMessage()]);
}
?>