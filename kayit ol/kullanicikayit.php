<?php
/*include 'db.php';

function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function calculate_age($birthdate) {
    $birthDate = new DateTime($birthdate);
    $currentDate = new DateTime();
    $age = $currentDate->diff($birthDate)->y;
    return $age;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = validate_input($_POST['fullname']);
    $password = validate_input($_POST['password']);
    $confirm_password = validate_input($_POST['confirm-password']);
    $email = validate_input($_POST['email']);
    $username = validate_input($_POST['username']);
    $birthdate = validate_input($_POST['birthdate']);
    $gender = validate_input($_POST['gender']);

    if (empty($fullname) || empty($password) || empty($confirm_password) || empty($email) || empty($username) || empty($birthdate) || empty($gender)) {
        echo "Tüm alanlar doldurulmalıdır.";
        exit();
    }

    if ($password !== $confirm_password) {
        echo "Şifreler uyuşmuyor.";
        exit();
    }

    if (calculate_age($birthdate) < 16) {
        echo "Kullanıcıların en az 16 yaşında olması gerekir.";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (first_name, last_name, email, username, birthdate, gender, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $fullname, $fullname, $email, $username, $birthdate, $gender, $hashed_password);

    if ($stmt->execute() === TRUE) {
        echo "Kayıt başarıyla tamamlandı.";
    } else {
        echo "Hata: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}*/

include '../db.php';

function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function calculate_age($birthdate) {
    $birthDate = new DateTime($birthdate);
    $currentDate = new DateTime();
    $age = $currentDate->diff($birthDate)->y;
    return $age;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = validate_input($_POST['first_name']);
    $last_name = validate_input($_POST['last_name']);
    $password = validate_input($_POST['password']);
    $confirm_password = validate_input($_POST['confirm-password']);
    $email = validate_input($_POST['email']);
    $username = validate_input($_POST['username']);
    $birthdate = validate_input($_POST['birthdate']);
    $gender = validate_input($_POST['gender']);

    if (empty($first_name) || empty($last_name) || empty($password) || empty($confirm_password) || empty($email) || empty($username) || empty($birthdate) || empty($gender)) {
        echo "Tüm alanlar doldurulmalıdır.";
        exit();
    }

    if ($password !== $confirm_password) {
        echo "Şifreler uyuşmuyor.";
        exit();
    }

    if (calculate_age($birthdate) < 16) {
        echo "Kullanıcıların en az 16 yaşında olması gerekir.";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // first_name ve last_name ayrımı için fullname'i parçalıyoruz
    /*$name_parts = explode(' ', $fullname);
    $first_name = $name_parts[0];
    $last_name = isset($name_parts[1]) ? $name_parts[1] : '';*/

    $sql = "INSERT INTO users (first_name, last_name, email, username, birthdate, gender, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $first_name, $last_name, $email, $username, $birthdate, $gender, $hashed_password);

    if ($stmt->execute() === TRUE) {
        echo "Kayıt başarıyla tamamlandı.";
        header("Location:../giriş yap/logindeneme.php");
        exit();
    } else {
        echo "Hata: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<?php include 'kayitolkullanici7.html'?>