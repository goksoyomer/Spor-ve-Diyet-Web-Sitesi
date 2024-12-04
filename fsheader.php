<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700&display=swap" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
 <!--<link rel="stylesheet" href="../feelserotoninstyle.css">
 <script src="../feelseroJS.js" defer></script>-->
 <title>
 Spor&Diyet sağlık için spor ve diyet sayfası
 </title>
 <meta charset="UTF-8">
 
</head>
<body>
<div id="header">
  <span id="openNavBar">&#9776;</span>
 <div id="headerSiteName">
  <h1 id="siteName">Spor&Diyet</h1>
 </div>
  <a class="shoppingLogo" href="#">
    <span class="fa-solid fa-cart-shopping fa-2xl" style="color: #000000;"></span>
  </a>
  <a id="notificationBell" href="#">
  <span class="fa-solid fa-bell fa-2xl" style="color: #000000"></span>
  </a>
  <a class="loginout" id="regisandlogout" href="logout.php">
   <b>Çıkış Yap</b>
 </a>
 

  <a class="loginout" id="loginText" href="<?php echo isset($_SESSION['profession']) && ($_SESSION['profession'] == 'Diyetisyen' || $_SESSION['profession'] == 'Antrenör') ? '../egitmen_profile.php' : '../user_profile.php'; ?>">
  <?php
   if (isset($_SESSION['first_name']) && isset($_SESSION['last_name'])) {
       echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
   } else {
       echo 'Giriş Yap';
   }
   ?>
  </a>
 
</div>
</body>
</html>
