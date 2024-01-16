<?php require('admin/include/db_config.php'); require('admin/include/essentials.php'); session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_email'])) {
        echo json_encode(['status' => 'error', 'message' => 'КОРИСТУВАЧ НЕ АВТОРИЗОВАНИЙ!']);
        exit;
    }

    $userEmail = $_SESSION['user_email'];
    $tourId = isset($_POST['tour_id']) ? $_POST['tour_id'] : null;

    if ($tourId !== null) {
        $cardNumber = $_POST['cardNumber'];
        $expiryMonth = $_POST['expiryMonth'];
        $expiryYear = $_POST['expiryYear'];
        $cvcCode = $_POST['cvcCode'];

        if (empty($cardNumber) || empty($expiryMonth) || empty($expiryYear) || empty($cvcCode)) {
            echo json_encode(['status' => 'error', 'message' => 'УСІ ПОЛЯ МАЮТЬ БУТИ ЗАПОВНЕНІ!']);
            exit;
        }

        if (!is_numeric($cardNumber) || !is_numeric($expiryMonth) || !is_numeric($expiryYear) || !is_numeric($cvcCode)) {
            echo json_encode(['status' => 'error', 'message' => 'НЕКОРЕКТНІ ДАНІ ПРО БАНКІВСЬКУ КАРТКУ!']);
            exit;
        }

        $userInfo = getUserInfoByEmail($userEmail);

        if (!$userInfo) {
            echo json_encode(['status' => 'error', 'message' => 'ІНФОРМАЦІЯ ПРО КОРИСТУВАЧА НЕ ЗНАЙДЕНА!']);
            exit;
        }

        $query = "INSERT INTO user_booking_info (user_id, tour_id, card_number, expiry_month, expiry_year, cvc_code) VALUES (?, ?, ?, ?, ?, ?)";
        $values = [$userInfo['user_id'], $tourId, $cardNumber, $expiryMonth, $expiryYear, $cvcCode];
        $result = insert($query, $values, 'iisiii');

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'БРОНЮВАННЯ УСПІШНО ОФОРМЛЕНО!']);
            exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ПОМИЛКА ЗБЕРЕЖЕННЯ ДАНИХ ПРО БРОНЮВАННЯ!']);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ІДЕНТИФІКАТОР ТУРУ ВІДСУТНІЙ!']);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'НЕПРИПУСТИМИЙ МЕТОД ЗАПИТУ!']);
    exit;
}
?>