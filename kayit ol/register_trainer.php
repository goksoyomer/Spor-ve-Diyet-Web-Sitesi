<?php
session_start();
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

$errors = [];
$form_data = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_data['fullname'] = validate_input($_POST['fullname']);
    $form_data['last_name'] = validate_input($_POST['last_name']);
    $form_data['id-number'] = validate_input($_POST['id-number']);
    $form_data['password'] = validate_input($_POST['password']);
    $form_data['confirm-password'] = validate_input($_POST['confirm-password']);
    $form_data['email'] = validate_input($_POST['email']);
    $form_data['username'] = validate_input($_POST['username']);
    $form_data['birthdate'] = validate_input($_POST['birthdate']);
    $form_data['gender'] = validate_input($_POST['gender']);
    $form_data['university'] = validate_input($_POST['university']);
    $form_data['profession'] = validate_input($_POST['profession']);

    if (empty($form_data['fullname']) || empty($form_data['last_name']) || empty($form_data['id-number']) || empty($form_data['password']) || empty($form_data['confirm-password']) || empty($form_data['email']) || empty($form_data['username']) || empty($form_data['birthdate']) || empty($form_data['gender']) || empty($form_data['university']) || empty($form_data['profession'])) {
        $errors[] = "Tüm alanlar doldurulmalıdır.";
    }

    if (!preg_match("/^[0-9]{11}$/", $form_data['id-number'])) {
        $errors[] = "Kimlik numarası sadece 11 haneli rakamlardan oluşmalıdır.";
    }

    if ($form_data['password'] !== $form_data['confirm-password']) {
        $errors[] = "Şifreler uyuşmuyor.";
    }

    if (calculate_age($form_data['birthdate']) < 18) {
        $errors[] = "Eğitmenlerin en az 18 yaşında olması gerekir.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($form_data['password'], PASSWORD_DEFAULT);

        if ($form_data['profession'] == "Diyetisyen") {
            $sql = "INSERT INTO dietitians (identity_number, university, first_name, last_name, email, username, birthdate, gender, password, profession) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        } else {
            $sql = "INSERT INTO trainers (identity_number, university, first_name, last_name, email, username, birthdate, gender, password, profession) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssss", $form_data['id-number'], $form_data['university'], $form_data['fullname'], $form_data['last_name'], $form_data['email'], $form_data['username'], $form_data['birthdate'], $form_data['gender'], $hashed_password, $form_data['profession']);

        if ($stmt->execute() === TRUE) {
         header("Location: ../giriş yap/egitmenlogin.php"); // Kayıt başarılı olunca yönlendirme
         exit();
         }else{
        $errors[] = "Eğitmen kaydında bir hata oluştu: " . $stmt_user->error;
         }
        

        $stmt->close();
        $conn->close();
    }

    $query = http_build_query([
        'errors' => implode("<br>", $errors),
        'fullname' => $form_data['fullname'],
        'last_name' => $form_data['last_name'],
        'id-number' => $form_data['id-number'],
        'email' => $form_data['email'],
        'username' => $form_data['username'],
        'birthdate' => $form_data['birthdate'],
        'gender' => $form_data['gender'],
        'university' => $form_data['university'],
        'profession' => $form_data['profession'],
    ]);

    header("Location: register_trainer.php?$query");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feelserotonin Kayıt Ol</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 60vw;
            max-width: 800px;
            padding: 20px;
            box-sizing: border-box;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .tab-button {
            background-color: #ffcc00;
            width: 100%;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            flex: 1;
            margin: 0;
        }

        .a-tab-button{
            background-color: #ffcc00;
            border: none;
            padding: 0;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            flex: 1;
            margin: 0;
        }

        .tab-button:hover {
            background-color: #ffd633;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            text-align: center;
            width: 100%;
            margin-bottom: 20px;
        }

        .form-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            width: 100%;
        }

        .form-left,
        .form-right {
            display: flex;
            flex-direction: column;
            width: 48%;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-buttons {
            display: flex;
            justify-content: center;
            width: 100%;
            margin-top: 20px;
        }

        .submit-button,
        .login-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 0 5px;
            max-width: 150px;
            flex: 1;
        }

        .login-button {
            background-color: #000;
        }

        .submit-button:hover {
            background-color: #45a049;
        }

        .login-button:hover {
            background-color: #333;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a class="a-tab-button" href="kullanicikayit.php">
                <button class="tab-button">Kullanıcı Olarak Kaydol</button>
            </a>
        </div>
        <?php if (isset($_GET['errors'])): ?>
            <div class="error">
                <?php echo htmlspecialchars($_GET['errors']); ?>
            </div>
        <?php endif; ?>
        <form class="form-container" action="register_trainer.php" method="post">
            <h2>EĞİTMEN KAYDI</h2>
            <div class="form-content">
                <div class="form-left">
                    <div class="form-group">
                        <label for="fullname">Adınızı Giriniz:</label>
                        <input type="text" id="fullname" name="fullname" placeholder="Adınız" value="<?php echo isset($_GET['fullname']) ? htmlspecialchars($_GET['fullname']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Soyadınızı Giriniz:</label>
                        <input type="text" id="last_name" name="last_name" placeholder="Soyadınız" value="<?php echo isset($_GET['last_name']) ? htmlspecialchars($_GET['last_name']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="id-number">TC Kimlik Numaranızı Giriniz:</label>
                        <input type="text" id="id-number" name="id-number" placeholder="Kimlik Numaranız" value="<?php echo isset($_GET['id-number']) ? htmlspecialchars($_GET['id-number']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Şifrenizi Giriniz:</label>
                        <input type="password" id="password" name="password" placeholder="Şifrenizi Giriniz" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Şifrenizi Tekrar Giriniz:</label>
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Şifrenizi Tekrar Giriniz" required>
                    </div>
                    <div class="form-group">
                        <label for="profession">Kayıt Türünüzü Seçiniz:</label>
                        <select id="profession" name="profession" required>
                            <option value="Diyetisyen" <?php echo (isset($_GET['profession']) && $_GET['profession'] == 'Diyetisyen') ? 'selected' : ''; ?>>Diyetisyen</option>
                            <option value="Antrenör" <?php echo (isset($_GET['profession']) && $_GET['profession'] == 'Antrenör') ? 'selected' : ''; ?>>Antrenör</option>
                        </select>
                    </div>
                </div>
                <div class="form-right">
                    <div class="form-group">
                        <label for="email">E-posta Adresinizi Giriniz:</label>
                        <input type="email" id="email" name="email" placeholder="E-posta Adresiniz" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Kullanıcı Adınızı Giriniz:</label>
                        <input type="text" id="username" name="username" placeholder="Kullanıcı Adınız" value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="birthdate">Doğum Tarihinizi Giriniz:</label>
                        <input type="date" id="birthdate" name="birthdate" value="<?php echo isset($_GET['birthdate']) ? htmlspecialchars($_GET['birthdate']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Cinsiyetinizi Seçiniz:</label>
                        <select id="gender" name="gender" required>
                            <option value="Erkek" <?php echo (isset($_GET['gender']) && $_GET['gender'] == 'Erkek') ? 'selected' : ''; ?>>Erkek</option>
                            <option value="Kadın" <?php echo (isset($_GET['gender']) && $_GET['gender'] == 'Kadın') ? 'selected' : ''; ?>>Kadın</option>
                            <option value="Diğer" <?php echo (isset($_GET['gender']) && $_GET['gender'] == 'Diğer') ? 'selected' : ''; ?>>Diğer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="university">Mezun Olduğunuz Üniversiteyi Seçiniz:</label>
                        <select id="university" name="university" required>
                            <option value="Bogazici Üniversitesi" <?php echo (isset($_GET['university']) && $_GET['university'] == 'Bogazici Üniversitesi') ? 'selected' : ''; ?>>Boğaziçi Üniversitesi</option>
                            <option value="Istanbul Teknik Üniversitesi" <?php echo (isset($_GET['university']) && $_GET['university'] == 'Istanbul Teknik Üniversitesi') ? 'selected' : ''; ?>>İstanbul Teknik Üniversitesi</option>
                            <option value="Orta Doğu Teknik Üniversitesi" <?php echo (isset($_GET['university']) && $_GET['university'] == 'Orta Doğu Teknik Üniversitesi') ? 'selected' : ''; ?>>Orta Doğu Teknik Üniversitesi</option>
                            <!-- Diğer üniversiteleri buraya ekleyin -->
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-buttons">
                <button type="submit" class="submit-button">Kayıt Ol</button>
                <button type="button" class="login-button" onclick="window.location.href='egitmenlogin.php'">Giriş Yap</button>
            </div>
        </form>
    </div>
</body>
</html>
