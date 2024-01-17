<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userLoggedIn = isset($_SESSION['user_name']) && isset($_SESSION['user_email']);
$userID = $userLoggedIn ? ($_SESSION['user_id'] ?? null) : '';
$userName = $userLoggedIn ? $_SESSION['user_name'] : '';

$title_q = "SELECT * FROM `admin_settings` WHERE `settings_id`=?";
$values = [1];
$title_r = mysqli_fetch_assoc(selectData($title_q, $values, 'i'));

?>

<style>body{font-size:16px;}@media (max-width:768px){body{font-size:14px;}.navbar-brand{font-size:1.5rem;}.navbar-nav .nav-link{font-size:0.9rem;}.btn{font-size:0.9rem;}.modal-title{font-size:1.2rem;}.modal-body{font-size:0.9rem;}.modal-header,.modal-footer{padding:0.5rem;}}.navbar-nav .nav-link,.navbar-brand,.btn{white-space:nowrap;}header{display:flex;justify-content:space-between;align-items:center;padding-right:15px;}.navbar-collapse{margin-left:auto;}.navbar-nav{display:flex;}.container{font-size:80%;}</style>

<nav class="navbar navbar-expand-lg navbar-light bg-white px-lg-3 py-lg-2 shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand me-3 fw-bold fs-3 h-font" href="index.php"><?php echo $title_r['site_title'] ?></a>
        <button class="navbar-toggler shadow-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="ukraine-war.php">#WARINUA2022</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-1" href="tour-list.php">КАТАЛОГ ТУРІВ</a>
                </li>
                <?php if ($userLoggedIn): ?>
                    <li class="nav-item">
                        <a class="nav-link me-1" href="tour-favorite.php">ОБРАНІ ТУРИ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-1" href="user-book-tour-info.php">ПРИДБАНІ ТУРИ</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link me-1" href="online-tour-list.php">ВІРТУАЛЬНІ ТУРИ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-1" href="about.php">ПРО НАС</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-1" href="contact.php">КОНТАКТИ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-1" href="review-info.php">ЗАПИТАННЯ / ВІДГУКИ</a>
                </li>
            </ul>
            <div class="d-flex">
                <?php if ($userLoggedIn) { ?>
                    <a href="user_details.php" class="text-decoration-none">
                        <span class="me-lg-3 me-2" style="white-space: nowrap;">👋 Привіт, <?php echo $userName; ?></span>
                    </a>
                <?php } else { ?>
                    <button type="button" class="btn btn-outline-dark shadow-none me-2" data-bs-toggle="modal" data-bs-target="#loginModal"> АВТОРИЗАЦІЯ </button>
                    <button type="button" class="btn btn-outline-dark shadow-none" data-bs-toggle="modal" data-bs-target="#registerModal"> РЕЄСТРАЦІЯ </button>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>

<?php if (!$userLoggedIn) { ?>
    <div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="login.php">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="bi bi-person-fill fs-3 me-2"></i> АВТОРИЗАЦІЯ В ОБЛІКОВИЙ ЗАПИС
                        </h5>
                        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <i class="bi bi-envelope-at-fill"></i>
                            <label class="form-label">ЕЛ.ПОШТА :</label>
                            <input name="login_email" type="email" class="form-control shadow-none">
                        </div>
                        <div class="mb-4">
                            <i class="bi bi-key-fill"></i>
                            <label class="form-label">ПАРОЛЬ :</label>
                            <input name="login_pass" type="password" class="form-control shadow-none">
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <button type="submit" class="btn btn-dark shadow-none" name="loginBtn">АВТОРИЗАЦІЯ</button>
                            <a href="change-password.php" class="btn btn-outline-dark shadow-none">ЗАБУЛИ ПАРОЛЬ?</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="registerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="register-form" method="post" action="register.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="bi bi-person-bounding-box fs-3 me-2"></i>РЕЄСТРАЦІЯ ОБЛІКОВОГО ЗАПИСУ
                    </h5>
                    <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span class="badge bg-light text-dark nb-3 text-wrap lh-base">
                        <i class="bi bi-shield-fill-exclamation"></i> ПРИМІТКА: Важливо звернути увагу, що для успішної реєстрації на даному веб-сайті необхідно надавати свої реальні особисті дані, включаючи ім'я, прізвище, адресу електронної пошти та інші необхідні відомості. Дякуємо за розуміння та дотримання вимог реєстрації нашого веб-сайту.
                    </span>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6 ps-0 mb-3">
                                <label class="form-label">ІМ'Я :</label>
                                <input name="name" type="text" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 p-0 mb-3">
                                <label class="form-label">ЕЛ.ПОШТА :</label>
                                <input name="email" type="email" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 ps-0 mb-3">
                                <label class="form-label">НОМЕР ТЕЛЕФОНУ :</label>
                                <input name="phone-num" type="number" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 p-0 mb-3">
                                <label class="form-label">АДРЕСА :</label>
                                <textarea name="address" class="form-control shadow-none" rows="1" required></textarea>
                            </div>
                            <div class="col-md-6 ps-0 mb-3">
                                <label class="form-label">ПІН-КОД :</label>
                                <input name="pincode" type="number" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 p-0 mb-3">
                                <label class="form-label">ДАТА НАРОДЖЕННЯ :</label>
                                <input name="dob" type="date" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 ps-0 mb-3">
                                <label class="form-label">ПАРОЛЬ :</label>
                                <input name="pass" type="password" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 p-0 mb-3">
                                <label class="form-label">ПІДТВЕРДЖЕННЯ ПАРОЛЯ :</label>
                                <input name="cpass" type="password" class="form-control shadow-none" required>
                            </div>
                        </div>
                        <div class="text-center my-1">
                            <button type="submit" class="btn btn-dark shadow-none" name="registerBtn">РЕЄСТРАЦІЯ</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>