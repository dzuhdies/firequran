<?php

session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT username FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $username = $user['username'];
} else {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Halaman Akun</title>
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
      text-align: center;
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

    .username {
      font-size: 28px;
      font-weight: bold;
      margin: 20px 0;
    }

    .info-card {
      background: #fff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
      text-align: left;
    }

    .info-card p {
      margin: 10px 0;
      font-size: 16px;
      color: #333;
    }

    .change-password {
      font-size: 16px;
      color: #007bff;
      cursor: pointer;
      text-decoration: underline;
      margin-bottom: 10px;
      display: inline-block;
    }

    .change-password:hover {
      color: #0056b3;
    }

  </style>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <div class="container">
    <div class="header">
      <button class="back-button" onclick="window.location.href='dashboard.php'">Kembali</button>
      <h1>Akun Saya</h1>
    </div>

    <div class="username"><?php echo htmlspecialchars($username); ?></div>

    <span class="change-password" onclick="changePassword()">Ganti Password</span>

    <br>

  </div>

  <script>
    function changePassword() {
      Swal.fire({
        icon: 'info',
        title: 'Fitur belum tersedia',
        text: 'Fitur untuk mengganti password belum tersedia saat ini. Mohon bersabar.',
        confirmButtonText: 'OK'
      });
    }
  </script>
</body>
</html>
