<?php
include 'admin/include/db_config.php';
include 'admin/include/essentials.php';

function verifyOldPassword($email, $oldPassword) {
    $getUserQuery = "SELECT * FROM `user_info` WHERE `user_email`=?";
    $getUserValues = [$email];
    $user = mysqli_fetch_assoc(selectData($getUserQuery, $getUserValues, 's'));

    if ($user && password_verify($oldPassword, $user['user_password'])) {
        return true;
    }

    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if (verifyOldPassword($email, $oldPassword)) {
        if ($newPassword === $confirmPassword) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $updatePasswordQuery = "UPDATE `user_info` SET `user_password`=? WHERE `user_email`=?";
            $updatePasswordValues = [$hashedPassword, $email];

            if (updateData($updatePasswordQuery, $updatePasswordValues, 'ss')) {
                echo json_encode(['success' => true, 'message' => 'Password change successful!']);
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'Password change failed. Please try again.']);
                exit();
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'New password and confirm password do not match.']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Old password is incorrect.']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Change</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">ИЗМЕНЕНИЕ ПАРОЛЯ</h4>
                    </div>
                    <div class="card-body">
                        <div id="changePasswordAlert"></div>
                        <form method="post" id="changePasswordForm">
                            <div class="form-group">
                                <label for="email">Електронна пошта:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="oldPassword">Старий пароль:</label>
                                <input type="password" class="form-control" id="oldPassword" name="oldPassword" required>
                            </div>
                            <div class="form-group">
                                <label for="newPassword">Новий пароль:</label>
                                <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword">Підтвердження паролю:</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                            </div>
                            <button type="button" class="btn btn-primary" id="changePasswordBtn">Змінити пароль</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <script>
        $(document).ready(function () {
            $("#changePasswordBtn").click(function () {
                $.ajax({
                    type: "POST",
                    url: "change-password.php",
                    data: $("#changePasswordForm").serialize(),
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            $("#changePasswordAlert").html('<div class="alert alert-success" role="alert">' + response.message + '</div>');
                        } else {
                            $("#changePasswordAlert").html('<div class="alert alert-danger" role="alert">' + response.message + '</div>');
                        }
                    }
                });
            });
        });
    </script>

</body>

</html>