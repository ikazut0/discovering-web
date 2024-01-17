<?php require('include/db_config.php'); require('include/essentials.php'); adminLoginCheck(); ?>

<!DOCTYPE HTML>
<html lang="UA">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>⚙️ НАЛАШТУВАННЯ</title>
    <?php require('include/links.php'); ?>
    <style>body{background-color:#f8f9fa;margin:0;font-family:'Arial',sans-serif;}#dashboard-menu{position:fixed;height:100%;z-index:11;}@media screen and (max-width:991px){#dashboard-menu{height:auto;width:100%;}#main-content{margin-top:60px;}.card{border-radius:15px;margin-bottom:20px;box-shadow:0 4px 8px rgba(0,0,0,0.1);}.btn{border-radius:10px;padding:10px 15px;font-size:14px;}.modal-content{border-radius:15px;}table{width:100%;border-collapse:collapse;margin-bottom:20px;}th,td{padding:12px;text-align:left;}.tooltip-inner{max-width:300px;padding:10px;color:#fff;text-align:left;background-color:#343a40;border-radius:8px;font-size:14px;box-shadow:0 2px 10px rgba(0,0,0,0.1);}.tooltip.bs-tooltip-top .arrow::before,.tooltip.bs-tooltip-bottom .arrow::before{border-top-color:#343a40;}.alert-center{position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:100;}.sticky-top{z-index:1000;}@media screen and (max-width:767px){th,td{font-size:12px;}}}</style>
</head>

<body class="bg-light"> <?php require('include/header.php'); ?>
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4" style="font-size: 13px;">⚙️ ОСНОВНІ НАЛАШТУВАННЯ ДЛЯ ПЛАТФОРМИ : </h3>
                
                <div class="card border-0 shadow mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0" style="font-size: 13px;">😊 ВВЕДІТЬ, БУДЬ ЛАСКА БАЖАНУ НАЗВУ : </h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#general-s"><i class="bi bi-pencil-square"></i></button>
                        </div>
                        <p class="card-text" id="site_title"></p>
                    </div>
                </div>
                
                <div class="modal fade" id="general-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="general_s_form">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="modal-title" style="font-size: 13px;">НАЛАШТУВАННЯ НАЗВИ ДЛЯ ВЕБ-САЙТУ : </h2>
                                </div>
                                
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold" style="font-size: 13px;">😊 ВВЕДІТЬ, БУДЬ ЛАСКА БАЖАНУ НАЗВУ ДЛЯ ЗМІНИ : </label>
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
                            <h5 class="card-title m-0" style="font-size: 13px;">😢 ВІДКЛЮЧИТИ ВЕБ-САЙТ?</h5>
                            <div class="form-check form-switch">
                                <form>
                                    <input onchange="upd_shutdown(this.value)" class="form-check-input" type="checkbox" id="shutdown-toggle">
                                </form>
                            </div>
                        </div>
                        <p class="card-text" style="font-size: 13px;">🛑 НЕ ОДИН КЛІЄНТ НЕ ЗМОЖЕ СКОРИСТАТИСЯ МОЖЛИВОСТЯМИ ВЕБ-САЙТУ!</p>
                    </div>
                </div>
                
                <div class="card border-0 shadow mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0" style="font-size: 13px;">НАЛАШТУВАННЯ КОНТАКТНОЇ ІНФОРМАЦІЇ : </h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#contacts-s"><i class="bi bi-pencil-square"></i></button>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                
                            <div class="mb-4">
                                <h6 class="card-subtitle mb-3 fw-bold" style="font-size: 13px;">🗺️ МІСЦЕ ЗНАХОДЖЕННЯ ГОЛОВНОГО ОФІСУ : </h6>
                                <p class="card-text" id="contact_address"></p>
                            </div>
                            
                            <div class="mb-4">
                                <h6 class="card-subtitle mb-3 fw-bold" style="font-size: 13px;">НОМЕРА ТЕЛЕФОНІВ ДЛЯ ЗВ'ЯЗКУ : </h6>
                                <p class="card-text mb-1"> ☎️ <span id="contact_phone_one"></span> </p>
                                <p class="card-text"> ☎️ <span id="contact_phone_two"></span> </p>
                            </div>
                            
                            <div class="mb-4">
                                <h6 class="card-subtitle mb-3 fw-bold" style="font-size: 13px;">📮 ЕЛЕКТРОННА ПОШТА ДЛЯ ЗВ'ЯЗКУ : </h6>
                                <p class="card-text" id="contact_email"></p>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="mb-4">
                                    <h6 class="card-subtitle mb-3 fw-bold" style="font-size: 13px;">🤳🏻 ПОСИЛАННЯ НА СОЦІАЛЬНІ МЕРЕЖІ : </h6>
                                    <p class="card-text mb-1"> <i class="bi bi-facebook me-1"></i> <span id="contact_facebook"></span> </p>
                                    <p class="card-text"> <i class="bi bi-instagram me-1"></i> <span id="contact_instagram"></span> </p>
                                </div>
                                
                                <div class="mb-4">
                                    <h6 class="card-subtitle mb-3 fw-bold" style="font-size: 13px;">📌 ВІДОБРАЖЕННЯ РОЗТАШУВАННЯ НА GMAPS : </h6>
                                    <iframe id="contact_iframe" class="border p-2 w-100"></iframe>
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
                                    <h2 class="modal-title">НАЛАШТУВАННЯ КОНТАКТНОЇ ІНФОРМАЦІЇ : </h2>
                                </div>
                                <div class="modal-body">
                                    <div class="container-fluid p-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                
                                            <div class="mb-3">
                                                <label class="form-label fw-bold" style="font-size: 13px;">🗺️ МІСЦЕ ЗНАХОДЖЕННЯ ГОЛОВНОГО ОФІСУ : </label>
                                                <input type="text" name="contact_address" id="contact_address_inp" class="form-control shadow-none" required>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label fw-bold" style="font-size: 13px;">НОМЕРА ТЕЛЕФОНІВ ДЛЯ ЗВ'ЯЗКУ : </label>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"> ☎️ </i></span>
                                                    <input type="text" name="contact_phone_one" id="contact_phone_one_inp" class="form-control shadow-none" required>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"> ☎️ </span>
                                                    <input type="text" name="contact_phone_two" id="contact_phone_two_inp" class="form-control shadow-none">
                                                </div> 
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label fw-bold" style="font-size: 13px;">📮 ЕЛЕКТРОННА ПОШТА ДЛЯ ЗВ'ЯЗКУ : </label>
                                                <input type="email" name="contact_email" id="contact_email_inp" class="form-control shadow-none" required>
                                            </div> </div>
                                            
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold" style="font-size: 13px;">🤳🏻 ПОСИЛАННЯ НА СОЦІАЛЬНІ МЕРЕЖІ : </label>
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
                                                    <label class="form-label fw-bold" style="font-size: 13px;">📌 ВІДОБРАЖЕННЯ РОЗТАШУВАННЯ НА GMAPS : </label>
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
                            <h5 class="card-title m-0" style="font-size: 13px;">🖼️ ЗАВАНТАЖЕННЯ КАРТИНКИ САЙТУ : </h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#carousel-s"><i class="bi bi-plus-square"></i></button>
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
                                    <h5 class="modal-title" style="font-size: 13px;">ЗАВАНТАЖТЕ КАРТИНКУ ДЛЯ ВІДОБРАЖЕННЯ : </h5>
                                </div>
                                
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold" style="font-size: 13px;">ВИБЕРІТЬ НЕОБХІДНУ КАРТИНКУ : </label>
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <?php require('include/scripts.php'); ?>
    
    <script src="scripts/settings-admin.js"></script>
    <script src="scripts/greeting-admin.js"></script>

</body> </html>