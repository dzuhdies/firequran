<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = $pdo->prepare("SELECT SUM(pages) AS total_pages FROM progres WHERE user_id = ?");
$query->execute([$user_id]);
$userProgress = $query->fetch(PDO::FETCH_ASSOC);
$totalPagesRead = $userProgress['total_pages'] ?? 0; 

$rankingQuery = $pdo->query("
    SELECT users.username, SUM(progres.pages) AS total_pages 
    FROM progres 
    JOIN users ON progres.user_id = users.id 
    GROUP BY users.id, users.username 
    ORDER BY total_pages DESC 
    LIMIT 10
");
$ranking = $rankingQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FireQur'an - RQA</title>
  <link rel="stylesheet" href="index.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

  <div class="container">
    <div class="header">
      <h1>FireQur'an - RQA</h1>
      <div class="menu-icon" onclick="toggleSidebar()">☰</div>
    </div>

    <div class="progress-card">
      <div class="progress-title">Pencapaianmu Ramadhan Ini :</div>
      <div class="progress-content">
        <h2><?php echo $totalPagesRead; ?></h2>
        <p>Halaman</p>
      </div>
    </div>

    <div>
      <p> Tilawah Terbanyak Ramadhan ini</p>
    </div>

    <?php foreach ($ranking as $index => $user): ?>
      <div class="ranking">
        <h3><?php echo ($index + 1) . ". " . htmlspecialchars($user['username']) . " - " . $user['total_pages'] . " Halaman"; ?></h3>
      </div>
    <?php endforeach; ?>

    
    <div class="footer-text">
      <b>Jangan Menyerah Karena Melihat Pencapaian Orang Lain. Gunakan Ini Sebagai Motivasi!</b>
    </div>
    <div class="footer-text">
      <b>Fastabiqul Khoirot, Semoga Lelah berbuah Jannah</b>
    </div>

    <div class="footer-text">
      Rumah Qur'an Ar-Rahman Balung
    </div>

    <div class="add-button" onclick="showModal()">+</div>
  </div>

  <div class="sidebar" id="sidebar">
    <ul>
      <li><a href="akun.php">Akun</a></li>
      <li><a href="following.php">Cari Akun</a></li>
      <li><a href="history.php">Histori</a></li>
      <li><a href="diagram.php">Diagram</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>

  <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

  <div class="modal" id="modal">
    <h3>Tambah Progres</h3>
    <input type="number" id="progressInput" placeholder="Masukkan jumlah halaman" />
    <div class="modal-buttons">
      <button class="back-button" onclick="hideModal()">Kembali</button>
      <button class="add-progress-button" onclick="addProgress()">Tambah</button>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('active');
      document.getElementById('overlay').classList.toggle('active');
    }

    function showModal() {
      document.getElementById('modal').classList.add('active');
      document.getElementById('overlay').classList.add('active');
    }

    function hideModal() {
      document.getElementById('modal').classList.remove('active');
      document.getElementById('overlay').classList.remove('active');
    }

    function addProgress() {
    const input = document.getElementById("progressInput");
    const pages = parseInt(input.value);

    if (pages > 0) {
        fetch("addprogres.php", {
          method: "POST",
          body: new URLSearchParams({ pages: pages }),
          headers: { "Content-Type": "application/x-www-form-urlencoded" }
        })
        .then(response => response.json())
        .then(data => {
          if (data.status === "success") {
            hideModal();  
            Swal.fire({
              icon: "success",
              title: "Berhasil!",
              text: `Progres ${pages} halaman telah ditambahkan.`,
              timer: 2000,
              showConfirmButton: false
            }).then(() => {
              location.reload();
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Gagal",
              text: "Gagal menambahkan progres: " + data.message,
              confirmButtonText: "OK"
            });
          }
        })
        .catch(error => {
          console.error("Error adding progress:", error);
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Terjadi kesalahan saat menambahkan progres.",
            confirmButtonText: "OK"
          });
        });
    } else {
        Swal.fire({
          icon: "warning",
          title: "Input tidak valid",
          text: "Masukkan angka yang valid!",
          confirmButtonText: "OK"
        });
    }
}

  </script>

</body>
</html>
