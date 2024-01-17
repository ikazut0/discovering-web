<?php require "include/db_config.php"; require "include/essentials.php"; adminLoginCheck(); ?>

<!DOCTYPE HTML>
<html lang="UA">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>⚙️ НАЛАШТУВАННЯ</title>
    <?php require "include/links.php";?>
    <style>body{background-color:#f8f9fa;margin:0;font-family:'Arial',sans-serif;}#dashboard-menu{position:fixed;height:100%;z-index:11;}@media screen and (max-width:991px){#dashboard-menu{height:auto;width:100%;}#main-content{margin-top:60px;}.card{border-radius:15px;margin-bottom:20px;box-shadow:0 4px 8px rgba(0,0,0,0.1);}.btn{border-radius:10px;padding:10px 15px;font-size:14px;}.modal-content{border-radius:15px;}table{width:100%;border-collapse:collapse;margin-bottom:20px;}th,td{padding:12px;text-align:left;}.tooltip-inner{max-width:300px;padding:10px;color:#fff;text-align:left;background-color:#343a40;border-radius:8px;font-size:14px;box-shadow:0 2px 10px rgba(0,0,0,0.1);}.tooltip.bs-tooltip-top .arrow::before,.tooltip.bs-tooltip-bottom .arrow::before{border-top-color:#343a40;}.alert-center{position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:100;}.sticky-top{z-index:1000;}@media screen and (max-width:767px){th,td{font-size:12px;}}}</style>
</head>

<body class="bg-light"> <?php require "include/header.php";?>
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4" style="font-size: 13px;">🚞 DISCOVERING.UA - КАТАЛОГ ДОСТУПНИХ ТУРІВ : </h3>
                
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="text-end mb-4">
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add_tour"><i class="bi bi-plus-square"></i></button>
                        </div>
                        
                        <div class="table-responsive-lg" style="max-height: 450px; overflow-y: scroll;">
                        <table class="table table-hover table-striped text-center">
                            <thead class="bg-dark text-light">
                                 <tr>
                                    <th scope="col" style="font-size: 13px;" class="text-center">#</th>
                                    <th scope="col" style="font-size: 13px;" class="text-center">НАЗВА : </th>
                                    <th scope="col" style="font-size: 13px;" class="text-center">ЕКСКУРСОВОДІВ :</th>
                                    <th scope="col" style="font-size: 13px;" class="text-center">ЛЮДЕЙ : </th>
                                    <th scope="col" style="font-size: 13px;" class="text-center">ЦІНА : </th>
                                    <th scope="col" style="font-size: 13px;" class="text-center">БІЛЕТІВ : </th>
                                    <th scope="col" style="font-size: 13px;" class="text-center">СТАТУС : </th>
                                    <th scope="col" style="font-size: 13px;" class="text-center">ОПЕРАЦІЇ : </th>
                                </tr>
                            </thead>
                            <tbody id="tour-data"> </tbody>
                        </table>
                    </div>
                    
                    <div class="modal fade" id="add_tour" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <form id="add_tour_form" autocomplete="off">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" style="font-size: 13px;">ПРОЦЕС ДОДАВАННЯ ТУРУ ДО КАТАЛОГУ : </h5>
                                    </div>
                                    
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold" style="font-size: 13px;">🧐 ВВЕДІТЬ, БУДЬ ЛАСКА НАЗВУ ТУРА : </label>
                                                <input type="text" name="tour_name" class="form-control shadow-none">
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold" style="font-size: 13px;">🏂🏻 ВВЕДІТЬ, БУДЬ ЛАСКА КІЛЬКІСТЬ ЕКСКУРСОВОДІВ : </label>
                                                <input type="number" min="1" name="tour_area" class="form-control shadow-none">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold" style="font-size: 13px;">💰 ВВЕДІТЬ, БУДЬ ЛАСКА ЦІНУ ДЛЯ ДАНОГО ТУРА : </label>
                                                <input type="text" min="1" name="tour_price" class="form-control shadow-none">
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold" style="font-size: 13px;">🎫 ВВЕДІТЬ, БУДЬ ЛАСКА КІЛЬКІСТЬ БІЛЕТІВ : </label>
                                                <input type="text" min="1" name="tour_quantity" class="form-control shadow-none">
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold" style="font-size: 13px;">👨🏻 ВВЕДІТЬ, БУДЬ ЛАСКА КІЛЬКІСТЬ ДОРОСЛИХ : </label>
                                                <input type="text" min="1" name="tour_adult" class="form-control shadow-none">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold" style="font-size: 13px;">🧒 ВВЕДІТЬ, БУДЬ ЛАСКА КІЛЬКІСТЬ ДІТЕЙ : </label>
                                                <input type="text" min="1" name="tour_children" class="form-control shadow-none">
                                            </div>
                                            
                                            <div class="col-12 mb-3">
                                                <label class="form-label fw-bold" style="font-size: 13px;">🔎 ОБЕРІТЬ, БУДЬ ЛАСКА КЛЮЧОВІ ОЗНАКИ : </label>
                                                <div class="row">
                                                        <?php
                                                        $res = selectAll(
                                                            "admin_feature"
                                                        );
                                                        while (
                                                            $opt = mysqli_fetch_assoc(
                                                                $res
                                                            )
                                                        ) {
                                                            echo "
                                                             <div class='col-md-3 mb-1'>
                                                              <label>
                                                               <input type='checkbox' name='tour_features' value='$opt[feature_id]' class='form-check-input shadow-none'>
                                                               $opt[feature_name]
                                                              <label>
                                                            </div>
                                                            ";
                                                        }
                                                        ?>
                                                        </div>
                                                    </div>

                                                <div class="col-12 mb-3">
                                                    <label class="form-label fw-bold" style="font-size: 13px;">✏️ НАПИШІТЬ, БУДЬ ЛАСКА ОПИС ДЛЯ ДАНОГО ТУРА : </label>
                                                    <textarea name="tour_desc" rows="4" class="form-control shadow-none"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="modal-footer">
                                            <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">ЗАКРИТИ</button>
                                            <button type="submit" class="btn custom-bg text-white shadow-none">ЗБЕРЕГТИ</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="modal fade" id="edit_tour" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <form id="edit_tour_form" autocomplete="off">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" style="font-size: 13px;">ОНОВЛЕННЯ ІНФОРМАЦІЇ ПРО ДАНИЙ ТУР : </h5>
                                        </div>
                                        
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold" style="font-size: 13px;">🧐 ВВЕДІТЬ, БУДЬ ЛАСКА НАЗВУ ТУРА :  </label>
                                                    <input type="text" name="tour_name" class="form-control shadow-none">
                                                </div>
                                                
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold" style="font-size: 13px;">🏂🏻 ВВЕДІТЬ, БУДЬ ЛАСКА КІЛЬКІСТЬ ЕКСКУРСОВОДІВ : </label>
                                                    <input type="number" min="1" name="tour_area" class="form-control shadow-none">
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold" style="font-size: 13px;">💰 ВВЕДІТЬ, БУДЬ ЛАСКА ЦІНУ ДЛЯ ДАНОГО ТУРА : </label>
                                                    <input type="text" min="1" name="tour_price" class="form-control shadow-none">
                                                </div>
                                                
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold" style="font-size: 13px;">🎫 ВВЕДІТЬ, БУДЬ ЛАСКА КІЛЬКІСТЬ БІЛЕТІВ : </label>
                                                    <input type="text" min="1" name="tour_quantity" class="form-control shadow-none">
                                                </div>
                                                
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold" style="font-size: 13px;">👨🏻 ВВЕДІТЬ, БУДЬ ЛАСКА КІЛЬКІСТЬ ДОРОСЛИХ : </label>
                                                    <input type="text" min="1" name="tour_adult" class="form-control shadow-none">
                                                </div>
                                                
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold" style="font-size: 13px;">🧒 ВВЕДІТЬ, БУДЬ ЛАСКА КІЛЬКІСТЬ ДІТЕЙ : </label>
                                                    <input type="text" min="1" name="tour_children" class="form-control shadow-none">
                                                </div>
                                                
                                                <div class="col-12 mb-3">
                                                    <label class="form-label fw-bold" style="font-size: 13px;">🔎 ОБЕРІТЬ, БУДЬ ЛАСКА КЛЮЧОВІ ОЗНАКИ : </label>
                                                    <div class="row">
                                                        <?php
                                                        $res = selectAll(
                                                            "admin_feature"
                                                        );
                                                        while (
                                                            $opt = mysqli_fetch_assoc(
                                                                $res
                                                            )
                                                        ) {
                                                            echo "
                                                             <div class='col-md-3 mb-1'>
                                                              <label>
                                                               <input type='checkbox' name='tour_features' value='$opt[feature_id]' class='form-check-input shadow-none'>
                                                               $opt[feature_name]
                                                              <label>
                                                            </div>
                                                            ";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <label class="form-label fw-bold" style="font-size: 13px;">✏️ НАПИШІТЬ, БУДЬ ЛАСКА ОПИС ДЛЯ ДАНОГО ТУРА : </label>
                                                    <textarea name="tour_desc" rows="4" class="form-control shadow-none"></textarea>
                                                </div>
                                                <input type="hidden" name="tour_id">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">ЗАКРИТИ</button>
                                            <button type="submit" class="btn custom-bg text-white shadow-none">ЗБЕРЕГТИ</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="modal fade" id="tour_image" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">🍃⛅️🏞️ : </h5>
                                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    
                                    <div class="modal-body">
                                        <div id="image-alert"></div>
                                        <div class="border-bottom border-3 pb-3 mb-3">
                                            <form id="add_image_form">
                                                <label class="form-label fw-bold" style="font-size: 13px;">ВИБЕРІТЬ, БУДЬ ЛАСКА ФОТО ДЛЯ ВІДОБРАЖЕННЯ : </label>
                                                <input type="file" name="image" class="form-control shadow-none mb-3" accept=".jpg, .png, .webp, .jpeg" required>
                                                <button class="btn custom-bg text-white shadow-none">⤵️ ЗАВАНТАЖИТИ</button>
                                                <input type="hidden" name="tour_id">
                                            </form>
                                        </div>
                                        
                                        <div class="table-responsive-md mx-auto text-center" style="max-height: 480px; overflow-y: scroll;">
                                        <table class="table">
                                            <thead class="sticky-top bg-dark text-light">
                                                <tr class="bg-dark text-light">
                                                    <th scope="col" width="35%" style="white-space: nowrap; text-align: center;" style="font-size: 13px;">КАРТИНКА</th>
                                                    <th scope="col" style="white-space: nowrap; text-align: center;" style="font-size: 13px;">ВІДОБРАЖАТИ КАРТИНКУ?</th>
                                                    <th class="text-center" scope="col" style="white-space: nowrap;" style="font-size: 13px;">ВИДАЛИТИ ФОТО?</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody id="tour-image-data"> </tbody>
                                        
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php require "include/scripts.php"; ?>
    
    <script src="scripts/greeting-admin.js"></script>
    <script src="scripts/tours-info.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
     document.addEventListener('DOMContentLoaded', function () {
        if (typeof bootstrap !== 'undefined') {
            var tooltips = new bootstrap.Tooltip(document.body, { selector: '[data-bs-toggle="tooltip"]' });
        } else { console.error('Bootstrap is not defined. Make sure you have included Bootstrap JS.'); }
        });
        
    </script> 

</body> </html>