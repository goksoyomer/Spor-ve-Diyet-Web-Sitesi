<?php
session_start();
include '../db.php';
include 'fsheaderMP.php';
include 'egitmenleftnavbar.php';
include '../authegitmen.php';
check_login();

$user_id = $_SESSION['user_id'];
$profession = $_SESSION['profession'];

if ($profession == 'Diyetisyen') {
    $sql = "SELECT * FROM diet_packages WHERE dietitian_id = ?";
} elseif ($profession == 'Antrenör') {
    $sql = "SELECT * FROM training_packages WHERE trainer_id = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mağazam</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../feelserotoninstyle.css">
    <script src="../feelseroJS.js" defer></script>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Mağazam</h2>
        <table>
            <tr>
                <th>Paket Adı</th>
                <th>Fiyat</th>
                <th>Gün Sayısı</th>
                <th>Onay Durumu</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['package_name']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['days']; ?></td>
                    <td><?php echo $row['is_approved'] ? 'Onaylı' : 'Onay Bekliyor'; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
