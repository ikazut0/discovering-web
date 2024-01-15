<?php
include 'admin/include/db_config.php';
include 'admin/include/essentials.php';

if(isset($_POST['registerBtn'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone-num'];
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];
    $dob = $_POST['dob'];
    $password = password_hash($_POST['pass'], PASSWORD_DEFAULT);

    $checkEmailQuery = "SELECT * FROM `user_info` WHERE `user_email`=?";
    $checkEmailValues = [$email];
    $checkEmailResult = mysqli_fetch_assoc(selectData($checkEmailQuery, $checkEmailValues, 's'));

    if (!$checkEmailResult) {
        $registerQuery = "INSERT INTO `user_info` (`user_name`, `user_email`, `user_phone`, `user_address`, `user_pincode`, `user_dob`, `user_password`) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $registerValues = [$name, $email, $phone, $address, $pincode, $dob, $password];

        if (insert($registerQuery, $registerValues, 'ssssiss')) {
            session_start();
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;

            header("Location: index.php");
            exit();
        } else {
            alertHeader("Registration failed. Please try again.");
        }
    } else {
        alertHeader("Email is already registered. Please use a different email.");
    }
}
?>
