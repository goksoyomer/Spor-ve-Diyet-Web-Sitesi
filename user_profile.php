<?php
session_start();
include 'db.php';
include 'fsheader.php';
include 'fsleftnavbar.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<script>alert('Kullanıcı bulunamadı.'); window.location.href = 'user_store.php';</script>";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="feelserotoninstyle.css">
    <script src="feelseroJS.js" defer></script>
    <title>Kullanıcı Profili</title>
    <style>
        body {
            font-family: 'Ubuntu', sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
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
            margin-top: 20px;
        }

        .profile {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile h2 {
            margin: 0;
            margin-bottom: 20px;
        }

        .profile p {
            margin: 5px 0;
        }

        .profile .label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile">
            <h2><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h2>
            <p><span class="label">Kullanıcı Adı:</span> <?php echo $user['username']; ?></p>
            <p><span class="label">Cinsiyet:</span> <?php echo $user['gender']; ?></p>
            <p><span class="label">Boy:</span> <?php echo $user['height']; ?> cm</p>
            <p><span class="label">Kilo:</span> <?php echo $user['weight']; ?> kg</p>
            <p><span class="label">BMI:</span> <?php echo $user['bmi']; ?></p>
        </div>
    </div>
</body>
</html>
