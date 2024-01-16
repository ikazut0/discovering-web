<?php require('admin/include/db_config.php'); require('admin/include/essentials.php'); session_start();

$userLoggedIn = isset($_SESSION['user_email']);

if ($userLoggedIn && isset($_POST['tour_id'])) {
    $tour_id = $_POST['tour_id'];
    $user_email = $_SESSION['user_email'];

    $user_info = getUserInfoByEmail($user_email);

    if (!$user_info) {
        echo json_encode(['status' => 'error', 'message' => 'КОРИСТУВАЧ НЕ ЗНАЙДЕНИЙ!']); exit;
    }

    $user_id = $user_info['user_id'];

    $checkQuery = "SELECT * FROM user_favorites WHERE user_id=? AND tour_id=?";
    $checkValues = [$user_id, $tour_id];
    $checkResult = selectData($checkQuery, $checkValues, 'ii');

    if (mysqli_num_rows($checkResult) == 0) {
        $insertQuery = "INSERT INTO user_favorites (user_id, tour_id) VALUES (?, ?)";
        $insertValues = [$user_id, $tour_id];
        $insertResult = insert($insertQuery, $insertValues, 'ii');

        if ($insertResult) {
            echo json_encode(['status' => 'success', 'message' => 'ТУР ДОДАНИЙ ДО ОБРАНОГО!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ПОМИЛКА ДОДАВАННЯ ТУРУ ДО ВИБРАНОГО!']);
        }
    } else {
        $deleteQuery = "DELETE FROM user_favorites WHERE user_id=? AND tour_id=?";
        $deleteValues = [$user_id, $tour_id];
        $deleteResult = delete($deleteQuery, $deleteValues, 'ii');

        if ($deleteResult) {
            echo json_encode(['status' => 'success', 'message' => 'ТУР ВИДАЛЕНО З ВИБРАНОГО!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ПОМИЛКА ВИДАЛЕННЯ З ВИБРАНОГО!']);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'КОРИСТУВАЧ НЕ ВВІЙШОВ У СИСТЕМУ!']);
}

?>