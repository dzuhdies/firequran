<?php

session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); 
    exit;
}


$user_id = $_SESSION['user_id'];

 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    $new_pages = $_POST['new_pages'];
    
    $updateQuery = "UPDATE progres SET pages = :new_pages WHERE id = :edit_id AND user_id = :user_id";
    $stmt = $pdo->prepare($updateQuery);
    $stmt->bindParam(':new_pages', $new_pages, PDO::PARAM_INT);
    $stmt->bindParam(':edit_id', $edit_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    
    header("Location: history.php");  
    exit;
}

 
$query = "SELECT id, date, pages FROM progres WHERE user_id = :user_id ORDER BY date DESC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

 
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>History</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f9f9f9; }
    .container { max-width: 400px; margin: auto; padding: 20px; }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .header h1 { font-size: 24px; margin: 0; }
    .back-button { font-size: 18px; color: #555; background: #f1f1f1; padding: 10px 20px; border: none; border-radius: 10px; cursor: pointer; }
    .back-button:hover { background: #ddd; }
    .history-list { margin-top: 20px; }
    .history-item { display: flex; justify-content: space-between; align-items: center; background: #fff; border-radius: 10px; padding: 15px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); margin-bottom: 10px; }
    .history-item .date { font-size: 16px; color: #333; font-weight: bold; }
    .history-item .pages { font-size: 16px; color: #555; }
    .edit-button { font-size: 14px; padding: 5px 10px; margin-left: 10px; background: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer; }
    .edit-button:hover { background: #0056b3; }
    .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
    .modal-content { background: white; padding: 20px; border-radius: 10px; text-align: center; width: 80%; max-width: 400px; }
    .close-button { cursor: pointer; color: red; font-size: 20px; }
    .input-field { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; }
    .save-button { padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
    .save-button:hover { background: #218838; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <button class="back-button" onclick="location.href='dashboard.php'">Kembali</button>
      <h1>History</h1>
    </div>

    <div class="history-list">
      <?php if ($history) {
        foreach ($history as $entry) {
          echo "<div class='history-item'>
                  <div>
                    <div class='date'>" . htmlspecialchars($entry['date']) . "</div>
                    <div class='pages'>" . htmlspecialchars($entry['pages']) . " Halaman</div>
                  </div>
                  <button class='edit-button' onclick='openModal(" . $entry['id'] . ", " . $entry['pages'] . ")'>Edit</button>
                </div>";
        }
      } else {
        echo "<p>Tidak ada data bacaan yang ditemukan.</p>";
      }
      ?>
    </div>
  </div>

  <div class="modal" id="editModal">
    <div class="modal-content">
      <span class="close-button" onclick="closeModal()">&times;</span>
      <h2>Edit Bacaan</h2>
      <form method="POST" action="history.php">
        <input type="hidden" name="edit_id" id="edit_id">
        <input type="number" name="new_pages" id="new_pages" class="input-field" min="1" required>
        <br>
        <button type="submit" class="save-button">Simpan</button>
      </form>
    </div>
  </div>

  <script>
    function openModal(id, pages) {
      document.getElementById('edit_id').value = id;
      document.getElementById('new_pages').value = pages;
      document.getElementById('editModal').style.display = 'flex';
    }

    function closeModal() {
      document.getElementById('editModal').style.display = 'none';
    }
  </script>
</body>
</html>
