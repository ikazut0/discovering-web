<?php
session_start();
require('admin/include/db_config.php');
require('admin/include/essentials.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверяем, авторизован ли пользователь
    if (!isset($_SESSION['user_email'])) {
        echo json_encode(['status' => 'error', 'message' => 'Пользователь не авторизован']);
        exit;
    }

    // Получаем данные из формы
    $userEmail = $_SESSION['user_email'];
    $tourId = isset($_POST['tour_id']) ? $_POST['tour_id'] : null;

    // Проверяем, передан ли ID тура
    if ($tourId !== null) {
        // Получаем остальные данные из формы
        $cardNumber = $_POST['cardNumber'];
        $expiryMonth = $_POST['expiryMonth'];
        $expiryYear = $_POST['expiryYear'];
        $cvcCode = $_POST['cvcCode'];

        // Дополнительная валидация данных
        if (empty($cardNumber) || empty($expiryMonth) || empty($expiryYear) || empty($cvcCode)) {
            echo json_encode(['status' => 'error', 'message' => 'Все поля должны быть заполнены']);
            exit;
        }

        // Дополнительная валидация данных о карте
        if (!is_numeric($cardNumber) || !is_numeric($expiryMonth) || !is_numeric($expiryYear) || !is_numeric($cvcCode)) {
            echo json_encode(['status' => 'error', 'message' => 'Некорректные данные о банковской карте']);
            exit;
        }

        // Получаем информацию о пользователе
        $userInfo = getUserInfoByEmail($userEmail);

        // Проверяем, существует ли информация о пользователе
        if (!$userInfo) {
            echo json_encode(['status' => 'error', 'message' => 'Информация о пользователе не найдена']);
            exit;
        }

        // Вставляем данные о бронировании в базу данных
        $query = "INSERT INTO user_booking_info (user_id, tour_id, card_number, expiry_month, expiry_year, cvc_code) VALUES (?, ?, ?, ?, ?, ?)";
        $values = [$userInfo['user_id'], $tourId, $cardNumber, $expiryMonth, $expiryYear, $cvcCode];
        $result = insert($query, $values, 'iisiii');

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Бронирование успешно оформлено']);
            exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Ошибка при сохранении данных о бронировании']);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Tour ID is missing']);
        exit;
    }
} else {
    // Если запрос не является POST-запросом, возвращаем ошибку
    echo json_encode(['status' => 'error', 'message' => 'Недопустимый метод запроса']);
    exit;
}
?>
