<?php
session_start();
include '../db.php';
include 'fsheaderMP.php';
include 'egitmenleftnavbar.php';

$user_id = $_SESSION['user_id'];
$profession = $_SESSION['profession'];

if ($profession == 'Diyetisyen') {
    $sql = "SELECT d.*, COUNT(dp.package_id) as approved_packages FROM dietitians d 
            LEFT JOIN diet_packages dp ON d.dietitian_id = dp.dietitian_id AND dp.is_approved = 1 
            WHERE d.dietitian_id = ?";
} else {
    $sql = "SELECT t.*, COUNT(tp.package_id) as approved_packages FROM trainers t 
            LEFT JOIN training_packages tp ON t.trainer_id = tp.trainer_id AND tp.is_approved = 1 
            WHERE t.trainer_id = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

if (!$profile) {
    echo "<script>alert('Eğitmen bulunamadı.'); window.location.href = 'user_store.php';</script>";
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
    <link rel="stylesheet" href="../feelserotoninstyle.css">
    <script src="../feelseroJS.js" defer></script>
    <title>Eğitmen Profili</title>
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
            <h2><?php echo $profile['first_name'] . ' ' . $profile['last_name']; ?></h2>
            <p><span class="label">Mezun Olduğu Okul:</span> <?php echo $profile['university']; ?></p>
            <p><span class="label">Uzmanlık:</span> <?php echo $profile['profession']; ?></p>
            <p><span class="label">Onaylı Paket Sayısı:</span> <?php echo $profile['approved_packages']; ?></p>
        </div>
    </div>
</body>
</html>
