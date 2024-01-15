<?php
require('admin/include/db_config.php');
require('admin/include/essentials.php');

session_start();

// Проверка, если пользователь авторизован (можно использовать сессии или другие методы)
$userLoggedIn = isset($_SESSION['user_email']);

if ($userLoggedIn && isset($_GET['action']) && isset($_GET['tour_id'])) {
    $action = $_GET['action'];
    $tour_id = $_GET['tour_id'];

    switch ($action) {
        case 'add':
            addToFavorites($tour_id);
            break;
        case 'remove':
            removeFromFavorites($tour_id);
            break;
        default:
            // Неизвестное действие
            break;
    }
}

// Функция добавления тура в избранное
function addToFavorites($tour_id)
{
    $user_email = $_SESSION['user_email'];

    // Проверяем, не добавлен ли тур уже в избранное
    $checkQuery = "SELECT * FROM user_favorites uf 
                   JOIN user_info u ON uf.user_id = u.user_id 
                   WHERE u.user_email=? AND uf.tour_id=?";
    $checkValues = [$user_email, $tour_id];
    $checkResult = selectData($checkQuery, $checkValues, 'si');

    if (mysqli_num_rows($checkResult) == 0) {
        // Тур еще не добавлен в избранное, добавляем
        $insertQuery = "INSERT INTO user_favorites (user_id, tour_id) 
                        VALUES ((SELECT user_id FROM user_info WHERE email=?), ?)";
        $insertValues = [$user_email, $tour_id];
        $insertResult = insertData($insertQuery, $insertValues, 'si');

        if ($insertResult) {
            // Успешное добавление в избранное
            header("Location: tour-favorite.php"); // Можете изменить перенаправление по необходимости
            exit();
        } else {
            // Ошибка добавления
            echo "Error adding to favorites.";
        }
    } else {
        // Тур уже в избранном
        echo "Tour already in favorites.";
    }
}

// Функция удаления тура из избранного
function removeFromFavorites($tour_id)
{
    $user_email = $_SESSION['user_email'];

    $deleteQuery = "DELETE FROM user_favorites uf 
                    USING users_info u 
                    WHERE uf.user_id = u.user_id 
                    AND u.user_email=? 
                    AND uf.tour_id=?";
    $deleteValues = [$user_email, $tour_id];
    $deleteResult = deleteData($deleteQuery, $deleteValues, 'si');

    if ($deleteResult) {
        // Успешное удаление из избранного
        header("Location: tour-favorite.php"); // Можете изменить перенаправление по необходимости
        exit();
    } else {
        // Ошибка удаления
        echo "Error removing from favorites.";
    }
}

// Получаем избранные туры пользователя
$user_email = $_SESSION['user_email'];
$favoritesQuery = "SELECT t.* FROM user_favorites uf 
                   JOIN admin_tours t ON uf.tour_id = t.tour_id 
                   JOIN user_info u ON uf.user_id = u.user_id 
                   WHERE u.user_email=?";
$favoritesValues = [$user_email];
$favoritesResult = selectData($favoritesQuery, $favoritesValues, 's');
?>


<!DOCTYPE HTML>
<html lang="UA">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('include/links.php'); ?>
    <title>DISCOVERING.UA - МАНДРУЙ УКРАЇНОЮ</title>
</head>

<body class="bg-light"> <?php require('include/header.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">ВАШІ УЛЮБЛЕНІ ТУРИ</h2>
        <hr class="mx-auto my-3" style="border: 1px solid #000; width: 85px;">
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 ps-4">
                <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
                    <div class="container-fluid flex-lg-column align-items-stretch">
                        <h4 class="mt-2">ФІЛЬТР ДОСТУПНИХ ТУРІВ :</h4>
                        <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterDropdown" aria-controls="navbarNav" aria-expanded="false">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="filterDropdown">
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="mb-3" style="font-size: 18px;">ПОШУК ДОСТУПНИХ ТУРІВ :</h5>
                                <label class="form-label">ОБЕРІТЬ ПОЧАТКОВУ ДАТУ :</label>
                                <input type="date" class="form-control shadow-none mb-3">
                                <label class="form-label">ОБЕРІТЬ КІНЦЕВУ ДАТУ :</label>
                                <input type="date" class="form-control shadow-none">
                            </div>
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="mb-3" style="font-size: 18px;">ОБЕРІТЬ КЛЮЧОВІ ОЗНАКИ ТУРА :</h5>
                                <div class="mb-2">
                                    <input type="checkbox" id="f1" class="form-check-input shadow-none me-1">
                                    <label class="form-check-label" for="f1">ІСТОРИЧНІ ПАМ'ЯТКИ</label>
                                </div>
                                <div class="mb-2">
                                    <input type="checkbox" id="f2" class="form-check-input shadow-none me-1">
                                    <label class="form-check-label" for="f2">КУЛЬТУРНІ ОБ'ЄКТИ</label>
                                </div>
                                <div class="mb-2">
                                    <input type="checkbox" id="f3" class="form-check-input shadow-none me-1">
                                    <label class="form-check-label" for="f3">АРХІТЕКТУРНІ ШЕДЕВРИ</label>
                                </div>
                                <div class="mb-2">
                                    <input type="checkbox" id="f4" class="form-check-input shadow-none me-1">
                                    <label class="form-check-label" for="f4">КУЛЬТУРНА РІЗНОМАНІТНІСТЬ</label>
                                </div>
                                <div class="mb-2">
                                    <input type="checkbox" id="f5" class="form-check-input shadow-none me-1">
                                    <label class="form-check-label" for="f5">СУЧАСНА АРХІТЕКТУРА</label>
                                </div>
                            </div>
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="mb-3" style="font-size: 18px;">КІЛЬКІСТЬ ЛЮДЕЙ :</h5>
                                <div class="d-flex">
                                    <div class="me-3">
                                        <label class="form-label">ДОРОСЛИХ</label>
                                        <input type="number" class="form-control shadow-none" min="1">
                                    </div>
                                    <div>
                                        <label class="form-label">ДІТЕЙ</label>
                                        <input type="number" class="form-control shadow-none" min="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="col-lg-9 col-md-12 px-4">
                <?php
                $tour_res = selectData("SELECT * FROM `admin_tours` WHERE `tour_status`=? AND `tour_removed`=?", [1, 0], 'ii');

                while ($tour_data = mysqli_fetch_assoc($favoritesResult)) {
                    $fea_q = selectData("SELECT f.feature_name FROM `admin_feature` f 
                    INNER JOIN `admin_tours_feature` tfea ON f.feature_id = tfea.feature_id
                    WHERE tfea.tour_id = ?", [$tour_data['tour_id']], 'i');
                    
                    $features_data = "";
                    
                    while ($fea_row = mysqli_fetch_assoc($fea_q)) {
                        $features_data .= "<span class='badge bg-light text-dark text-wrap me-1 mb-1'>$fea_row[feature_name]</span>";
                    }
                    
                    $tour_thumb = TOURS_IMG_PATH . "empty-image-alert.gif";
                    $thumb_q = mysqli_query($connection_info, "SELECT * FROM `admin_tour_images` 
                    WHERE `tour_id`='$tour_data[tour_id]' 
                    AND `tour_thumb`='1'");
                    
                    if(mysqli_num_rows($thumb_q) > 0) {
                        $thumb_res = mysqli_fetch_assoc($thumb_q);
                        $tour_thumb = TOURS_IMG_PATH.$thumb_res['tour_image'];
                    }
                
                    echo <<< data
                    <div class="card mb-4 border-0 shadow">
                        <div class="row g-0 p-3 align-items-center">
                            <div class="col-md-5 mb-lg-0 mb-md-0 mb-3">
                                <img src="$tour_thumb" class="img-fluid rounded">
                            </div>
                            <div class="col-md-5 px-lg-3 px-md-3 px-0">
                                <h5 class="mb-3">НАЗВА ТУРУ : $tour_data[tour_name]</h5>
                                <div class="features mb-3">
                                    <h6 class="mb-1">КЛЮЧОВІ ОЗНАКИ ТУРА :</h6>
                                    $features_data
                                </div>
                                <div class="peoples mb-3">
                                    <h6 class="mb-1">ОРІЄНТОВАНО НА :</h6>
                                    <span class="badge bg-light text-dark text-wrap">$tour_data[tour_adult] ДОРОСЛИХ</span>
                                    <span class="badge bg-light text-dark text-wrap">$tour_data[tour_children] ДИТИНИ</span>
                                </div>
                                <div class="managers mb-3">
                                    <h6 class="mb-1">КІЛЬКІСТЬ ЕКСКУРСОВОДІВ : </h6>
                                    <span class="badge bg-light text-dark text-wrap">$tour_data[tour_area] ЕКСКУРСОВОД [ІВ]</span>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <h6 class="mb-2">ЦІНА ЗА ТУР : $tour_data[tour_price]₴</h6>
                                <p class="mb-2" style="font-size: 10px;"><i class="bi bi-exclamation-octagon"></i> ЦІНА ТІЛЬКИ ЗА ТУР </p>
                                <a href="book-tour.php?tour_id=$tour_data[tour_id]" class="btn btn-sm w-100 text-white custom-bg shadow-none mb-2">ОФОРМИТИ 🛍️</a>
                                <a href="tour-details.php?tour_id=$tour_data[tour_id]" class="btn btn-sm w-100 btn-outline-dark shadow-none mt-2 ml-2">ДЕТАЛЬНІШЕ...</a>
                            </div>
                        </div>
                    </div>
                    data;
                }
                ?>
            </div>
        </div>
    </div>

<!-- Consolidated script includes -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>
