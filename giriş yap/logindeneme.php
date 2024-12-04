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

    if (empty($username) || empty($password)) {
        echo "<script>alert('Tüm alanlar doldurulmalıdır.');</script>";
    } else {
        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['first_name'] = $row['first_name'];
                $_SESSION['last_name'] = $row['last_name'];
                header("Location:../anasayfalar/anasayfa2.php");
                exit();
            } else {
                echo "<script>alert('Şifre yanlış.');</script>";
            }
        } else {
            echo "<script>alert('Kullanıcı adı bulunamadı.');</script>";
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<?php include 'girisyapkullanici.html' ?>
<!--<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>feelserotonin Giriş Yap</title>
    <style>
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
        }
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            background-color: #f0f0f0;
            justify-content: center;
            align-items: center;
            display: flex;
        }
        #labelUserName, #labelPassword {
            display: block;
            position: relative;
            font-size: 18px;
            font-family: Helvetica, Arial, sans-serif;
            margin-top: 3%;
        }
        input[type=text], input[type=password] {
            display: block;
            position: relative;
            margin-top: 6%;
            height: 40px;
            width: 350px;
        }
        .buttonSubmit {
            display: block;
            position: relative;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            margin: 8% auto 0 auto;
            height: 40px;
            width: 150px;
            font-weight: 700;
            cursor: pointer;
        }
        .buttonSubmit:hover {
            background-color: #45a049;
        }
        .forgatandregis {
            display: block;
            text-decoration: none;
            color: whitesmoke;
        }
        .userIndicator {
            display: block;
            font-size: 20px;
            margin: 4% 0 3%;
        }
        .enterTypeChangerR, .enterTypeChangerL {
            font-size: 20px;
            color: #121413;
            display: flex;
            height: 100%;
            width: 100%;
            text-decoration: none;
            justify-content: center;
            align-items: center;
        }
        .enterTypeChangerL:hover, .enterTypeChangerR:hover {
            background-color: #ffd633;
            border-radius: 5px 0 0 0;
        }
        .deneme {
            display: grid;
            grid-template-rows: 12.5% 80% 7.5%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 80%;
            width: 35%;
            margin-top: 0;
        }
        .deneme-item-1 {
            display: grid;
            grid-template-columns: 49.5% 1% 49.5%;
            background-color: #ffcc00;
            border-radius: 5px 5px 0 0;
        }
        .deneme-item-2 {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-around;
            background-color: #FFF;
        }
        .deneme-item-3 {
            background-color: #272323;
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            align-items: center;
            border-radius: 0 0 5px 5px;
        }
        .middleLine {
            background-color: #000000;
            height: 100%;
            width: 100%;
        }
        .loginDiv {
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 80%;
            width: 100%;
        }
        input[type=text]:focus, input[type=password]:focus {
            border: 3px solid #555;
        }
    </style>
</head>
<body>
  <div class="deneme">
     <div class="deneme-item-1">
      <div>
            <a class="enterTypeChangerL" href="#">
                Kullanıcı Girişi
            </a>
      </div>
      <div class="middleLine"></div> 
      <div>
            <a class="enterTypeChangerR" href="#">
                Eğitmen Girişi
            </a>
     </div>
   </div>
   <div class="deneme-item-2">
     <h2 class="userIndicator">KULLANICI GİRİŞİ</h2>
     <div class="loginDiv">
     <form class="input-login" name="usersLogin" method="post" action="logindeneme.php">
        <label for="userName" id="labelUserName"><strong>Kullanıcı Adınızı Giriniz:</strong></label>
        <input type="text" name="userName" placeholder="Kullanıcı Adınız" required>
        <label for="password" id="labelPassword"><strong>Şifrenizi Giriniz:</strong></label>
        <input type="password" name="password" placeholder="Şifreniz" required>
        <button type="submit" class="buttonSubmit" name="girisYap">Giriş Yap</button>
       </form>
    </div>
   </div>
   <div class="deneme-item-3">
     <a href="#" class="forgatandregis"><b>Kayıt Ol</b></a>
     <a href="#" class="forgatandregis"><b>Şifremi Unuttum</b></a>
     </div>
   </div>
</body>
</html>-->
