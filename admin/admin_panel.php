<?php
session_start();
include '../db.php';

// Admin giriş kontrolü
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Paket onaylama ve reddetme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['approve']) || isset($_POST['reject']))) {
    $package_id = $_POST['package_id'];
    $type = $_POST['type'];

    if ($type == "diet") {
        if (isset($_POST['approve'])) {
            $sql = "UPDATE diet_packages SET is_approved = 1 WHERE package_id = ?";
        } else {
            $sql = "DELETE FROM diet_packages WHERE package_id = ?";
        }
    } else {
        if (isset($_POST['approve'])) {
            $sql = "UPDATE training_packages SET is_approved = 1 WHERE package_id = ?";
        } else {
            $sql = "DELETE FROM training_packages WHERE package_id = ?";
        }
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $package_id);

    if ($stmt->execute() === TRUE) {
        echo "<script>alert('Paket " . (isset($_POST['approve']) ? "onaylandı" : "reddedildi") . ".');</script>";
    } else {
        echo "<script>alert('Hata: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Diyetisyen onaylama ve reddetme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['approve_dietitian']) || isset($_POST['reject_dietitian']))) {
    $dietitian_id = $_POST['dietitian_id'];

    $sql = isset($_POST['approve_dietitian']) ? "UPDATE dietitians SET is_approved = 1 WHERE dietitian_id = ?" : "DELETE FROM dietitians WHERE dietitian_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $dietitian_id);

    if ($stmt->execute() === TRUE) {
        echo "<script>alert('Diyetisyen " . (isset($_POST['approve_dietitian']) ? "onaylandı" : "reddedildi") . ".');</script>";
    } else {
        echo "<script>alert('Hata: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Antrenör onaylama ve reddetme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['approve_trainer']) || isset($_POST['reject_trainer']))) {
    $trainer_id = $_POST['trainer_id'];

    $sql = isset($_POST['approve_trainer']) ? "UPDATE trainers SET is_approved = 1 WHERE trainer_id = ?" : "DELETE FROM trainers WHERE trainer_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $trainer_id);

    if ($stmt->execute() === TRUE) {
        echo "<script>alert('Antrenör " . (isset($_POST['approve_trainer']) ? "onaylandı" : "reddedildi") . ".');</script>";
    } else {
        echo "<script>alert('Hata: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Onay bekleyen diyet paketlerini, antrenman paketlerini, diyetisyenleri ve antrenörleri çekme
$sql_diet = "SELECT * FROM diet_packages WHERE is_approved = 0";
$sql_training = "SELECT * FROM training_packages WHERE is_approved = 0";
$sql_dietitians = "SELECT * FROM dietitians WHERE is_approved = 0";
$sql_trainers = "SELECT * FROM trainers WHERE is_approved = 0";

$result_diet = $conn->query($sql_diet);
$result_training = $conn->query($sql_training);
$result_dietitians = $conn->query($sql_dietitians);
$result_trainers = $conn->query($sql_trainers);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
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

        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        .button:hover {
            background-color: #45a049;
        }

        .reject-button {
            background-color: #f44336;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        .reject-button:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Onay Bekleyen Diyet Paketleri</h2>
        <table>
            <tr>
                <th>Paket Adı</th>
                <th>Fiyat</th>
                <th>Gün Sayısı</th>
                <th>İşlemler</th>
            </tr>
            <?php while ($row = $result_diet->fetch_assoc()): ?>
                <tr>
                    <form action="admin_panel.php" method="post">
                        <input type="hidden" name="package_id" value="<?php echo $row['package_id']; ?>">
                        <input type="hidden" name="type" value="diet">
                        <td><?php echo $row['package_name']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['days']; ?></td>
                        <td>
                            <button type="submit" name="approve" class="button">Onayla</button>
                            <button type="submit" name="reject" class="reject-button">Reddet</button>
                        </td>
                    </form>
                </tr>
            <?php endwhile; ?>
        </table>

        <h2>Onay Bekleyen Antrenman Paketleri</h2>
        <table>
            <tr>
                <th>Paket Adı</th>
                <th>Fiyat</th>
                <th>Gün Sayısı</th>
                <th>İşlemler</th>
            </tr>
            <?php while ($row = $result_training->fetch_assoc()): ?>
                <tr>
                    <form action="admin_panel.php" method="post">
                        <input type="hidden" name="package_id" value="<?php echo $row['package_id']; ?>">
                        <input type="hidden" name="type" value="training">
                        <td><?php echo $row['package_name']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['days']; ?></td>
                        <td>
                            <button type="submit" name="approve" class="button">Onayla</button>
                            <button type="submit" name="reject" class="reject-button">Reddet</button>
                        </td>
                    </form>
                </tr>
            <?php endwhile; ?>
        </table>

        <h2>Onay Bekleyen Diyetisyenler</h2>
        <table>
            <tr>
                <th>Adı</th>
                <th>Soyadı</th>
                <th>Email</th>
                <th>Kullanıcı Adı</th>
                <th>Üniversite</th>
                <th>İşlemler</th>
            </tr>
            <?php while ($row = $result_dietitians->fetch_assoc()): ?>
                <tr>
                    <form action="admin_panel.php" method="post">
                        <input type="hidden" name="dietitian_id" value="<?php echo $row['dietitian_id']; ?>">
                        <td><?php echo $row['first_name']; ?></td>
                        <td><?php echo $row['last_name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['university']; ?></td>
                        <td>
                            <button type="submit" name="approve_dietitian" class="button">Onayla</button>
                            <button type="submit" name="reject_dietitian" class="reject-button">Reddet</button>
                        </td>
                    </form>
                </tr>
            <?php endwhile; ?>
        </table>

        <h2>Onay Bekleyen Antrenörler</h2>
        <table>
            <tr>
                <th>Adı</th>
                <th>Soyadı</th>
                <th>Email</th>
                <th>Kullanıcı Adı</th>
                <th>Üniversite</th>
                <th>İşlemler</th>
            </tr>
            <?php while ($row = $result_trainers->fetch_assoc()): ?>
                <tr>
                    <form action="admin_panel.php" method="post">
                        <input type="hidden" name="trainer_id" value="<?php echo $row['trainer_id']; ?>">
                        <td><?php echo $row['first_name']; ?></td>
                        <td><?php echo $row['last_name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['university']; ?></td>
                        <td>
                            <button type="submit" name="approve_trainer" class="button">Onayla</button>
                            <button type="submit" name="reject_trainer" class="reject-button">Reddet</button>
                        </td>
                    </form>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
