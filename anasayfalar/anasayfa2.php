<?php include 'fsheaderMP.php'; ?>
<?php include 'fsleftnavbarMP.php'; ?>
<?php include '../auth.php'; ?>
<?php check_login(); ?>
 
<?php
include '../db.php';
$user_id = $_SESSION['user_id']; // Kullanıcı giriş yaptıysa user_id'yi session'dan alın

// Kullanıcının aktif ve tamamlanmış antrenmanlarını ve diyetlerini kontrol edin
$sql_active_training = "SELECT tp.package_name FROM user_training_purchases ut JOIN training_packages tp ON ut.package_id = tp.package_id WHERE ut.user_id = ? AND ut.is_completed = 0";
$sql_completed_training = "SELECT tp.package_name FROM user_training_purchases ut JOIN training_packages tp ON ut.package_id = tp.package_id WHERE ut.user_id = ? AND ut.is_completed = 1";
$sql_active_diet = "SELECT dp.package_name FROM user_diet_purchases ud JOIN diet_packages dp ON ud.package_id = dp.package_id WHERE ud.user_id = ?  AND ud.is_completed = 0";
$sql_completed_diet = "SELECT dp.package_name FROM user_diet_purchases ud JOIN diet_packages dp ON ud.package_id = dp.package_id WHERE ud.user_id = ? AND ud.is_completed = 1";

$stmt_active_training = $conn->prepare($sql_active_training);
$stmt_active_training->bind_param("i", $user_id);
$stmt_active_training->execute();
$result_active_training = $stmt_active_training->get_result();

$stmt_completed_training = $conn->prepare($sql_completed_training);
$stmt_completed_training->bind_param("i", $user_id);
$stmt_completed_training->execute();
$result_completed_training = $stmt_completed_training->get_result();

$stmt_active_diet = $conn->prepare($sql_active_diet);
$stmt_active_diet->bind_param("i", $user_id);
$stmt_active_diet->execute();
$result_active_diet = $stmt_active_diet->get_result();

$stmt_completed_diet = $conn->prepare($sql_completed_diet);
$stmt_completed_diet->bind_param("i", $user_id);
$stmt_completed_diet->execute();
$result_completed_diet = $stmt_completed_diet->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ana Sayfa</title>
    <link rel="stylesheet" href="../feelserotoninstyle.css">
    <script src="../feelseroJS.js" defer></script>
    <style>
        .main-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            padding: 100px;
        }
        .content {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 45%;
            padding: 20px;
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
    <header>
        <!-- Header içeriği burada olacak -->
    </header>
    <div class="main-container">
        <div class="content">
            <h2>Hoş Geldiniz, <?php echo $_SESSION['username']; ?></h2>

            <div class="section">
                <h3>Devam Eden Diyetlerim</h3>
                <?php if ($result_active_diet->num_rows > 0): ?>
                    <?php while ($row = $result_active_diet->fetch_assoc()): ?>
                        <p>Şu anda aldığınız diyet: <?php echo $row['package_name']; ?></p>
                    <?php endwhile; ?>
                    <a href="diyetlerim.php" class="button">Tüm Diyetleri Gör</a>
                <?php else: ?>
                    <p>Devam eden diyetiniz bulunmamaktadır.</p>
                <?php endif; ?>
            </div>

            <div class="section">
                <h3>Tamamlanmış Diyetlerim</h3>
                <?php if ($result_completed_diet->num_rows > 0): ?>
                    <?php while ($row = $result_completed_diet->fetch_assoc()): ?>
                        <p>Tamamladığınız diyet: <?php echo $row['package_name']; ?></p>
                    <?php endwhile; ?>
                    <a href="diyetlerim.php" class="button">Tüm Diyetleri Gör</a>
                <?php else: ?>
                    <p>Tamamlanmış diyetiniz bulunmamaktadır.</p>
                <?php endif; ?>
            </div>
        </div>
 
        <div class="content">
            <div class="section">
                <h3>Devam Eden Antrenmanlarım</h3>
                <?php if ($result_active_training->num_rows > 0): ?>
                    <?php while ($row = $result_active_training->fetch_assoc()): ?>
                        <p>Şu anda aldığınız antrenman: <?php echo $row['package_name']; ?></p>
                    <?php endwhile; ?>
                    <a href="antrenmanlarim.php" class="button">Tüm Antrenmanları Gör</a>
                <?php else: ?>
                    <p>Devam eden antrenmanınız bulunmamaktadır.</p>
                <?php endif; ?>
            </div>

            <div class="section">
                <h3>Tamamlanmış Antrenmanlarım</h3>
                <?php if ($result_completed_training->num_rows > 0): ?>
                    <?php while ($row = $result_completed_training->fetch_assoc()): ?>
                        <p>Tamamladığınız antrenman: <?php echo $row['package_name']; ?></p>
                    <?php endwhile; ?>
                    <a href="antrenmanlarim.php" class="button">Tüm Antrenmanları Gör</a>
                <?php else: ?>
                    <p>Tamamlanmış antrenmanınız bulunmamaktadır.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
