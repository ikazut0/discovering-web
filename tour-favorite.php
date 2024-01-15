<?php require('admin/include/db_config.php'); require('admin/include/essentials.php'); session_start();

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
            break;
    }
}

function addToFavorites($tour_id)
{
    $user_email = $_SESSION['user_email'];
    
    $checkQuery = "SELECT * FROM user_favorites uf JOIN user_info u ON uf.user_id = u.user_id WHERE u.user_email=? AND uf.tour_id=?";
    $checkValues = [$user_email, $tour_id];
    $checkResult = selectData($checkQuery, $checkValues, 'si');
    
    if (mysqli_num_rows($checkResult) == 0) {
        $insertQuery = "INSERT INTO user_favorites (user_id, tour_id) VALUES ((SELECT user_id FROM user_info WHERE email=?), ?)";
        $insertValues = [$user_email, $tour_id];
        $insertResult = insertData($insertQuery, $insertValues, 'si');
        
        if ($insertResult) {
            header("Location: tour-favorite.php");
            exit();
        } else {
            echo "ПОМИЛКА ДОДАВАННЯ ДО ОБРАНОГО";
        }
    } else {
        echo "ДАНИЙ ТУР ВЖЕ У СПИСКУ ОБРАНОГО";
    }
}

function removeFromFavorites($tour_id)
{
    $user_email = $_SESSION['user_email'];
    
    $deleteQuery = "DELETE FROM user_favorites uf USING users_info u WHERE uf.user_id = u.user_id AND u.user_email=? AND uf.tour_id=?";
    $deleteValues = [$user_email, $tour_id];
    $deleteResult = deleteData($deleteQuery, $deleteValues, 'si');
    
    if ($deleteResult) {
        header("Location: tour-favorite.php");
        exit();
    } else {
        echo "ПОМИЛКА ВИДАЛЕННЯ З СПИСКУ ОБРАНОГО";
    }
}

$user_email = $_SESSION['user_email'];
$favoritesQuery = "SELECT t.* FROM user_favorites uf JOIN admin_tours t ON uf.tour_id = t.tour_id JOIN user_info u ON uf.user_id = u.user_id WHERE u.user_email=?";
$favoritesValues = [$user_email];
$favoritesResult = selectData($favoritesQuery, $favoritesValues, 's');

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

<div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">СПИСОК ТУРІВ, ЯКІ БУЛИ ДОДАНІ ДО КАТАЛОГУ ОБРАНИХ</h2>
    <hr class="mx-auto my-3" style="border: 1px solid #000; width: 85px;">
</div>

<div class="row justify-content-center">
    <div class="col-lg-9 col-md-12 px-4">
        <?php
        
        $tour_res = selectData("SELECT * FROM `admin_tours` WHERE `tour_status`=? AND `tour_removed`=?", [1, 0], 'ii');
        
        while ($tour_data = mysqli_fetch_assoc($favoritesResult)) {
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
                
            echo <<< data
            <div class="card mb-4 border-0 shadow">
            <div class="row g-0 p-3 align-items-center">
            <div class="col-md-5 mb-lg-0 mb-md-0 mb-3">
            <img src="$tour_thumb" class="img-fluid rounded">
            </div>

            <div class="col-md-5 px-lg-3 px-md-3 px-0">
            <h5 class="mb-3">НАЗВА : $tour_data[tour_name]</h5>
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
            </div> </div>
            
            <div class="col-md-2 text-center">
            <h6 class="mb-2">ЦІНА ТУРУ : $tour_data[tour_price] ₴</h6>
            <p class="mb-2" style="font-size: 10px;"><i class="bi bi-exclamation-octagon"></i> ВКАЗАНА ЦІНА ТІЛЬКИ ЗА ТУР </p>
            <a href="tour-details.php?tour_id=$tour_data[tour_id]" class="btn btn-sm w-100 btn-outline-dark shadow-none mt-2 ml-2"> ДЕТАЛЬНІШЕ >>> </a>
            </div> </div> </div>
            data;
            }
            ?>
            </div>
        </div>
    </div>

    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <p class="col-md-4 mb-0 text-muted">© 2024 DISCOVERING.UA, INC</p><a class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none"><img class="bi me-2" width="70" height="32" src="images/footer-logo.png"></a>
        </footer>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body> </html>