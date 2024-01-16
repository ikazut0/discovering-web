<?php require('admin/include/db_config.php'); require('admin/include/essentials.php'); session_start();

if (isset($_GET['tour_id'])) {
    $tourId = $_GET['tour_id'];

    $query = "SELECT t.*, i.tour_image 
              FROM admin_tours t 
              LEFT JOIN admin_tour_images i ON t.tour_id = i.tour_id 
              WHERE t.tour_id=?";
    $tourInfo = selectData($query, [$tourId], 'i');

    if ($tourInfo && mysqli_num_rows($tourInfo) > 0) {
        $tourData = mysqli_fetch_assoc($tourInfo);
    } else {
        header("Location: error.php");
        exit();
    }
} else {
    header("Location: error.php");
    exit();
}

$query = "SELECT `site_shutdown` FROM `admin_settings` WHERE `settings_id`=?";
$values = [1];

$mysqli = new mysqli("localhost", "root", "", "tourdb");

if ($mysqli->connect_error) {
    die("ПІДКЛЮЧЕННЯ НЕ ВДАЛОСЯ : " . $mysqli->connect_error);
}

$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', ...$values);

$stmt->execute();

$result = $stmt->get_result()->fetch_assoc();

$stmt->close();
$mysqli->close();

if ($result && isset($result['site_shutdown']) && $result['site_shutdown'] == 1) {
    echo '<div style="display: flex; justify-content: center; align-items: center; height: 100vh;">';
    echo '<img src="https://media.giphy.com/avatars/404academy/kGwR3uDrUKPI.gif" style="max-width: 100%; max-height: 100%;"/>';
    echo '</div>'; exit;
}

?>

<!DOCTYPE HTML>
<HTML lang="UA">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('include/links.php'); ?>
    <link rel="shortcut icon" href="https://cdn.icon-icons.com/icons2/1928/PNG/512/iconfinder-compass-direction-maps-holiday-vacation-icon-4602027_122100.png" type="image/x-icon">
    <title>DISCOVERING.UA</title>
    <style> body { background-color: #f8f9fa; font-family: 'Arial', sans-serif; } .tour-details h2 { color: #007bff; margin-bottom: 10px; text-align: center; } .tour-details hr { margin: 0 auto 20px; border: 1px solid #000; width: 85px; } .tour-details h3 { color: #007bff; margin-bottom: 10px; } .tour-details p { margin-bottom: 15px; } form { margin-top: 20px; } form label { display: block; margin: 10px 0; } form input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; } form button { background-color: #007bff; color: #fff; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; display: block; margin: 0 auto; } form button:hover { background-color: #0056b3; } </style>
</head>

<body> <?php require('include/header.php'); ?>
    <div class="container">
        <div class="tour-details">
            <h2 class="fw-bold h-font">ОФОРМЛЕННЯ ТУРУ</h2>
            <hr>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h3 class="mb-3 text-center">НАЗВА : <?php echo $tourData['tour_name']; ?></h3>
                    <p class="text-center">ОГОЛОШЕНА ВАРТІСТЬ ТУРУ : <?php echo $tourData['tour_price']; ?> ₴</p>
                    <p>ОПИС ТУРУ : <?php echo $tourData['tour_desc']; ?></p>
                    <form id="bookingForm">
                        <label for="cardNumber">НОМЕР БАНКІВСЬКОЇ КАРТИ : </label>
                        <input type="text" id="cardNumber" name="cardNumber" required>

                        <label for="expiryMonth">МІСЯЦЬ ЗАКІНЧЕННЯ ТЕРМІНУ ДІЇ КАРТКИ : </label>
                        <input type="text" id="expiryMonth" name="expiryMonth" required>

                        <label for="expiryYear">РІК ЗАКІНЧЕННЯ ТЕРМІНУ ДІЇ КАРТКИ : </label>
                        <input type="text" id="expiryYear" name="expiryYear" required>

                        <label for="cvcCode">ВВЕДІТЬ CVC-КОД ДО КАРТКИ : </label>
                        <input type="text" id="cvcCode" name="cvcCode" required>

                        <button type="button" id="submitBooking">ОФОРМИТИ ДАНИЙ ТУР</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require('include/scripts-library.php'); ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const submitButton = document.getElementById('submitBooking');
            const bookingForm = document.getElementById('bookingForm');

            submitButton.addEventListener('click', function () {
                const xhr = new XMLHttpRequest();
                const url = 'process-booking.php';

                xhr.open('POST', url, true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                const params = new URLSearchParams(new FormData(bookingForm));
                params.append('tour_id', <?php echo $tourId; ?>);

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        try {
                            const response = JSON.parse(xhr.responseText);

                            if (response.status === 'success') {
                                console.log(response.message);
                                bookingForm.reset();
                            } else if (response.status === 'error') {
                                console.error(response.message);
                            } else {
                                console.error('НЕПЕРЕДБАЧЕНА ВІДПОВІДЬ СЕРВЕРА!');
                            }
                        } catch (e) {
                            console.error('ПОМИЛКА ОБРОБКИ ВІДПОВІДІ СЕРВЕРА!');
                        }
                    }
                };

                xhr.onerror = function () {
                    console.error('ПОМИЛКА НАДСИЛАННЯ ЗАПИТУ!');
                };

                xhr.send(params);
            });

            const cardNumberInput = document.getElementById('cardNumber');
            cardNumberInput.addEventListener('input', function () {
                const cardNumberValue = cardNumberInput.value.trim();

                if (cardNumberValue.length > 20) {
                    console.error('НЕДІЙСНИЙ НОМЕР КАРТКИ!');
                }
            });
        });
    </script>
    
</body> </html>