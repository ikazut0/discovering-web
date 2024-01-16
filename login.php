<?php include 'admin/include/db_config.php'; include 'admin/include/essentials.php';

if (isset($_POST['loginBtn'])) {
    $loginEmail = $_POST['login_email'];
    $loginPassword = $_POST['login_pass'];

    $checkUserQuery = "SELECT * FROM `user_info` WHERE `user_email`=?";
    $checkUserValues = [$loginEmail];
    $userData = mysqli_fetch_assoc(selectData($checkUserQuery, $checkUserValues, 's'));

    if ($userData && password_verify($loginPassword, $userData['user_password'])) {
        session_start();
        $_SESSION['user_name'] = $userData['user_name'];
        $_SESSION['user_email'] = $userData['user_email'];
        header("Location: index.php"); exit();
    } else {
        alertHeader("НЕВІРНА ЕЛЕКТРОННА ПОШТА АБО ПАРОЛЬ 😭");
    }
}
?>