<?php
include 'fsheaderMP.php';
include 'egitmenleftnavbar.php';
include '../authegitmen.php';
check_login();
include '../db.php';

$user_id = $_SESSION['user_id'];

$sql_trainer_name = "SELECT first_name, last_name FROM trainers WHERE trainer_id = ?";
$stmt_trainer_name = $conn->prepare($sql_trainer_name);
$stmt_trainer_name->bind_param("i", $user_id);
$stmt_trainer_name->execute();
$result_trainer_name = $stmt_trainer_name->get_result();
$trainer = $result_trainer_name->fetch_assoc();

// Eğitmen için aktif antrenmanları getirin
$sql_active_trainings = "SELECT tp.package_name FROM training_packages tp JOIN trainers t ON tp.trainer_id = t.trainer_id WHERE t.trainer_id = ? AND tp.is_approved = 1";
$stmt_active_trainings = $conn->prepare($sql_active_trainings);
$stmt_active_trainings->bind_param("i", $user_id);
$stmt_active_trainings->execute();
$result_active_trainings = $stmt_active_trainings->get_result();
$stmt_active_trainings->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrenör Ana Sayfa</title>
    <link rel="stylesheet" href="../feelserotoninstyle.css">
    <script src="../feelseroJS.js" defer></script>
    <
    <style>
        .main-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 120px;
        }
        .content {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 1000px;
            padding: 20px;
            margin-top: 20px;
            box-sizing: border-box;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h3 {
            margin-bottom: 10px;
        }
        .section p {
            margin-bottom: 10px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="content">
            <h2>Hoş Geldiniz, <?php echo "Antrenör"." ".$trainer['first_name']." ".$trainer['last_name']; ?></h2>

            <div class="section">
                <h3>Aktif Antrenman Paketleriniz</h3>
                <?php if ($result_active_trainings->num_rows > 0): ?>
                    <ul>
                        <?php while ($training = $result_active_trainings->fetch_assoc()): ?>
                            <li><?php echo $training['package_name']; ?></li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>Aktif antrenman paketiniz bulunmamaktadır.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
