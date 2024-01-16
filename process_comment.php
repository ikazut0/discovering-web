<?php require('admin/include/db_config.php'); require('admin/include/essentials.php'); session_start();

if (!isset($_SESSION['user_email'])) {
    echo "КОРИСТУВАЧ НЕ АВТОРИЗОВАНИЙ!"; exit();
}

$userEmail = $_SESSION['user_email'];
$userInfo = getUserInfoByEmail($userEmail);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tour_id = $_POST['tour_id'];
    $user_id = isset($userInfo['user_id']) ? $userInfo['user_id'] : null;
    $comment_text = $_POST['comment_text'];

    if ($user_id === null) {
        echo "НЕДІЙСНИЙ : USER_ID"; exit();
    }

    if ($connection_info->connect_error) {
        die("ПІДКЛЮЧЕННЯ НЕ ВДАЛОСЯ : " . $connection_info->connect_error);
    }

    $check_user_query = $connection_info->query("SELECT * FROM `user_info` WHERE `user_id`='$user_id'");

    if ($check_user_query->num_rows > 0) {
        $insert_comment = $connection_info->query("INSERT INTO `tour_comments` (`tour_id`, `user_id`, `comment_text`) VALUES ('$tour_id', '$user_id', '$comment_text')");

        if ($insert_comment) {
            header("Location: tour-details.php?tour_id=$tour_id"); exit();
        } else {
            echo "ПОМИЛКА ДОДАВАННЯ КОМЕНТАРЯ : " . $connection_info->error;
        }
    } else {
        echo "НЕДІЙСНИЙ USER_ID : $user_id";
    }
    $connection_info->close();
}

?>