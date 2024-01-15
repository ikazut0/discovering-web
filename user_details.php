<?php require('admin/include/db_config.php'); require('admin/include/essentials.php'); session_start();

$userLoggedIn = isset($_SESSION['user_name']) && isset($_SESSION['user_email']);
$userID = $userLoggedIn ? ($_SESSION['user_id'] ?? null) : '';
$userName = $userLoggedIn ? $_SESSION['user_name'] : '';
$userEmail = $userLoggedIn ? $_SESSION['user_email'] : '';

function verifyOldPassword($email, $oldPassword)
{
    $getUserQuery = "SELECT * FROM `user_info` WHERE `user_email`=?";
    $getUserValues = [$email];
    $user = mysqli_fetch_assoc(selectData($getUserQuery, $getUserValues, 's'));

    if ($user && password_verify($oldPassword, $user['user_password'])) {
        return true;
    }
    return false;
}

function getUserDetailsByEmail($email)
{
    global $connection_info;

    $query = "SELECT * FROM `user_info` WHERE `user_email`=?";
    $stmt = mysqli_prepare($connection_info, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            $user = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            return $user;
        } else { 
            echo "<script> alert('ПОМИЛКА ВИКОНАННЯ ЗАПИТУ'); </script>" . mysqli_error($connection_info);
        }
    } else {
        echo "<script> alert('ПОМИЛКА ПІДГОТОВКИ ЗВІТУ'); </script>" . mysqli_error($connection_info);
    }
    return null; 
}

if ($userLoggedIn) {
    $user = getUserDetailsByEmail($userEmail);
}

if (isset($_POST['logoutBtn'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['changePasswordBtn'])) {
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
                session_destroy();
                header("Location: index.php?alert=password_updated");
                exit();
            } else {
                echo "<script> alert('НЕ ВДАЛОСЯ ЗМІНИТИ ПАРОЛЬ 😭'); </script>";
            }
        } else {
            echo "<script> alert('НОВИЙ ПАРОЛЬ ТА ПАРОЛЬ ПІДТВЕРДЖЕННЯ НЕ ЗБІГАЮТЬСЯ 🤔'); </script>";
        }
    } else {
        echo "<script> alert('СТАРИЙ ПАРОЛЬ НЕПРАВИЛЬНИЙ 🤦‍♂️'); </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="UA">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="https://cdn.icon-icons.com/icons2/1928/PNG/512/iconfinder-compass-direction-maps-holiday-vacation-icon-4602027_122100.png" type="image/x-icon">
    <?php require('include/links.php'); ?>
    <title>DISCOVERING.UA</title>
</head>

<body class="bg-light"> <?php require('include/header.php'); ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">

                <div class="card-body">
                    <?php if ($userLoggedIn) : ?>
                        <?php $user = getUserDetailsByEmail($userEmail); if ($user) : ?>
                            <form id="userDetailsForm" method="post" action="user_details.php">

                                <div class="mb-3">
                                    <label for="email" class="form-label">ЕЛЕКТРОННА ПОШТА : </label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['user_email']; ?>" readonly>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="phone-num" class="form-label">НОМЕР ТЕЛЕФОНУ : </label>
                                    <input type="phone-num" class="form-control" id="phone-num" name="phone-num" value="<?php echo $user['user_phone']; ?>" readonly>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="address" class="form-label">МІСЦЕ ПРОЖИВАННЯ : </label>
                                    <input type="address" class="form-control" id="address" name="address" value="<?php echo $user['user_address']; ?>" readonly>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="pincode" class="form-label">ПОШТОВИЙ ІНДЕКС : </label>
                                    <input type="pincode" class="form-control" id="pincode" name="pincode" value="<?php echo $user['user_pincode']; ?>" readonly>
                                </div>
                                
                                <hr> <h5 class="mb-3">ОНОВЛЕННЯ ПАРОЛЯ</h5>
                                
                                <div class="mb-3">
                                    <label for="oldPassword" class="form-label">ВВЕДІТЬ СТАРИЙ ПАРОЛЬ : </label>
                                    <input type="password" class="form-control" id="oldPassword" name="oldPassword">
                                </div>

                                <div class="mb-3">
                                    <label for="newPassword" class="form-label">ВВЕДІТЬ НОВИЙ ПАРОЛЬ : </label>
                                    <input type="password" class="form-control" id="newPassword" name="newPassword">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label">ПІДТВЕРДЬТЕ НОВИЙ ПАРОЛЬ : </label>
                                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary mb-3" name="changePasswordBtn">ОНОВИТИ ПАРОЛЬ</button>
                                </div> </form>
                                
                                <form method="post" action="user_details.php">
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-danger" name="logoutBtn">ВИЙТИ З ОБЛІКОВОГО ЗАПИСУ</button>
                                    </div>
                                </form>
                                
                                <?php else : ?>
                                    <p class="text-danger text-center">ПОМИЛКА ОТРИМАННЯ ІНФОРМАЦІЇ ПРО ВАС 🤦‍♂️</p>
                                <?php endif; ?>

                                <?php else : ?>
                                    <p class="text-warning text-center">ВИ НЕ АВТОРИЗОВАНІ В СИСТЕМІ 🤦‍♂️</p>
                                    <p class="text-info text-center">НАТИСНІТЬ КНОПКУ "АВТОРИЗАЦІЯ", ЩОБ ВВІЙТИ В ОБЛІКОВИЙ ЗАПИС</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
        <p class="col-md-4 mb-0 text-muted">© 2024 DISCOVERING.UA, Inc</p>
        <a class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none"><img class="bi me-2" width="70" height="32" src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ab/%D0%9B%D0%BE%D0%B3%D0%BE%D1%82%D0%B8%D0%BF_%D1%81%D0%B5%D1%80%D0%B2%D1%96%D1%81%D1%83_%D0%94%D1%96%D1%8F.svg/2560px-%D0%9B%D0%BE%D0%B3%D0%BE%D1%82%D0%B8%D0%BF_%D1%81%D0%B5%D1%80%D0%B2%D1%96%D1%81%D1%83_%D0%94%D1%96%D1%8F.svg.png"></a>
    </footer>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>$(document).ready(function(){$("#changePasswordBtn").click(function(){$.ajax({type:"POST",url:"user_details.php",data:$("#userDetailsForm").serialize(),dataType:"json",success:function(response){if(response.success){alert(response.message);}else{alert(response.message);}},error:function(xhr,status,error){console.error(xhr.responseText);}});});});</script>

</body> </html>