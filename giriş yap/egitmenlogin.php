<?php
session_start();
include '../db.php';

function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = validate_input($_POST['userName']);
    $password = validate_input($_POST['password']);
    $identity_number = validate_input($_POST['nationalIdentity']);

    if (empty($username) || empty($password) || empty($identity_number)) {
        echo "<script>alert('Tüm alanlar doldurulmalıdır.');</script>";
    } else {
        // Eğitmen tablosunda kullanıcı adı ve kimlik numarası kontrolü
        $sql_trainer = "SELECT * FROM trainers WHERE username=? AND identity_number=?";
        $stmt_trainer = $conn->prepare($sql_trainer);
        $stmt_trainer->bind_param("ss", $username, $identity_number);
        $stmt_trainer->execute();
        $result_trainer = $stmt_trainer->get_result();

        // Diyetisyen tablosunda kullanıcı adı ve kimlik numarası kontrolü
        $sql_dietitian = "SELECT * FROM dietitians WHERE username=? AND identity_number=?";
        $stmt_dietitian = $conn->prepare($sql_dietitian);
        $stmt_dietitian->bind_param("ss", $username, $identity_number);
        $stmt_dietitian->execute();
        $result_dietitian = $stmt_dietitian->get_result();

        if ($result_trainer->num_rows > 0) {
            $row = $result_trainer->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['trainer_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['first_name'] = $row['first_name']; // Adı ekle
                $_SESSION['last_name'] = $row['last_name']; // Soyadı ekle
                $_SESSION['profession'] = $row['profession'];
                $_SESSION['is_approved'] = $row['is_approved'];
                header("Location: ../anasayfalar/trainer_index.php");
                exit();
            } else {
                echo "<script>alert('Şifre yanlış.');</script>";
            }
        } elseif ($result_dietitian->num_rows > 0) {
            $row = $result_dietitian->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['dietitian_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['first_name'] = $row['first_name']; // Adı ekle
                $_SESSION['last_name'] = $row['last_name']; // Soyadı ekle
                $_SESSION['profession'] = $row['profession'];
                $_SESSION['is_approved'] = $row['is_approved'];
                header("Location: ../anasayfalar/dietitian_index.php");
                exit();
            } else {
                echo "<script>alert('Şifre yanlış.');</script>";
            }
        } else {
            echo "<script>alert('Kullanıcı adı veya kimlik numarası bulunamadı.');</script>";
        }

        $stmt_trainer->close();
        $stmt_dietitian->close();
        $conn->close();
    }
}
?>

<?php include 'girisyapegitmen.html' ?>
