<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: egitmenlogin.php");
        exit();
    }
}

function check_profession($conn) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: egitmenlogin.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Eğitmen mi kontrol et
    $sql_check_trainer = "SELECT profession FROM trainers WHERE trainer_id = ?";
    $stmt_check_trainer = $conn->prepare($sql_check_trainer);
    $stmt_check_trainer->bind_param("i", $user_id);
    $stmt_check_trainer->execute();
    $result_check_trainer = $stmt_check_trainer->get_result();
    $trainer_profession = $result_check_trainer->fetch_assoc();
    $stmt_check_trainer->close();

    if ($trainer_profession) {
        if ($trainer_profession['profession'] == 'Antrenör') {
            header("Location: trainer_anasayfa.php");
            exit();
        }
    }

    // Diyetisyen mi kontrol et
    $sql_check_dietitian = "SELECT profession FROM dietitians WHERE dietitian_id = ?";
    $stmt_check_dietitian = $conn->prepare($sql_check_dietitian);
    $stmt_check_dietitian->bind_param("i", $user_id);
    $stmt_check_dietitian->execute();
    $result_check_dietitian = $stmt_check_dietitian->get_result();
    $dietitian_profession = $result_check_dietitian->fetch_assoc();
    $stmt_check_dietitian->close();

    if ($dietitian_profession) {
        if ($dietitian_profession['profession'] == 'Diyetisyen') {
            header("Location: dietitian_anasayfa.php");
            exit();
        }
    }

    // Ne eğitmen ne de diyetisyen ise
    header("Location: logindeneme.php");
    exit();
}
?>
