<?php
session_start();

include 'config.php';

function getAllUsers($pdo) {
    try {
        $query = "
            SELECT u.username, COALESCE(SUM(p.pages), 0) as total_pages
            FROM users u
            LEFT JOIN progres p ON u.id = p.user_id
            GROUP BY u.username
            ORDER BY total_pages DESC
        ";
        $stmt = $pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Query gagal: " . $e->getMessage());
    }
}

function searchUsers($pdo, $username) {
    try {
        $query = "
            SELECT u.username, COALESCE(SUM(p.pages), 0) as total_pages
            FROM users u
            LEFT JOIN progres p ON u.id = p.user_id
            WHERE u.username LIKE :username
            GROUP BY u.username
        ";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':username', "%$username%");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Query gagal: " . $e->getMessage());
    }
}

$users = isset($_GET['username']) ? searchUsers($pdo, $_GET['username']) : getAllUsers($pdo);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Pengguna</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f9f9f9;
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
      margin-bottom: 20px;
    }

    .header h1 {
      font-size: 24px;
      margin: 0;
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

    .search-bar {
      display: flex;
      margin-bottom: 20px;
    }

    .search-bar input {
      flex: 1;
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 10px 0 0 10px;
      outline: none;
    }

    .search-bar button {
      padding: 10px;
      font-size: 16px;
      background: #007bff;
      color: #fff;
      border: none;
      border-radius: 0 10px 10px 0;
      cursor: pointer;
      transition: background 0.3s;
    }

    .search-bar button:hover {
      background: #0056b3;
    }

    .following-list {
      margin-top: 20px;
    }

    .following-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #fff;
      border-radius: 10px;
      padding: 15px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      margin-bottom: 10px;
    }

    .following-item .username {
      font-size: 18px;
      font-weight: bold;
      color: #333;
    }

    .following-item .progres {
      font-size: 16px;
      color: #555;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <button class="back-button" onclick="window.location.href='dashboard.php'">Kembali</button>
      <h1>Akun</h1>
    </div>

    <div class="search-bar">
      <form method="GET" action="">
        <input type="text" name="username" placeholder="Cari akun..." value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>">
        <button type="submit">Cari</button>
      </form>
    </div>

    <p>Menampilkan semua akun...</p>

    <div class="following-list">
      <?php foreach ($users as $user): ?>
        <div class="following-item">
          <div class="username">@<?php echo htmlspecialchars($user['username']); ?></div>
          <div class="progres"><?php echo htmlspecialchars($user['total_pages']); ?> Halaman</div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>
