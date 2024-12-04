<?php 
 $profession = $_SESSION['profession'];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
 <!--<link rel="stylesheet" href="feelserotoninstyle.css">
 <script src="feelseroJS.js" defer></script>-->
 <title>
 feelserotonin sağlık için spor ve diyet sayfası
 </title>
 <meta charset="UTF-8">
 
</head>
<div id="leftNavBar">
  <ul class="leftNavUl">
  <li>
  <a href='<?php echo $profession == 'Diyetisyen' ? 'dietitian_index.php' : 'trainer_index.php'; ?>'>
    <img src="../anasayfa.svg">
    <span> 
      <b>Anasayfa</b>
    </span>
   </a>
 
  </li>
  <!--<li>
   <a href="">
    <img src="büyüteç.svg">
    <span> 
      <b>Keşfet</b>
    </span>
   </a>
   </li>-->
   <li>
    <a href="addpackages.php">
    <img src="../paketekle.svg">
   <span> 
     <b>Paket Ekle</b>
   </span>
    </a>
   </li>
   <li>
    <a href="mystore.php">
      <img src="../mağaza.svg">
    <span> 
       <b>Mağazam</b>
    </span>
    </a>
  </li>
   <li>
    <a href="egitmen_profile.php">
   <img src="../profil logosu.svg">
   <span> 
     <b>Profilim</b>
   </span>
    </a>
   </li>
  <li>
     <a href="">
        <img src="../mesajgönder2.svg">
        <span> 
         <b>Mesajlar (Hazır Değil!)</b>
        </span>
      </a>
  </li>
   
  <li>
    <a href="">
       <img src="../ayarlar.svg">
       <span> 
        <b>Ayarlar (Hazır Değil!)</b>
       </span>
     </a>
 </li>
 </ul>
</div>