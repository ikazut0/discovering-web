<?php require('include/db_config.php'); require('include/essentials.php'); adminLoginCheck(); ?>

<!DOCTYPE HTML>
<html lang="UA">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>aPanel - НАЛАШТУВАННЯ</title>

    <?php require('include/links.php'); ?>

    <style>
        body {
            background-color: #f8f9fa;
        }

        #dashboard-menu {
            position: fixed;
            height: 100%;
            z-index: 11;
        }

        @media screen and (max-width: 991px) {
            #dashboard-menu {
                height: auto;
                width: 100%;
            }

            #main-content {
                margin-top: 60px;
            }
        }

        .card {
            border-radius: 15px;
        }

        .btn-custom {
            border-radius: 10px;
        }

        .modal-content {
            border-radius: 15px;
        }
    </style>
</head>

<body class="bg-light">

    <?php require('include/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">ОСНОВНІ НАЛАШТУВАННЯ ВЕБ-САЙТУ DISCOVERING.UA :</h3>

                <div class="card border-0 shadow mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">НАЛАШТУВАННЯ НАЗВИ САЙТУ [TITLE WEBSITE] :</h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#general-s">
                                <i class="bi bi-pencil-square"></i> РЕДАГУВАТИ | CHANGE
                            </button>
                        </div>
                        <h6 class="card-subtitle mb-1 fw-bold">НАЗВА САЙТУ :</h6>
                        <p class="card-text" id="site_title"></p>
                    </div>
                </div>

                <div class="modal fade" id="general-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="general_s_form">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="modal-title">НАЛАШТУВАННЯ НАЗВИ :</h2>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">ВВЕДІТЬ БАЖАНУ НАЗВУ САЙТУ ДЛЯ ЗМІНИ :</label>
                                        <input type="text" name="site_title" id="site_title_inp" class="form-control shadow-none" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" onclick="site_title.value = general_data.site_title" class="btn text-secondary shadow-none" data-bs-dismiss="modal">ЗАКРИТИ</button>
                                    <button type="submit" class="btn custom-bg text-white shadow-none">ЗБЕРЕГТИ</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">ЗАВЕРШИТИ РОБОТУ САЙТУ ДЛЯ ПРОВЕДЕННЯ ТЕХНІЧНИХ РОБІТ</h5>
                            <div class="form-check form-switch">
                                <form>
                                    <input onchange="upd_shutdown(this.value)" class="form-check-input" type="checkbox" id="shutdown-toggle">
                                </form>
                            </div>
                        </div>
                        <p class="card-text">🛑 НЕ ОДИН КЛІЄНТ НЕ ЗМОЖЕ ЗАБРОНЮВАТИ ТУР, ЯКЩО ВЕБ САЙТ БУДЕ ЗНАХОДИТЬСЯ У ВИМКНЕНОМУ РЕЖИМІ! ВИКОРИСТОВУВАТИ З ОБЕРЕЖНІСТЮ.</p>
                    </div>
                </div>

                <div class="card border-0 shadow mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">НАЛАШТУВАННЯ ОФОРМЛЕННЯ САЙТУ [CONTACT SECTION] :</h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#contacts-s">
                                <i class="bi bi-pencil-square"></i> РЕДАГУВАТИ | CHANGE
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <h6 class="card-subtitle mb-1 fw-bold">РОЗТАШУВАННЯ ГОЛОВНОГО ОФІСУ :</h6>
                                    <p class="card-text" id="contact_address"></p>
                                </div>
                                <div class="mb-4">
                                    <h6 class="card-subtitle mb-1 fw-bold">НОМЕРА ТЕЛЕФОНІВ ДЛЯ ЗВ'ЯЗКУ :</h6>
                                    <p class="card-text mb-1">
                                        <i class="bi bi-telephone-fill"></i>
                                        <span id="contact_phone_one"></span>
                                    </p>
                                    <p class="card-text">
                                        <i class="bi bi-telephone-fill"></i>
                                        <span id="contact_phone_two"></span>
                                    </p>
                                </div>
                                <div class="mb-4">
                                    <h6 class="card-subtitle mb-1 fw-bold">ЕЛЕКТРОННА АДРЕСА ДЛЯ ЗВ'ЯЗКУ :</h6>
                                    <p class="card-text" id="contact_email"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <h6 class="card-subtitle mb-1 fw-bold">ПОСИЛАННЯ НА СОЦІАЛЬНІ МЕРЕЖІ :</h6>
                                    <p class="card-text mb-1">
                                        <i class="bi bi-facebook me-1"></i>
                                        <span id="contact_facebook"></span>
                                    </p>
                                    <p class="card-text">
                                        <i class="bi bi-instagram me-1"></i>
                                        <span id="contact_instagram"></span>
                                    </p>
                                </div>
                                <div class="mb-4">
                                    <h6 class="card-subtitle mb-1 fw-bold">КАРТА САЙТУ [iFrame] :</h6>
                                    <iframe id="contact_iframe" class="border p-2 w-100" loading="lazy"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="contacts-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form id="contacts_s_form">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="modal-title">НАЛАШТУВАННЯ КОНТАКТНОЇ ІНФОРМАЦІЇ :</h2>
                                </div>
                                <div class="modal-body">
                                    <div class="container-fluid p-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">РОЗТАШУВАННЯ ГОЛОВНОГО ОФІСУ :</label>
                                                    <input type="text" name="contact_address" id="contact_address_inp" class="form-control shadow-none" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">НОМЕРА ТЕЛЕФОНІВ ДЛЯ ЗВ'ЯЗКУ :</label>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                                                        <input type="text" name="contact_phone_one" id="contact_phone_one_inp" class="form-control shadow-none" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                                                        <input type="text" name="contact_phone_two" id="contact_phone_two_inp" class="form-control shadow-none">
                                                    </div> 
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">ЕЛЕКТРОННА АДРЕСА ДЛЯ ЗВ'ЯЗКУ :</label>
                                                    <input type="email" name="contact_email" id="contact_email_inp" class="form-control shadow-none" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">ПОСИЛАННЯ НА СОЦІАЛЬНІ МЕРЕЖІ :</label>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="bi bi-facebook"></i></span>
                                                        <input type="text" name="contact_facebook" id="contact_facebook_inp" class="form-control shadow-none" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="bi bi-instagram"></i></span>
                                                        <input type="text" name="contact_instagram" id="contact_instagram_inp" class="form-control shadow-none" required>
                                                    </div> 
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">КАРТА САЙТУ [iFrame] :</label>
                                                    <input type="text" name="contact_iframe" id="contact_iframe_inp" class="form-control shadow-none" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" onclick="contacts_inp(contacts_data)" class="btn text-secondary shadow-none" data-bs-dismiss="modal">ЗАКРИТИ</button>
                                    <button type="submit" class="btn custom-bg text-white shadow-none">ЗБЕРЕГТИ</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> 
                
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">НАЛАШТУВАННЯ ОФОРМЛЕННЯ САЙТУ [SLIDER SECTION] :</h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#carousel-s">
                                <i class="bi bi-plus-square"></i> ЗАВАНТАЖИТИ ФОТО | UPLOAD PHOTO
                            </button>
                        </div>
                        
                        <div class="row" id="carousel-data">

                        </div>
                    </div>
                </div>

                <div class="modal fade" id="carousel-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="carousel_s_form">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">НАЛАШТУВАННЯ ОФОРМЛЕННЯ САЙТУ :</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">ВИБЕРІТЬ, ФОТО ДЛЯ ВІДОБРАЖЕННЯ [ДО 10MB] :</label>
                                        <input type="file" name="carousel_picture" id="carousel_picture_inp" class="form-control shadow-none" accept=".jpg, .png, .webp, .jpeg" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                <button type="button" onclick="carousel_picture.value=''" class="btn text-secondary shadow-none" data-bs-dismiss="modal">ЗАКРИТИ</button>
                                <button type="submit" class="btn custom-bg text-white shadow-none">ЗБЕРЕГТИ</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('include/scripts.php'); ?>

    <script src="scripts/settings-admin.js"></script>
    <script src="scripts/greeting-admin.js"></script>
    
</body>
</html>