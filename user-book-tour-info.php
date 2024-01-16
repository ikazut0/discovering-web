<?php require('admin/include/db_config.php'); require('admin/include/essentials.php'); session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$userEmail = $_SESSION['user_email'];

$bookedToursQuery = "SELECT t.*, b.booking_date, i.tour_image 
                     FROM admin_tours t 
                     JOIN user_booking_info b ON t.tour_id = b.tour_id 
                     JOIN user_info u ON b.user_id = u.user_id 
                     JOIN admin_tour_images i ON t.tour_id = i.tour_id AND i.tour_thumb = 1 
                     WHERE u.user_email = ?";

$bookedToursValues = [$userEmail];
$bookedToursResult = selectData($bookedToursQuery, $bookedToursValues, 's');

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
        <h2 class="fw-bold h-font text-center">ІСТОРІЯ ОФОРМЛЕНИХ КОРИСТУВАЧЕМ ТУРІВ</h2>
        <hr class="mx-auto my-3" style="border: 1px solid #000; width: 85px;">
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9 col-md-12 px-4"> 
            <?php
            while ($bookedTourData = mysqli_fetch_assoc($bookedToursResult)) {
                echo "<div class='card mb-4 border-0 shadow'>";
                echo "<div class='row g-0 p-3 align-items-center'>";
                echo "<div class='col-md-5 mb-lg-0 mb-md-0 mb-3'>";
                
                $tour_thumb = TOURS_IMG_PATH . "empty-image-alert.gif";
                $thumb_q = mysqli_query($connection_info, "SELECT * FROM `admin_tour_images` WHERE `tour_id`='$bookedTourData[tour_id]' AND `tour_thumb`='1'");
                
                if (mysqli_num_rows($thumb_q) > 0) {
                    $thumb_res = mysqli_fetch_assoc($thumb_q);
                    $tour_thumb = TOURS_IMG_PATH . $thumb_res['tour_image'];
                }
                
                echo "<img src='$tour_thumb' class='img-fluid rounded' width='1280' height='720'>";
                echo "</div>";
                echo "<div class='col-md-5 px-lg-3 px-md-3 px-0'>";
                echo "<h5 class='mb-3'>НАЗВА : {$bookedTourData['tour_name']}</h5>";
                echo "<p>ОГОЛОШЕНА ВАРТІСТЬ ТУРУ : {$bookedTourData['tour_price']}₴</p>";
                echo "<p>ДАТА БРОНЮВАННЯ ТУРУ : {$bookedTourData['booking_date']}</p>";
                
                echo "<div class='mt-4'>";
                echo "<a href='ticket-user.php?tour_id={$bookedTourData['tour_id']}' class='btn btn-primary'>ОТРИМАТИ КВИТАНЦІЮ</a>"; 
                echo "</div>"; echo "</div>"; echo "</div>"; echo "</div>";
            } 
            ?>
        </div>
    </div>
</div>

<div class="container">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
        <p class="col-md-4 mb-0 text-muted">© 2024 DISCOVERING.UA, INC</p>
        <a class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
            <img class="bi me-2" width="70" height="32" src="images/footer-logo.png">
        </a>
    </footer>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body> </html>