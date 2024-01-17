<?php require('include/essentials.php'); require('include/db_config.php'); ?>

<?php
session_start();
if ((isset($_SESSION['adminLoginCheck']) && $_SESSION['adminLoginCheck'] == true)) {
    adminHome('settings.php');
}
?>

<!DOCTYPE HTML>
<html lang="UA">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <title>aPanel - АВТОРИЗАЦІЯ</title>
    <?php require('include/links.php'); ?>
    <style>div.login-form{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:400px;}.alert-center-top{position:fixed;top:10%;left:50%;transform:translateX(-50%);}</style>
</head>

<body class="bg-light">
    <div class="login-form text-center rounded bg-white shadow overflow-hidden">
        <form method="POST">
            <h4 id="greeting-admin" class="bg-dark text-white py-3">aPanel</h4>
            <div class="p-4">
                <div class="mb-3">
                    <input name="admin_name" required type="text" class="form-control shadow-none text-center" placeholder="ВКАЖІТЬ ЛОГІН АДМІНІСТРАТОРА">
                </div>
                <div class="mb-4">
                    <input name="admin_password" required type="password" class="form-control shadow-none text-center" placeholder="ВКАЖІТЬ ПАРОЛЬ АДМІНІСТРАТОРА">
                </div>
                <button name="admin_login" type="submit" class="btn text-white custom-bg shadow-none">АВТОРИЗУВАТИСЯ</button>
            </div>
        </form>
    </div>

    <?php
    if (isset($_POST['admin_login'])) {
        $frm_data = filtrationData($_POST);
        $query = "SELECT * FROM `admin_info` WHERE `admin_name`=? AND `admin_password`=?";
        $values = [$frm_data['admin_name'], $frm_data['admin_password']];
        $result = selectData($query, $values, "ss");
        if ($result->num_rows == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['adminLoginCheck'] = true;
            $_SESSION['adminID'] = $row['admin_id'];
            adminHome('settings.php');
        } else {
            echo '<div id="myAlert" class="alert alert-danger alert-center-top alert-dismissible fade show text-center position-fixed" role="alert">';
            echo '<strong>НЕ ПРАВИЛЬНИЙ ЛОГІН, АБО ПАРОЛЬ! ЛОГІН АДМІНІСТРАТОРА, АБО ПАРОЛЬ ЩО ВИ ВКАЗАЛИ - НЕ ВІРНІ 🥹. СПРОБУЙТЕ ЩЕ РАЗ.</strong>';
            echo '</div>';
            echo '<script>';
            echo 'setTimeout(function() {';
            echo '  var myAlert = document.getElementById("myAlert");';
            echo '  myAlert.style.display = "none";';
            echo '}, 4500);';
            echo '</script>';
        }
    }
    ?>

    <a class="support-ukraine" target="_blank" rel="nofollow noopener">
        <div class="support-ukraine__flag" role="img" aria-label="Flag of Ukraine">
            <div class="support-ukraine__flag__blue"></div>
            <div class="support-ukraine__flag__yellow"></div>
        </div>
        <div class="support-ukraine__label"> ПРАЦЮЄМО ЗА РАДИ ПЕРЕМОГИ! СЛАВА УКРАЇНІ!
        </div>
    </a>

    <?php require('include/scripts.php'); ?>

    <script src="scripts/greeting-admin.js"></script>

</body> </html>