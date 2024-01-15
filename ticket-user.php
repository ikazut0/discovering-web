<?php
require('admin/include/db_config.php');
require('admin/include/essentials.php');

session_start();

if (!isset($_SESSION['user_email'])) {
    // Redirect to login page or handle as needed
    header("Location: login.php");
    exit();
}

$userEmail = $_SESSION['user_email'];

// Check if tour_id is set in the query parameters
if (isset($_GET['tour_id'])) {
    $tourId = $_GET['tour_id'];

    // Get booked tour details for the user
    $bookedTourQuery = "SELECT t.*, b.booking_date, i.tour_image, u.user_name, b.card_number
                        FROM admin_tours t
                        JOIN user_booking_info b ON t.tour_id = b.tour_id
                        JOIN user_info u ON b.user_id = u.user_id
                        JOIN admin_tour_images i ON t.tour_id = i.tour_id AND i.tour_thumb = 1
                        WHERE u.user_email = ? AND t.tour_id = ?";

    $bookedTourValues = [$userEmail, $tourId];
    $bookedTourResult = selectData($bookedTourQuery, $bookedTourValues, 'si');

    if ($bookedTourData = mysqli_fetch_assoc($bookedTourResult)) {
        // Tour details found, proceed to display
    } else {
        // Redirect or handle if tour not found
        header("Location: user-booked-tours.php");
        exit();
    }
} else {
    // Redirect or handle if tour_id is not set
    header("Location: user-booked-tours.php");
    exit();
}
?>

<!DOCTYPE HTML>
<html lang="UA">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('include/links.php'); ?>
    <title>DISCOVERING.UA - МАНДРУЙ УКРАЇНОЮ</title>
    <style>
    #cardArea {
        background-color: white; /* Установите цвет фона для области cardArea */
        padding: 20px; /* Добавьте отступы для более привлекательного вида */
    }

    #cardArea img {
        max-width: 100%; /* Сделайте изображение максимально возможным размером в пределах cardArea */
        height: auto; /* Автоматический расчет высоты, чтобы избежать искажений */
    }
</style>
</head>

<body class="bg-light">
    <?php require('include/header.php'); ?>
    <!-- Add your header content here -->

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">ІНФОРМАЦІЯ ПРО ТУР</h2>
        <hr class="mx-auto my-3" style="border: 1px solid #000; width: 85px;">
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9 col-md-12 px-4">
            <div class='card mb-4 border-0 shadow' id="cardArea">
                <div class='row g-0 p-3 align-items-center'>
                    <div class='col-md-5 mb-lg-0 mb-md-0 mb-3'>
                        <?php
                        // Display tour image
                        $tourThumb = TOURS_IMG_PATH . "empty-image-alert.gif";
                        if (!empty($bookedTourData['tour_image'])) {
                            $tourThumb = TOURS_IMG_PATH . $bookedTourData['tour_image'];
                        }

                        echo "<img src='$tourThumb' class='img-fluid rounded'>";
                        ?>
                    </div>
                    <div class='col-md-5 px-lg-3 px-md-3 px-0'>
                    <h5 class='mb-3'>ІМ'Я КОРИСТУВАЧА: <?php echo $bookedTourData['user_name']; ?></h5>
                        <p>ТУР: <?php echo $bookedTourData['tour_name']; ?></p>
                        <h5 class='mb-3'>НАЗВА ТУРУ: <?php echo $bookedTourData['tour_name']; ?></h5>
                        <p>ЦІНА ТУРУ: <?php echo $bookedTourData['tour_price']; ?>₴</p>
                        <p>ДАТА БРОНЮВАННЯ: <?php echo $bookedTourData['booking_date']; ?></p>
                        <div class='mt-4'>
                <button onclick="saveReceipt()" class='btn btn-success'>СОХРАНИТЬ КВИТАНЦІЮ</button>
            </div>
                        <!-- Add more details as needed -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add your scripts here -->
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script>
    function saveReceipt() {
        // Capture the card area using html2canvas
        html2canvas(document.getElementById("cardArea"), {
            backgroundColor: null, // Установите фоновый цвет на null для сохранения фона
        }).then(function(canvas) {
            // Convert the canvas to an image
            var image = canvas.toDataURL("image/png");

            // Create a link element to download the image
            var link = document.createElement('a');
            link.href = image;
            link.download = 'receipt.png';

            // Trigger the download
            link.click();

            // Show notification
            alert('КВИТАНЦІЯ ЗБЕРЕЖЕНА');
        });
    }
</script>

</body>

</html>
