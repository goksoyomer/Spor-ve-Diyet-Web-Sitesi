<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../db.php';
include 'fsheaderMP.php';
include 'egitmenleftnavbar.php';
include '../authegitmen.php';
check_login();

function validate_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$user_id = $_SESSION['user_id'];
$profession = $_SESSION['profession'];
$is_approved = $_SESSION['is_approved'];
while (($is_approved == 0) && ($is_approved = $_SESSION['is_approved'])){
    echo "<div style='display: flex; justify-content: center; align-items: center; height: 100%; width: 100%;'>
          <div style='background-color: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); height: 15vw;    
            width: 60vw; max-width: 800px; padding: 0; box-sizing: border-box;'>
            <p style='padding: 8%; text-align: center; font-size: 30px; color: red;'>
            Eğitmenlik başvurunuz henüz sistemimiz tarafından onaylanmadı. Onaylı olmadığınız için mağazaya paket ekleyemezsiniz. Onaylanıp
            </p> 
          </div>
          </div>";
          
    exit();
}

$package_name = "";
$price = 0;
$days = 0;
$type = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['details'])) {
    $package_name = validate_input($_POST['package_name']);
    $price = validate_input($_POST['price']);
    $days = validate_input($_POST['days']);
    $type = validate_input($_POST['type']);

    $_SESSION['package_name'] = $package_name;
    $_SESSION['price'] = $price;
    $_SESSION['days'] = $days;
    $_SESSION['type'] = $type;
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['details'])) {
    $package_name = $_SESSION['package_name'];
    $price = $_SESSION['price'];
    $days = $_SESSION['days'];
    $type = $_SESSION['type'];
    $details = json_decode($_POST['details'], true);

    if ($type == "diet") {
        $sql = "INSERT INTO diet_packages (dietitian_id, package_name, price, days, is_approved) VALUES (?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issi", $user_id, $package_name, $price, $days);
    } else {
        $sql = "INSERT INTO training_packages (trainer_id, package_name, price, days, is_approved) VALUES (?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issi", $user_id, $package_name, $price, $days);
    }

    if ($stmt->execute() === TRUE) {
        $package_id = $stmt->insert_id;

        foreach ($details as $day => $day_details) {
            if ($type == "diet") {
                $sql_day = "INSERT INTO diet_days (package_id, day_number, total_calories) VALUES (?, ?, 0)";
                $stmt_day = $conn->prepare($sql_day);
                $stmt_day->bind_param("ii", $package_id, $day);
                if ($stmt_day->execute() === TRUE) {
                    $day_id = $stmt_day->insert_id;

                    foreach ($day_details['meals'] as $meal) {
                        $sql_meal = "INSERT INTO diet_meals (day_id, meal_name, amount, calories) VALUES (?, ?, ?, ?)";
                        $stmt_meal = $conn->prepare($sql_meal);
                        $stmt_meal->bind_param("isss", $day_id, $meal['name'], $meal['amount'], $meal['calories']);
                        $stmt_meal->execute();
                    }
                }
            } else {
                $sql_day = "INSERT INTO training_days (package_id, day_number, total_calories_burned) VALUES (?, ?, 0)";
                $stmt_day = $conn->prepare($sql_day);
                $stmt_day->bind_param("ii", $package_id, $day);
                if ($stmt_day->execute() === TRUE) {
                    $day_id = $stmt_day->insert_id;

                    foreach ($day_details['trainings'] as $training) {
                        $sql_training = "INSERT INTO training_exercises (day_id, exercise_name, repetitions, calories_burned) VALUES (?, ?, ?, ?)";
                        $stmt_training = $conn->prepare($sql_training);
                        $stmt_training->bind_param("isis", $day_id, $training['name'], $training['repetitions'], $training['calories']);
                        $stmt_training->execute();
                    }
                }
            }
        }

        if ($type == "diet") {
            $success_message = "<div style='text-align: center; color: green; margin-top: 20%;'>
                    <h2>Diyet ekleme işlemi başarılı.</h2>
                  </div>";
        } else {
            $success_message = "<div style='text-align: center; color: green; margin-top: 20%;'>
                    <h2>Antrenman ekleme işlemi başarılı, onaylandıktan sonra kullanıcı mağazasında listelenecek.</h2>
                  </div>";
        }

        // Form verilerini temizle
        unset($_SESSION['package_name']);
        unset($_SESSION['price']);
        unset($_SESSION['days']);
        unset($_SESSION['type']);
    } else {
        echo "<div style='text-align: center; color: red; margin-top: 20%;'>
                <h2>Hata: " . $stmt->error . "</h2>
              </div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../feelserotoninstyle.css">
    <script src="../feelseroJS.js" defer></script>
    <style>
        html{
            margin: 0;
            padding-top: 0px;
            width: 100%;
            height: 100%;
        }
        body {
            font-family: 'Ubuntu', sans-serif;
            background-color: #f0f0f0;
            display: flex;
            width: 100%;
            height: 100%;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin: 0;
            padding-top: 0px; /* Headerın altında kalmasını sağlamak için padding ekleyin */
        }
        /*.approveContainer {

        }*/

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
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
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

        .days-container {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .day {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            overflow-y: auto;
            max-height: 400px;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            width: 100%;
            align-items: center;
        }

        .form-container form {
            width: 100%;
        }

        .days-select {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .hidden {
            display: none;
        }

        .visible {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Paket Ekle</h2>
        <?php
            if ($success_message != "") {
                echo $success_message;
            }
        ?>
        <?php if (!isset($_POST['days']) || $success_message != ""): ?>
            <form action="addpackages.php" method="post">
                <div class="form-group">
                    <label for="package_name">Paket Adı:</label>
                    <input type="text" id="package_name" name="package_name" required>
                </div>
                <div class="form-group">
                    <label for="price">Fiyat:</label>
                    <input type="number" id="price" name="price" required>
                </div>
                <div class="form-group">
                    <label for="days">Gün Sayısı:</label>
                    <input type="number" id="days" name="days" required>
                </div>
                <?php if ($profession == 'Diyetisyen'): ?>
                    <input type="hidden" id="type" name="type" value="diet">
                <?php elseif ($profession == 'Antrenör'): ?>
                    <input type="hidden" id="type" name="type" value="training">
                <?php endif; ?>
                <button type="submit" class="button">Günleri Oluştur</button>
            </form>
        <?php else: ?>
            <div class="days-select">
                <label for="day-select">Gün Seç:</label>
                <select id="day-select" onchange="showDayForm(this.value)">
                    <option value="">Gün Seç</option>
                    <?php for ($i = 1; $i <= $days; $i++): ?>
                        <option value="<?php echo $i; ?>">Gün <?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-container">
                <form action="addpackages.php" method="post">
                    <?php for ($i = 1; $i <= $days; $i++): ?>
                        <div class="day hidden" id="day-<?php echo $i; ?>">
                            <?php if ($type === 'diet'): ?>
                                <h4>Gün <?php echo $i; ?></h4>
                                <div class="form-group">
                                    <label for="meal-count-<?php echo $i; ?>">Günlük Öğün Sayısı:</label>
                                    <input type="number" id="meal-count-<?php echo $i; ?>" name="details[day<?php echo $i; ?>][meal_count]" required>
                                </div>
                                <div class="form-group" id="meals-container-<?php echo $i; ?>"></div>
                                <button type="button" class="button" onclick="addMeal(<?php echo $i; ?>)">Öğün Ekle</button>
                            <?php elseif ($type === 'training'): ?>
                                <h4>Gün <?php echo $i; ?></h4>
                                <div class="form-group">
                                    <label for="training-count-<?php echo $i; ?>">Günlük Antrenman Sayısı:</label>
                                    <input type="number" id="training-count-<?php echo $i; ?>" name="details[day<?php echo $i; ?>][training_count]" required>
                                </div>
                                <div class="form-group" id="trainings-container-<?php echo $i; ?>"></div>
                                <button type="button" class="button" onclick="addTraining(<?php echo $i; ?>)">Antrenman Ekle</button>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
                    <input type="hidden" id="details" name="details">
                    <button type="submit" class="button" onclick="<?php echo $type === 'diet' ? 'collectDietDetails()' : 'collectTrainingDetails()'; ?>">Paket Ekle</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <script>
        const days = <?php echo $days; ?>;
        const type = "<?php echo $type; ?>";

        function showDayForm(day) {
            for (let i = 1; i <= days; i++) {
                document.getElementById(`day-${i}`).classList.add('hidden');
                document.getElementById(`day-${i}`).classList.remove('visible');
            }
            if(day) {
                document.getElementById(`day-${day}`).classList.remove('hidden');
                document.getElementById(`day-${day}`).classList.add('visible');
            }
        }

        if (type === "diet") {
            for (let i = 1; i <= days; i++) {
                const container = document.getElementById(`meals-container-${i}`);
                document.getElementById(`meal-count-${i}`).addEventListener('input', function () {
                    container.innerHTML = '';
                    const mealCount = this.value;
                    for (let j = 1; j <= mealCount; j++) {
                        const mealDiv = document.createElement('div');
                        mealDiv.className = 'meal';
                        mealDiv.innerHTML = `
                            <h5>Öğün ${j}</h5>
                            <div class="form-group">
                                <label for="meal-name-${i}-${j}">Yemek Adı:</label>
                                <input type="text" id="meal-name-${i}-${j}" name="details[day${i}][meals][meal${j}][name]" required>
                            </div>
                            <div class="form-group">
                                <label for="meal-amount-${i}-${j}">Miktar:</label>
                                <input type="text" id="meal-amount-${i}-${j}" name="details[day${i}][meals][meal${j}][amount]" required>
                            </div>
                            <div class="form-group">
                                <label for="meal-calories-${i}-${j}">Kalori:</label>
                                <input type="number" id="meal-calories-${i}-${j}" name="details[day${i}][meals][meal${j}][calories]" required>
                            </div>
                        `;
                        container.appendChild(mealDiv);
                    }
                });
            }
        } else if (type === "training") {
            for (let i = 1; i <= days; i++) {
                const container = document.getElementById(`trainings-container-${i}`);
                document.getElementById(`training-count-${i}`).addEventListener('input', function () {
                    container.innerHTML = '';
                    const trainingCount = this.value;
                    for (let j = 1; j <= trainingCount; j++) {
                        const trainingDiv = document.createElement('div');
                        trainingDiv.className = 'training';
                        trainingDiv.innerHTML = `
                            <h5>Antrenman ${j}</h5>
                            <div class="form-group">
                                <label for="training-name-${i}-${j}">Antrenman Adı:</label>
                                <input type="text" id="training-name-${i}-${j}" name="details[day${i}][trainings][training${j}][name]" required>
                            </div>
                            <div class="form-group">
                                <label for="training-repetitions-${i}-${j}">Set Sayısı:</label>
                                <input type="number" id="training-repetitions-${i}-${j}" name="details[day${i}][trainings][training${j}][repetitions]" required>
                            </div>
                            <div class="form-group">
                                <label for="training-calories-${i}-${j}">Yakılan Kalori:</label>
                                <input type="number" id="training-calories-${i}-${j}" name="details[day${i}][trainings][training${j}][calories]" required>
                            </div>
                        `;
                        container.appendChild(trainingDiv);
                    }
                });
            }
        }

        function collectDietDetails() {
            const details = {};
            for (let i = 1; i <= days; i++) {
                const mealCount = document.getElementById(`meal-count-${i}`).value;
                const meals = {};
                for (let j = 1; j <= mealCount; j++) {
                    const name = document.getElementById(`meal-name-${i}-${j}`).value;
                    const amount = document.getElementById(`meal-amount-${i}-${j}`).value;
                    const calories = document.getElementById(`meal-calories-${i}-${j}`).value;
                    meals[`meal${j}`] = {
                        name: name,
                        amount: amount,
                        calories: calories
                    };
                }
                details[`day${i}`] = {
                    meal_count: mealCount,
                    meals: meals
                };
            }
            document.getElementById('details').value = JSON.stringify(details);
        }

        function collectTrainingDetails() {
            const details = {};
            for (let i = 1; i <= days; i++) {
                const trainings = {};
                for (let j = 1; j <= document.getElementById(`trainings-container-${i}`).children.length; j++) {
                    const name = document.getElementById(`training-name-${i}-${j}`).value;
                    const repetitions = document.getElementById(`training-repetitions-${i}-${j}`).value;
                    const calories = document.getElementById(`training-calories-${i}-${j}`).value;
                    trainings[`training${j}`] = {
                        name: name,
                        repetitions: repetitions,
                        calories: calories
                    };
                }
                details[`day${i}`] = {
                    trainings: trainings
                };
            }
            document.getElementById('details').value = JSON.stringify(details);
        }
    </script>
</body>
</html>
