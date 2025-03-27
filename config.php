<?php
$host = "sql300.infinityfree.com";
$dbname = "if0_38337293_quran";
$user = "if0_38337293";
$pass = "tQ6NMIMoTLCEG";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
