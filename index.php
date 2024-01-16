<?php require('admin/include/db_config.php'); require('admin/include/essentials.php'); session_start();

$query = "SELECT `site_shutdown` FROM `admin_settings` WHERE `settings_id`=?";
$values = [1];

$mysqli = new mysqli("localhost", "root", "", "tourdb");

if ($mysqli->connect_error) {
    die("ПІДКЛЮЧЕННЯ НЕ ВДАЛОСЯ : " . $mysqli->connect_error);
}

$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', ...$values);

$stmt->execute();

$result = $stmt->get_result()->fetch_assoc();

$stmt->close();
$mysqli->close();

if ($result && isset($result['site_shutdown']) && $result['site_shutdown'] == 1) {
    echo '<div style="display: flex; justify-content: center; align-items: center; height: 100vh;">';
    echo '<img src="https://media.giphy.com/avatars/404academy/kGwR3uDrUKPI.gif" style="max-width: 100%; max-height: 100%;"/>';
    echo '</div>'; exit;
}

if (isset($_GET['alert']) && $_GET['alert'] === 'password_updated') {
    echo "<script>alert('ПАРОЛЬ УСПІШНО ОНОВЛЕНО!');</script>";
}

?>

<!DOCTYPE HTML>
<HTML lang="UA">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('include/links.php'); ?>
    <link rel="shortcut icon" href="https://cdn.icon-icons.com/icons2/1928/PNG/512/iconfinder-compass-direction-maps-holiday-vacation-icon-4602027_122100.png" type="image/x-icon">
    <title>DISCOVERING.UA</title>
</head>

<body class="bg-light"> <?php require('include/header.php'); ?>
    <div class="container-fluid px-lg-4 mt-4">
        <div class="swiper swiper-container">
            <div class="swiper-wrapper">
                <?php
                $res = selectAll('admin_carousel');

                while ($row = mysqli_fetch_assoc($res)) {
                    $path = CAROUSEL_IMG_PATH;
                    echo <<< data
                    <div class='swiper-slide'>
                        <img src='$path$row[carousel_image]' class='w-100 d-block'/>
                    </div>
                    data;
                }
                ?>
            </div>
        </div>
    </div>

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">ДОСТУПНІ ДЛЯ ЗАМОВЛЕННЯ ТУРИ ТА ПОДОРОЖІ</h2>
    <div class="container">
        <div class="row">
            <?php
            
            $tour_res = selectData("SELECT * FROM `admin_tours` WHERE `tour_status`=? AND `tour_removed`=? LIMIT 3", [1, 0], 'ii');

            while ($tour_data = mysqli_fetch_assoc($tour_res)) {

                $fea_q = selectData("SELECT f.feature_name FROM `admin_feature` f INNER JOIN `admin_tours_feature` tfea ON f.feature_id = tfea.feature_id WHERE tfea.tour_id = ?", [$tour_data['tour_id']], 'i');

                $features_data = "";

                while ($fea_row = mysqli_fetch_assoc($fea_q)) {

                    $features_data .= "<span class='badge bg-light text-dark text-wrap me-1 mb-1'>$fea_row[feature_name]</span>";

                }

                $tour_thumb = TOURS_IMG_PATH . "empty-image-alert.gif";

                $thumb_q = mysqli_query($connection_info, "SELECT * FROM `admin_tour_images` WHERE `tour_id`='$tour_data[tour_id]' AND `tour_thumb`='1'");

                if (mysqli_num_rows($thumb_q) > 0) {

                    $thumb_res = mysqli_fetch_assoc($thumb_q);

                    $tour_thumb = TOURS_IMG_PATH . $thumb_res['tour_image'];
                }

                echo <<< data
                <div class="col-lg-4 col-md-6 my-3">
                    <div class='card border-0 shadow' style='max-width: 350px; margin: auto;'>
                        <img src='$tour_thumb' class='card-img-top' width='350' height='197'>
                        <div class="card-body">
                            <h5 class='text-center'>$tour_data[tour_name]</h5>
                            <h6 class='mb-2 text-center'>ЦІНА ТУРУ : $tour_data[tour_price] ₴</h6>
                            <div class="alert alert-primary d-flex align-items-center text-center" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"> <use xlink:href="#info-fill" /> </svg>
                                <div> <i class="bi bi-exclamation-octagon"> ВКАЗАНА ЦІНА ТІЛЬКИ ЗА <u>ТУР </u> </i>
                                </div>
                            </div>
                            <div class="features mb-3">
                                <h6 class="mb-1">КЛЮЧОВІ ОСОБЛИВОСТІ ДАНОГО ТУРА : </h6>
                                $features_data
                            </div>
                            <div class="peoples mb-3">
                                <h6 class="mb-1">ДАНИЙ ТУР ОРІЄНТОВАНИЙ НА : </h6>
                                <span class="badge bg-light text-dark text-wrap">$tour_data[tour_adult] ДОРОСЛИХ</span>
                                <span class="badge bg-light text-dark text-wrap">$tour_data[tour_children] ДИТИНИ</span>
                            </div>
                            <div class="managers mb-3">
                                <h6 class="mb-1">КІЛЬКІСТЬ ЕКСКУРСОВОДІВ : </h6>
                                <span class="badge bg-light text-dark text-wrap">$tour_data[tour_area] ЕКСКУРСОВОД / ГІД [ІВ]</span>
                            </div>
                            <div class="d-flex justify-content-evenly mb-2">
                                <a href="tour-details.php?tour_id=$tour_data[tour_id]" class="btn btn-sm w-100 btn-outline-dark shadow-none mt-2 ml-2"> ДЕТАЛЬНІШЕ >>> </a>
                            </div>
                        </div>
                    </div>
                </div>
                data;
            }
            ?>
        </div>
    </div>

    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <p class="col-md-4 mb-0 text-muted">© 2024 DISCOVERING.UA, INC</p><a class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none"><img class="bi me-2" width="70" height="32" src="images/footer-logo.png"></a>
        </footer>
    </div>
    
    <a class="support-ukraine" target="_blank" rel="nofollow noopener">
        <div class="support-ukraine__flag" role="img">
            <div class="support-ukraine__flag__blue"></div>
            <div class="support-ukraine__flag__yellow"></div>
        </div>
        <div class="support-ukraine__label">#SPEND WITH UKRAINE TO STAND WITH UKRAINE</div>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="scripts/swiper-slider.js"></script>

</body> </html>