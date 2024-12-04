<?php
session_start();

// Kullanıcının mesleğine göre yönlendirme yapmadan önce mesleği kontrol edin
if (isset($_SESSION['profession'])) {
    $profession = $_SESSION['profession'];
    session_unset();
    session_destroy();

    if ($profession == "Antrenör" || $profession == "Diyetisyen") {
        header("Location: giriş yap/egitmenlogin.php");
        exit();
    } else {
        header("Location: giriş yap/logindeneme.php");
        exit();
    }
} else {
    // Meslek bilgisi yoksa varsayılan olarak kullanıcı giriş sayfasına yönlendir
    session_unset();
    session_destroy();
    header("Location: giriş yap/logindeneme.php");
    exit();
}
?>

