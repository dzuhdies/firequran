<?php
session_start();
include 'config.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];

function getReadingProgress($userId) {
    global $pdo;
    try {
        $query = "
            SELECT COALESCE(SUM(p.pages), 0) as total_pages
            FROM users u
            LEFT JOIN progres p ON u.id = p.user_id
            WHERE u.id = :userId
        ";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_pages'];
    } catch (PDOException $e) {
        die("Query gagal: " . $e->getMessage());
    }
}

$totalPages = getReadingProgress($userId);

$totalKhatam = floor($totalPages / 604);

$progressPercent = ($totalPages % 604) / 604 * 100;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Diagram</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f9f9f9;
      text-align: center;
    }

    .container {
      max-width: 400px;
      margin: auto;
      padding: 20px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 0;
      font-size: 24px;
      font-weight: bold;
    }

    .back-button {
      font-size: 18px;
      color: #555;
      background: #f1f1f1;
      padding: 10px 20px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .back-button:hover {
      background: #ddd;
    }

    .total-khatam {
      font-size: 36px;
      font-weight: bold;
      color: #333;
      margin: 20px 0;
    }

    .progress-widget {
      background: #fff;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      margin: 10px auto;
      width: 80%;
      font-size: 20px;
      font-weight: bold;
      color: #555;
    }

    .progress-circle {
      position: relative;
      width: 150px;
      height: 150px;
      border-radius: 50%;
      background: conic-gradient(#007bff var(--progress), #ddd 0);
      margin: 20px auto;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      font-weight: bold;
      color: #333;
    }

    .progress-circle::after {
      content: '';
      position: absolute;
      width: 120px;
      height: 120px;
      background: #fff;
      border-radius: 50%;
    }

    .progress-text {
      position: absolute;
      z-index: 1;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <button class="back-button" onclick="history.back()">Kembali</button>
      <span>Diagram</span>
      <span></span>
    </div>
    <div class="total-khatam">Total Khatam: <?php echo $totalKhatam; ?></div>
    <div class="progress-widget"><?php echo $totalPages % 604; ?> dari 604 Halaman</div>
    <div class="progress-circle" style="--progress: <?php echo $progressPercent; ?>%">
      <span class="progress-text"><?php echo round($progressPercent); ?>%</span>
    </div>
  </div>
</body>
</html>
