<?php require('admin/include/db_config.php'); require('admin/include/essentials.php'); session_start();

function getUserInfoByEmail($email)
{
    global $connection_info;

    $query = "SELECT * FROM user_info WHERE user_email=?";
    $values = [$email];
    $result = selectData($query, $values, 's');

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}

function addToFavorites($user_id, $user_email, $tour_id)
{
    global $connection_info;

    $query = "INSERT INTO user_favorites (user_id, user_email, tour_id) VALUES (?, ?, ?)";
    $values = [$user_id, $user_email, $tour_id];
    $result = insertData($query, $values, 'iss');

    if ($result) {
        return true;
    } else {
        return false;
    }
}

function checkIfTourBooked($user_id, $tour_id)
{
    global $connection_info;

    $query = "SELECT * FROM user_booking_info WHERE user_id=? AND tour_id=?";
    $values = [$user_id, $tour_id];
    $result = selectData($query, $values, 'ii');

    return ($result && mysqli_num_rows($result) > 0);
}

$userLoggedIn = isset($_SESSION['user_email']);
$userInfo = [];

if ($userLoggedIn) {
    $userEmail = $_SESSION['user_email'];
    $userInfo = getUserInfoByEmail($userEmail);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'addToFavorites') {
    if ($userLoggedIn && isset($_POST['tour_id'])) {
        $tourId = $_POST['tour_id'];
        $userId = $userInfo['user_id'];
        $userEmail = $userInfo['user_email'];

        $isTourBooked = checkIfTourBooked($userId, $tourId);

        if (!$isTourBooked) {
            $success = addToFavorites($userId, $userEmail, $tourId);

            if ($success) {
                echo json_encode(['status' => 'success', 'message' => 'ТУР ДОДАНИЙ ДО ОБРАНОГО']);
                exit;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ПОМИЛКА ДОДАВАННЯ ТУРУ ДО ВИБРАНОГО']);
                exit;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ТУР УЖЕ КУПЛЕНИЙ, АБО ОФОРМЛЕНИЙ']);
            exit;
        }
    }
}
?>

<!DOCTYPE HTML>
<html lang="UA">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('include/links.php'); ?>
    <link rel="shortcut icon" href="https://cdn.icon-icons.com/icons2/1928/PNG/512/iconfinder-compass-direction-maps-holiday-vacation-icon-4602027_122100.png" type="image/x-icon">
    <title>DISCOVERING.UA</title>
    <style>
    .small-text {
        font-size: 12px;
    }
    </style>
</head>

<body class="bg-light"> <?php require('include/header.php'); ?>

<div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">ДОСТУПНІ ДЛЯ ЗАМОВЛЕННЯ ТУРИ ТА ПОДОРОЖІ</h2>
    <hr class="mx-auto my-3" style="border: 1px solid #000; width: 85px;">
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 ps-4">
            <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
                <div class="container-fluid flex-lg-column align-items-stretch">
                    <h4 class="mt-2">ФІЛЬТР ТУРІВ :</h4>
                    <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterDropdown" aria-controls="navbarNav" aria-expanded="false">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="filterDropdown">
                        <div class="border bg-light p-3 rounded mb-3">
                            <h5 class="mb-3" style="font-size: 18px;">ПОШУК ТУРІВ ПО НАЗВІ : </h5>
                            <form method="get" action="">
                                <label for="searchTour">Введіть назву туру:</label>
                                <input type="text" id="searchTour" name="searchTour" class="form-control shadow-none mb-3">
                                <button type="submit" class="btn btn-sm btn-outline-dark">Пошук</button>
                                <a href="tour-list.php" class="btn btn-sm btn-outline-secondary">Скинути</a>
                            </form>
                        </div>
                        
                        <div class="border bg-light p-3 rounded mb-3">
                            <h5 class="mb-3" style="font-size: 18px;">СОРТУВАННЯ ТУРІВ:</h5>
                            <form method="get" action="">
                                <label for="sortTour">Оберіть спосіб сортування:</label>
                                <select id="sortTour" name="sortTour" class="form-select shadow-none mb-3 small-text">
                                    <option value="name_asc">За назвою (за зростанням)</option>
                                    <option value="name_desc">За назвою (за спаданням)</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-dark">Сортувати</button>
                            </form>
                        </div>
                        
                        <div class="border bg-light p-3 rounded mb-3">
                            <h5 class="mb-3" style="font-size: 18px;">СОРТУВАННЯ ПО КІЛЬКОСТІ ЛЮДЕЙ:</h5>
                            <form method="get" action="">
                                <label for="sortPeople">Оберіть спосіб сортування:</label>
                                <select id="sortPeople" name="sortPeople" class="form-select shadow-none mb-3 small-text">
                                    <option value="adult_asc">За кількістю дорослих (за зростанням)</option>
                                    <option value="adult_desc">За кількістю дорослих (за спаданням)</option>
                                    <option value="children_asc">За кількістю дітей (за зростанням)</option>
                                    <option value="children_desc">За кількістю дітей (за спаданням)</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-dark">Сортувати</button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        
        <div class="col-lg-9 col-md-12 px-4">
            <?php
            
            $sortOption = isset($_GET['sortTour']) ? $_GET['sortTour'] : '';
            $sortPeopleOption = isset($_GET['sortPeople']) ? $_GET['sortPeople'] : '';
            $searchTour = isset($_GET['searchTour']) ? $_GET['searchTour'] : '';
            
            $orderBy = '';
            $orderByPeople = '';
            
            switch ($sortOption) {
                case 'name_asc':
                    $orderBy = 'ORDER BY `tour_name` ASC';
                    break;
                
                case 'name_desc':
                    $orderBy = 'ORDER BY `tour_name` DESC';
                    break;
                }
                
                switch ($sortPeopleOption) {
                    case 'adult_asc':
                        $orderByPeople = 'ORDER BY `tour_adult` ASC';
                        break;
                        
                case 'adult_desc':
                    $orderByPeople = 'ORDER BY `tour_adult` DESC';
                    break;
                    
                case 'children_asc':
                    $orderByPeople = 'ORDER BY `tour_children` ASC';
                    break;
                    
                case 'children_desc':
                    $orderByPeople = 'ORDER BY `tour_children` DESC';
                    break;
                }
                
                $tour_res = selectData("SELECT * FROM `admin_tours` WHERE `tour_status`=? AND `tour_removed`=? AND `tour_name` LIKE ? $orderBy $orderByPeople", [1, 0, "%$searchTour%"], 'iis');
                
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
                    
                    $isTourBooked = isset($userInfo['user_id']) ? checkIfTourBooked($userInfo['user_id'], $tour_data['tour_id']) : false;
                    $buttonHTML = '';
                    
                    if ($isTourBooked) {
                        $buttonHTML = '<p class="mb-2 text-success">Оформлено</p>';
                    } elseif ($userLoggedIn && $tour_thumb !== TOURS_IMG_PATH . "empty-image-alert.gif") {
                        $buttonHTML = '<a href="book-tour.php?tour_id=' . $tour_data['tour_id'] . '" class="btn btn-sm w-100 text-white custom-bg shadow-none mb-2">ОФОРМИТИ 🛍️</a>';
                    }                    
                    
                    if ($userLoggedIn) {
                        $buttonHTML .= '<a href="javascript:void(0)" class="btn btn-sm w-100 text-white custom-bg shadow-none mb-2 like-button" data-tour-id="' . $tour_data['tour_id'] . '">ЗАКЛАДКИ ❤️</a>';
                    }
                    
                    $buttonHTML .= '<a href="tour-details.php?tour_id=' . $tour_data['tour_id'] . '" class="btn btn-sm w-100 btn-outline-dark shadow-none mt-2 ml-2">ДЕТАЛЬНІШЕ...</a>';
                    
                    $highlightedName = str_ireplace($searchTour, '<mark>' . $searchTour . '</mark>', $tour_data['tour_name']);
                    
                    echo <<< data
                    <div class="card mb-4 border-0 shadow">
                    <div class="row g-0 p-3 align-items-center">
                    <div class="col-md-5 mb-lg-0 mb-md-0 mb-3">
                    <img src="$tour_thumb" class="img-fluid rounded">
                    </div>
                    <div class="col-md-5 px-lg-3 px-md-3 px-0">
                    <h5 class="mb-3">НАЗВА ТУРУ : $highlightedName</h5>
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
                    <h6 class="mb-1">КІЛЬКІСТЬ ЕКСКУРСОВОДІВ : </h6>
                    <span class="badge bg-light text-dark text-wrap">$tour_data[tour_area] ЕКСКУРСОВОД [ІВ]</span>
                    </div>
                    </div>
                    <div class="col-md-2 text-center">
                    <h6 class="mb-2">ЦІНА ЗА ТУР : $tour_data[tour_price]₴</h6>
                    <p class="mb-2" style="font-size: 10px;"><i class="bi bi-exclamation-octagon"></i> ЦІНА ТІЛЬКИ ЗА ТУР </p>
                    $buttonHTML
                    </div>
                    </div>
                    </div>
                    data;
                }
                ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <p class="col-md-4 mb-0 text-muted">© 2024 DISCOVERING.UA, INC</p><a class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none"><img class="bi me-2" width="70" height="32" src="images/footer-logo.png"></a>
        </footer>
    </div>
    
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const likeButtons = document.querySelectorAll('.like-button');

        likeButtons.forEach(button => {
            button.addEventListener('click', function () {
                const tourId = this.getAttribute('data-tour-id');
                addToFavorites(tourId);
            });
        });

        function addToFavorites(tour_id) {
            const xhr = new XMLHttpRequest();
            const url = 'addToFavorites.php';

            xhr.open('POST', url, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            const params = 'action=addToFavorites&tour_id=' + encodeURIComponent(tour_id);

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    try {
                        console.log('ВІДПОВІДЬ :', xhr.responseText);
                        const response = JSON.parse(xhr.responseText);

                        if (response.status === 'success') {
                            console.log(response.message);
                        } else if (response.status === 'error') {
                            console.error(response.message);
                        } else {
                            console.error('НЕПЕРЕДБАЧЕНА ВІДПОВІДЬ ВІД СЕРВЕРА');
                        }
                    } catch (e) {
                        console.error('ПОМИЛКА ОБРОБКИ ВІДПОВІДІ ВІД СЕРВЕРА');
                    }
                }
            };
            xhr.onerror = function () {
                console.error('ПОМИЛКА НАДСИЛАННЯ ЗАПИТУ');
            };
            xhr.send(params);
        }
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body> </html>