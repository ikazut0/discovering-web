<?php require('admin/include/db_config.php'); require('admin/include/essentials.php'); 

if (isset($_GET['alert']) && $_GET['alert'] === 'password_updated') {
    // Display the alert using JavaScript
    echo "<script>
            alert('Password updated successfully!');
          </script>";
}

?>

<!DOCTYPE HTML>
<html lang="UA">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('include/links.php'); ?>
    <title>DISCOVERING.UA - МАНДРУЙ УКРАЇНОЮ</title>
</head>

<body class="bg-light">
    <?php require('include/header.php'); ?>

    <div class="container-fluid px-lg-4 mt-4">
        <div class="swiper swiper-container">
            <div class="swiper-wrapper">
                <?php
                $res = selectAll('admin_carousel');

                while($row = mysqli_fetch_assoc($res)) {
                    $path = CAROUSEL_IMG_PATH;
                    echo <<< data
                    <div class="swiper-slide">
                        <img src="$path$row[carousel_image]" class="w-100 d-block" />
                    </div>
                    data;
                }
                ?>
            </div>
        </div>
    </div>

    <div class="container availability-form-phone-optimization">
        <div class="row">
            <div class="col-lg-12 bg-white shadow p-4 rounded">
                <h5 class="mb-4">ПОШУК ДОСТУПНИХ ТУРІВ СТАНОМ НА <span id="currentDate"></span></h5>
                <form>
                    <div class="row align-items-end">
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight: 500;">ОБЕРІТЬ ПОЧАТКОВУ ДАТУ :</label>
                            <input type="date" class="form-control shadow-none">
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight: 500;">ОБЕРІТЬ КІНЦЕВУ ДАТУ :</label>
                            <input type="date" class="form-control shadow-none">
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight: 500;">ДОРОСЛИХ :</label>
                            <select class="form-select shadow-none">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label class="form-label" style="font-weight: 500;">ДІТЕЙ :</label>
                            <select class="form-select shadow-none">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                        <div class="col-lg-1 mb-lg-3 mt-2">
                            <button type="submit" class="btn text-white shadow-none custom-bg">ПОШУК</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">ДОСТУПНІ ТУРИ ТА ПОДОРОЖІ</h2>
    <div class="container">
        <div class="row">
            <?php
            $tour_res = selectData("SELECT * FROM `admin_tours` WHERE `tour_status`=? AND `tour_removed`=? ORDER BY `tour_id` DESC LIMIT 3", [1, 0], 'ii');
            while ($tour_data = mysqli_fetch_assoc($tour_res)) {
                $fea_q = selectData("SELECT f.feature_name FROM `admin_feature` f INNER JOIN `admin_tours_feature` tfea ON f.feature_id = tfea.feature_id WHERE tfea.tour_id = ?", [$tour_data['tour_id']], 'i');
                $features_data = "";
                while ($fea_row = mysqli_fetch_assoc($fea_q)) {
                    $features_data .= "<span class='badge bg-light text-dark text-wrap me-1 mb-1'>$fea_row[feature_name]</span>";
                }
                $tour_thumb = TOURS_IMG_PATH . "empty-image-alert.gif";
                $thumb_q = mysqli_query($connection_info, "SELECT * FROM `admin_tour_images` WHERE `tour_id`='$tour_data[tour_id]' AND `tour_thumb`='1'");
                if(mysqli_num_rows($thumb_q) > 0) {
                    $thumb_res = mysqli_fetch_assoc($thumb_q);
                    $tour_thumb = TOURS_IMG_PATH.$thumb_res['tour_image'];
                }
                echo <<<data
                <div class="col-lg-4 col-md-6 my-3">
                <div class='card border-0 shadow' style='max-width: 350px; margin: auto;'>
                <img src='$tour_thumb' class='card-img-top' width='350' height='197'>
                        <div class="card-body">
                            <h5>$tour_data[tour_name]</h5>
                            <h6 class="mb-4"> ЦІНА : $tour_data[tour_price]₴ (ГРИВЕНЬ, ГРН)</h6>
                            <div class="alert alert-primary d-flex align-items-center" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                                    <use xlink:href="#info-fill" />
                                </svg>
                                <div>
                                    <i class="bi bi-exclamation-octagon"></i> УВАГА : ЦІНА ТІЛЬКИ ЗА <u>ТУР</u>
                                </div>
                            </div>
                            <div class="features mb-3">
                                <h6 class="mb-1">КЛЮЧОВІ ОЗНАКИ ТУРА</h6>
                                $features_data
                            </div>
                            <div class="peoples mb-3">
                                <h6 class="mb-1">ОРІЄНТОВАНО НА</h6>
                                <span class="badge bg-light text-dark text-wrap">$tour_data[tour_adult] ДОРОСЛИХ</span>
                                <span class="badge bg-light text-dark text-wrap">$tour_data[tour_children] ДИТИНИ</span>
                            </div>
                            <div class="managers mb-3">
                                <h6 class="mb-1">MANAGER </h6>
                                <span class="badge bg-light text-dark text-wrap">$tour_data[tour_area] ДОРОСЛИХ</span>
                            </div>
                            <div class="rating mb-4">
                                <h6 class="mb-1">РЕЙТИНГ ДАНОГО ТУРА</h6>
                                <span class="badge rounded-pill bg-light">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                </span>
                            </div>
                            <div class="d-flex justify-content-evenly mb-2">
                                <a href="#" class="btn btn-sm text-white custom-bg shadow-none mt-2 ml-2">ОФОРМИТИ</a>
                                <a href="tour-details.php?tour_id=$tour_data[tour_id]" class="btn btn-sm btn-outline-dark shadow-none mt-2 ml-2">ДЕТАЛЬНІШЕ...</a>
                            </div>
                        </div>
                    </div>
                </div>
                data;
            }
            ?>
        </div>
    </div>

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">ВІДГУКИ ПРО НАШІ ТУРИ</h2>
    <div class="container">
        <div class="swiper swiper-coverflow">
            <div class="swiper-wrapper mb-5">
                <div class="swiper-slide bg-white p-4">
                    <div class="profile d-flex align-items-center mb-3">
                        <img src="https://www.boveepilates.com/wp-content/uploads/2014/03/Paige-Face-Circle.png"
                            width="30px">
                        <h6 class="m-0 ms-2">ІРИНА ВОЛКОВА</h6>
                    </div>
                    <p>
                        "Моя подорож до Львова була незабутньою! Вражаюча архітектура, смачна українська кухня та
                        гостинність місцевих жителів зробили цю поїздку особливою. Львів - місто, яке завжди залишиться в
                        моєму серці."
                    </p>
                    <div class="rating">
                        <span>ОЦІНКА : </span>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                </div>
                <div class="swiper-slide bg-white p-4">
                    <div class="profile d-flex align-items-center mb-3">
                        <img src="https://circle.so/wp-content/uploads/person-1.png" width="30px">
                        <h6 class="m-0 ms-2">ОЛЬГА ПЕТРЕНКО</h6>
                    </div>
                    <p>
                        "Чернігів вражає своєю історією та атмосферою. Відвідавши там історичні пам'ятки і насолодившись
                        місцевими стравами, я залишився під враженням від цього міста. Обов'язково рекомендую відвідати
                        Чернігів усім шанувальникам культури та історії."
                    </p>
                    <div class="rating">
                        <span>ОЦІНКА : </span>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                </div>
                <div class="swiper-slide bg-white p-4">
                    <div class="profile d-flex align-items-center mb-3">
                        <img src="https://i0.wp.com/studiolorier.com/wp-content/uploads/2018/10/Profile-Round-Sander-Lorier.jpg?ssl=1"
                            width="30px">
                        <h6 class="m-0 ms-2">МАКСИМ ШЕВЧЕНКО</h6>
                    </div>
                    <p>
                        "Моя подорож до Дніпра була дуже цікавою та насиченою. Місто вразило своєю архітектурою та
                        видатними культурними об'єктами. Власне, я залишився задоволеним від цієї подорожі та сплановую
                        повернутися туди знову."
                    </p>
                    <div class="rating">
                        <span>ОЦІНКА : </span>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                </div>
                <div class="swiper-slide bg-white p-4">
                    <div class="profile d-flex align-items-center mb-3">
                        <img src="https://i0.wp.com/studiolorier.com/wp-content/uploads/2018/10/Profile-Round-Sander-Lorier.jpg?ssl=1"
                            width="30px">
                        <h6 class="m-0 ms-2">АНДРІЙ ПЕТРОВ</h6>
                    </div>
                    <p>
                        "Моя подорож до Дніпра була дуже цікавою та насиченою. Місто вразило своєю архітектурою та
                        видатними культурними об'єктами. Власне, я залишився задоволеним від цієї подорожі та сплановую
                        повернутися туди знову."
                    </p>
                    <div class="rating">
                        <span>ОЦІНКА : </span>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                </div>
                <div class="swiper-slide bg-white p-4">
                    <div class="profile d-flex align-items-center mb-3">
                        <img src="https://i0.wp.com/studiolorier.com/wp-content/uploads/2018/10/Profile-Round-Sander-Lorier.jpg?ssl=1"
                            width="30px">
                        <h6 class="m-0 ms-2">МАКСИМ СОКОЛОВ</h6>
                    </div>
                    <p>
                        "Моя подорож до Дніпра була дуже цікавою та насиченою. Місто вразило своєю архітектурою та
                        видатними культурними об'єктами. Власне, я залишився задоволеним від цієї подорожі та сплановую
                        повернутися туди знову."
                    </p>
                    <div class="rating">
                        <span>ОЦІНКА : </span>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
    <br>

    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <p class="col-md-4 mb-0 text-muted">© 2024 DISCOVERING.UA, Inc</p>
            <a href="#"
                class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                <img class="bi me-2" width="70" height="32"
                    src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ab/%D0%9B%D0%BE%D0%B3%D0%BE%D1%82%D0%B8%D0%BF_%D1%81%D0%B5%D1%80%D0%B2%D1%96%D1%81%D1%83_%D0%94%D1%96%D1%8F.svg/2560px-%D0%9B%D0%BE%D0%B3%D0%BE%D1%82%D0%B8%D0%BF_%D1%81%D0%B5%D1%80%D0%B2%D1%96%D1%81%D1%83_%D0%94%D1%96%D1%8F.svg.png">
            </a>
        </footer>
    </div>

    <a class="support-ukraine" target="_blank" rel="nofollow noopener">
        <div class="support-ukraine__flag" role="img">
            <div class="support-ukraine__flag__blue"></div>
            <div class="support-ukraine__flag__yellow"></div>
        </div>
        <div class="support-ukraine__label">#SPEND WITH UKRAINE TO STAND WITH UKRAINE</div>
    </a>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="scripts/swiper-slider.js"></script>
    <script src="scripts/swiper-coverflow.js"></script>
    <script src="scripts/current-date.js"></script>
</body> </html>