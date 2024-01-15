<?php
session_start();
require('admin/include/db_config.php');
require('admin/include/essentials.php');

// Проверка, если пользователь авторизован (можно использовать сессии или другие методы)
$userLoggedIn = isset($_SESSION['user_email']);

if ($userLoggedIn && isset($_POST['tour_id'])) {
    $tour_id = $_POST['tour_id'];
    $user_email = $_SESSION['user_email'];

    // Получить информацию о пользователе по email
    $user_info = getUserInfoByEmail($user_email);

    if (!$user_info) {
        // Если пользователь не найден, вернуть ошибку
        echo json_encode(['status' => 'error', 'message' => 'User not found.']);
        exit;
    }

    $user_id = $user_info['user_id'];

    // Проверяем, не добавлен ли тур уже в избранное
    $checkQuery = "SELECT * FROM user_favorites WHERE user_id=? AND tour_id=?";
    $checkValues = [$user_id, $tour_id];
    $checkResult = selectData($checkQuery, $checkValues, 'ii');

    if (mysqli_num_rows($checkResult) == 0) {
        // Тур еще не добавлен в избранное, добавляем
        $insertQuery = "INSERT INTO user_favorites (user_id, tour_id) VALUES (?, ?)";
        $insertValues = [$user_id, $tour_id];
        $insertResult = insert($insertQuery, $insertValues, 'ii');

        if ($insertResult) {
            // Успешное добавление в избранное
            echo json_encode(['status' => 'success', 'message' => 'Tour added to favorites successfully.']);
        } else {
            // Ошибка добавления
            echo json_encode(['status' => 'error', 'message' => 'Error adding to favorites.']);
        }
    } else {
        // Тур уже в избранном, удаляем
        $deleteQuery = "DELETE FROM user_favorites WHERE user_id=? AND tour_id=?";
        $deleteValues = [$user_id, $tour_id];
        $deleteResult = delete($deleteQuery, $deleteValues, 'ii');

        if ($deleteResult) {
            // Успешное удаление из избранного
            echo json_encode(['status' => 'success', 'message' => 'Tour removed from favorites successfully.']);
        } else {
            // Ошибка удаления
            echo json_encode(['status' => 'error', 'message' => 'Error removing from favorites.']);
        }
    }
} else {
    // Пользователь не авторизован
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
}
?>
