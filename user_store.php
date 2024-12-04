<?php
session_start();
include 'db.php';
include 'fsleftnavbar.php';
include 'fsheader.php';
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header("Location: login.php");
    exit();
}

function getPackages($conn, $type) {
    if ($type == "diet") {
        $sql = "SELECT dp.*, d.first_name, d.last_name FROM diet_packages dp 
                JOIN dietitians d ON dp.dietitian_id = d.dietitian_id 
                WHERE dp.is_approved = 1";
    } else {
        $sql = "SELECT tp.*, t.first_name, t.last_name FROM training_packages tp 
                JOIN trainers t ON tp.trainer_id = t.trainer_id 
                WHERE tp.is_approved = 1";
    }
    return $conn->query($sql);
}

function purchasePackage($conn, $user_id, $package_id, $type) {
    if ($type == "diet") {
        $sql = "INSERT INTO user_diet_purchases (user_id, package_id, is_completed) VALUES (?, ?, 0)";
    } else {
        $sql = "INSERT INTO user_training_purchases (user_id, package_id, is_completed) VALUES (?, ?, 0)";
    }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $package_id);
    return $stmt->execute();
}

$purchase_success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['purchase'])) {
    $package_id = $_POST['package_id'];
    $type = $_POST['type'];

    if (purchasePackage($conn, $user_id, $package_id, $type)) {
        $purchase_success = true;
    } else {
        echo "<script>alert('Hata: Paket satın alınamadı.');</script>";
    }
}

$diet_packages = getPackages($conn, "diet");
$training_packages = getPackages($conn, "training");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="feelserotoninstyle.css">
    <script src="feelseroJS.js" defer></script>
    <title>Mağaza</title>
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
            width: 80vw;
            max-width: 1000px;
            padding: 20px;
            box-sizing: border-box;
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .button-container {
            margin-bottom: 20px;
        }

        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }

        .button:hover {
            background-color: #45a049;
        }

        .packages {
            width: 100%;
        }

        .packages.hidden {
            display: none;
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

        .purchase-button {
            background-color: #008CBA;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .purchase-button:hover {
            background-color: #007bb5;
        }

        .search-bar {
            width: 100%;
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
        }

        .search-bar input {
            width: 50%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .alert {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            margin-bottom: 15px;
            text-align: center;
            border-radius: 5px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="button-container">
            <button class="button" onclick="showPackages('training')">Antrenman Paketleri</button>
            <button class="button" onclick="showPackages('diet')">Diyet Paketleri</button>
        </div>

        <div class="search-bar">
            <input type="text" id="search-input" onkeyup="searchPackages()" placeholder="Paket ara...">
        </div>

        <div class="alert" id="purchase-alert">Paket başarıyla satın alındı!</div>

        <div id="training" class="packages">
            <h2>Antrenman Paketleri</h2>
            <table id="training-table">
                <tr>
                    <th>Paket Adı</th>
                    <th>Fiyat</th>
                    <th>Gün Sayısı</th>
                    <th>Eğitmen</th>
                    <th>İşlemler</th>
                </tr>
                <?php while ($row = $training_packages->fetch_assoc()): ?>
                    <tr>
                        <form action="user_store.php" method="post">
                            <input type="hidden" name="package_id" value="<?php echo $row['package_id']; ?>">
                            <input type="hidden" name="type" value="training">
                            <td><?php echo $row['package_name']; ?></td>
                            <td><?php echo $row['price']; ?></td>
                            <td><?php echo $row['days']; ?></td>
                            <td><a href="egitmen_profile_public.php?id=<?php echo $row['trainer_id']; ?>&type=trainer"><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></a></td>
                            <td>
                                <button type="submit" name="purchase" class="purchase-button">Satın Al</button>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div id="diet" class="packages hidden">
            <h2>Diyet Paketleri</h2>
            <table id="diet-table">
                <tr>
                    <th>Paket Adı</th>
                    <th>Fiyat</th>
                    <th>Gün Sayısı</th>
                    <th>Eğitmen</th>
                    <th>İşlemler</th>
                </tr>
                <?php while ($row = $diet_packages->fetch_assoc()): ?>
                    <tr>
                        <form action="user_store.php" method="post">
                            <input type="hidden" name="package_id" value="<?php echo $row['package_id']; ?>">
                            <input type="hidden" name="type" value="diet">
                            <td><?php echo $row['package_name']; ?></td>
                            <td><?php echo $row['price']; ?></td>
                            <td><?php echo $row['days']; ?></td>
                            <td><a href="egitmen_profile_public.php?id=<?php echo $row['dietitian_id']; ?>&type=dietitian"><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></a></td>
                            <td>
                                <button type="submit" name="purchase" class="purchase-button">Satın Al</button>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

    <script>
        function showPackages(type) {
            document.querySelectorAll('.packages').forEach(package => {
                package.classList.add('hidden');
            });
            document.getElementById(type).classList.remove('hidden');
        }

        function searchPackages() {
            const input = document.getElementById('search-input').value.toLowerCase();
            document.querySelectorAll('.packages:not(.hidden) table tr').forEach(row => {
                const packageName = row.cells[0]?.textContent.toLowerCase();
                if (packageName) {
                    row.style.display = packageName.includes(input) ? '' : 'none';
                }
            });
        }

        // Varsayılan olarak antrenman paketlerini göster
        document.addEventListener('DOMContentLoaded', () => {
            showPackages('training');
            <?php if ($purchase_success): ?>
                const alert = document.getElementById('purchase-alert');
                alert.style.display = 'block';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 3000);
            <?php endif; ?>
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>

