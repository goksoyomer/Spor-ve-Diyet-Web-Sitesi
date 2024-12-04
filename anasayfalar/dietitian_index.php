<?php
include 'fsheaderMP.php';
include 'egitmenleftnavbar.php';
include '../authegitmen.php';
check_login();
include '../db.php';

$user_id = $_SESSION['user_id'];

$sql_dietitian_name = "SELECT first_name, last_name FROM dietitians WHERE dietitian_id = ?";
$stmt_dietitian_name = $conn->prepare($sql_dietitian_name);
$stmt_dietitian_name->bind_param("i", $user_id);
$stmt_dietitian_name->execute();
$result_dietitian_name = $stmt_dietitian_name->get_result();
$dietitian = $result_dietitian_name->fetch_assoc();


// Diyetisyen için aktif diyetleri getirin
$sql_active_diets = "SELECT dp.package_name FROM diet_packages dp JOIN dietitians d ON dp.dietitian_id = d.dietitian_id WHERE d.dietitian_id = ? AND dp.is_approved = 1";
$stmt_active_diets = $conn->prepare($sql_active_diets);
$stmt_active_diets->bind_param("i", $user_id);
$stmt_active_diets->execute();
$result_active_diets = $stmt_active_diets->get_result();
$stmt_active_diets->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diyetisyen Ana Sayfa</title>
    <link rel="stylesheet" href="../feelserotoninstyle.css">
    <script src="../feelseroJS.js" defer></script>
    <style>
        .main-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 120px;
            margin-top: 0px; /* Header'ın altında yer alması için margin-top ekledik */
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
            <h2>Hoş Geldiniz, <?php echo "Diyetisyen"." ".$dietitian['first_name']." ".$dietitian['last_name']; ?></h2>

            <div class="section">
                <h3>Aktif Diyet Paketleriniz</h3>
                <?php if ($result_active_diets->num_rows > 0): ?>
                    <ul>
                        <?php while ($diet = $result_active_diets->fetch_assoc()): ?>
                            <li><?php echo $diet['package_name']; ?></li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>Aktif diyet paketiniz bulunmamaktadır.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
