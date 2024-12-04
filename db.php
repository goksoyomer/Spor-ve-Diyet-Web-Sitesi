<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "health_fitness2";

// Bağlantıyı oluştur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı hatası/Connection failed: " . $conn->connect_error);
}
?>
