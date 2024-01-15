<?php
session_start();
require('admin/include/db_config.php');
require('admin/include/essentials.php');

// Проверяем, был ли передан ID тура
if (isset($_GET['tour_id'])) {
    $tourId = $_GET['tour_id'];

    // Изменяем запрос для объединения таблиц
    $query = "SELECT t.*, i.tour_image 
              FROM admin_tours t 
              LEFT JOIN admin_tour_images i ON t.tour_id = i.tour_id 
              WHERE t.tour_id=?";
    $tourInfo = selectData($query, [$tourId], 'i');

    // Проверяем, найдена ли информация о туре
    if ($tourInfo && mysqli_num_rows($tourInfo) > 0) {
        $tourData = mysqli_fetch_assoc($tourInfo);
    } else {
        // Если тур не найден, можно выполнить перенаправление на страницу с сообщением об ошибке или другую страницу по вашему выбору.
        header("Location: error.php");
        exit();
    }
} else {
    // Если ID тура не был передан, выполните перенаправление на страницу с сообщением об ошибке или другую страницу по вашему выбору.
    header("Location: error.php");
    exit();
}
?>

<!DOCTYPE HTML>
<html lang="UA">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('include/links.php'); ?>
    <title>ОФОРМЛЕННЯ ТУРУ - DISCOVERING.UA</title>
    <style>
    body {
        background-color: #f8f9fa;
        font-family: 'Arial', sans-serif;
    }

    .tour-details h2 {
        color: #007bff;
        margin-bottom: 10px;
        text-align: center;
    }

    .tour-details hr {
        margin: 0 auto 20px;
        border: 1px solid #000;
        width: 85px;
    }

    .tour-details h3 {
        color: #007bff;
        margin-bottom: 10px;
    }

    .tour-details p {
        margin-bottom: 15px;
    }

    form {
        margin-top: 20px;
    }

    form label {
        display: block;
        margin: 10px 0;
    }

    form input {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    form button {
        background-color: #007bff;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        display: block;
        margin: 0 auto;
    }

    form button:hover {
        background-color: #0056b3;
    }
</style>

</head>

<body>
    <?php require('include/header.php'); ?>

    <div class="container">

        <div class="tour-details">
            <h2 class="fw-bold h-font">ОФОРМЛЕННЯ ТУРУ</h2>
            <hr>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h3 class="mb-3">НАЗВА ТУРУ: <?php echo $tourData['tour_name']; ?></h3>
                    <p>ОПИС ТУРУ: <?php echo $tourData['tour_desc']; ?></p>
                    <p>ЦІНА ТУРУ: <?php echo $tourData['tour_price']; ?>₴</p>

                    <!-- Форма для ввода данных о банковской карте -->
                    <form id="bookingForm">
                        <label for="cardNumber">Номер банковської карти:</label>
                        <input type="text" id="cardNumber" name="cardNumber" required>

                        <label for="expiryMonth">Місяць закінчення терміну дії:</label>
                        <input type="text" id="expiryMonth" name="expiryMonth" required>

                        <label for="expiryYear">Рік закінчення терміну дії:</label>
                        <input type="text" id="expiryYear" name="expiryYear" required>

                        <label for="cvcCode">CVC-код:</label>
                        <input type="text" id="cvcCode" name="cvcCode" required>

                        <button type="button" id="submitBooking">ОФОРМИТИ ЗАМОВЛЕННЯ</button>
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
                // Дополнительная валидация данных перед отправкой на сервер, если необходимо

                // Отправка данных на сервер с использованием AJAX
                const xhr = new XMLHttpRequest();
                const url = 'process-booking.php'; // Создайте файл process-booking.php для обработки данных о бронировании

                xhr.open('POST', url, true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                // Add the tour_id to the FormData object before sending
                const params = new URLSearchParams(new FormData(bookingForm));
                params.append('tour_id', <?php echo $tourId; ?>);

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        try {
                            const response = JSON.parse(xhr.responseText);

                            if (response.status === 'success') {
                                console.log(response.message);
                                // Дополнительные действия после успешного бронирования

                                // Clear the form after successful booking
                                bookingForm.reset();
                            } else if (response.status === 'error') {
                                console.error(response.message);
                            } else {
                                console.error('Непредвиденный ответ сервера');
                            }
                        } catch (e) {
                            console.error('Ошибка обработки ответа сервера');
                        }
                    }
                };

                xhr.onerror = function () {
                    console.error('Ошибка отправки запроса');
                };

                xhr.send(params);
            });

            // Add input validation for card number
            const cardNumberInput = document.getElementById('cardNumber');
            cardNumberInput.addEventListener('input', function () {
                const cardNumberValue = cardNumberInput.value.trim();

                // Check if card number is not more than 16 digits and matches the example
                if (cardNumberValue.length > 20) {
                    // Display an error message or take appropriate action
                    // For now, we'll log an error to the console
                    console.error('Invalid card number');
                }
            });
        });
    </script>
</body>

</html>
