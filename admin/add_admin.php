<?php
include '../db.php';

function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function add_admin($username, $password) {
    global $conn;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Parola hashleme

    $sql = "INSERT INTO admins (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        echo "Admin başarıyla eklendi.";
    } else {
        echo "Hata: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = validate_input($_POST['username']);
    $password = validate_input($_POST['password']);
    $confirm_password = validate_input($_POST['confirm-password']);

    if (empty($username) || empty($password) || empty($confirm_password)) {
        echo "Tüm alanlar doldurulmalıdır.";
        exit();
    }

    if ($password !== $confirm_password) {
        echo "Şifreler uyuşmuyor.";
        exit();
    }

    add_admin($username, $password);
}
?>
<?php include 'add_admin.html' ?>