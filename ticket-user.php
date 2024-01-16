<?php require('admin/include/db_config.php'); require('admin/include/essentials.php'); session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$userEmail = $_SESSION['user_email'];

if (isset($_GET['tour_id'])) {
    $tourId = $_GET['tour_id'];
    
    $bookedTourQuery = "SELECT t.*, b.booking_date, i.tour_image, u.user_name, b.card_number FROM admin_tours t JOIN user_booking_info b ON t.tour_id = b.tour_id JOIN user_info u ON b.user_id = u.user_id JOIN admin_tour_images i ON t.tour_id = i.tour_id AND i.tour_thumb = 1 WHERE u.user_email = ? AND t.tour_id = ?";
    
    $bookedTourValues = [$userEmail, $tourId];
    $bookedTourResult = selectData($bookedTourQuery, $bookedTourValues, 'si');
    
    if ($bookedTourData = mysqli_fetch_assoc($bookedTourResult)) {

    } else {
        header("Location: user-booked-tours.php");
        exit();
    }
} else {
    header("Location: user-booked-tours.php");
    exit();
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
    <style>body { background-color: #f8f9fa; } #cardArea { background-color: white; padding: 15px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); } #cardArea img { max-width: 100%; height: auto; border-radius: 5px; }</style>
</head>

<body> <?php require('include/header.php'); ?>
    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">ДЕТАЛЬНА ІНФОРМАЦІЯ ПРО ПРИДБАНИЙ КОРИСТУВАЧЕМ ТУР</h2>
        <hr class="mx-auto my-3" style="border: 1px solid #000; width: 85px;">
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-12 px-4">
            <div class="card mb-4 border-0 shadow" id="cardArea">
                <div class="row g-0 p-3 align-items-center">
                    <div class="col-md-5 mb-lg-0 mb-md-0 mb-3">
                        <?php
                        $tourThumb = TOURS_IMG_PATH . "empty-image-alert.gif";

                        if (!empty($bookedTourData['tour_image'])) {
                            $tourThumb = TOURS_IMG_PATH . $bookedTourData['tour_image'];
                        }

                        echo "<img src='$tourThumb' class='d-block w-100 rounded'>";
                        
                        ?>
                    </div>

                    <div class="col-md-7 px-lg-3 px-md-3 px-0">
                        <h5 class="mb-3" style="font-size: 16px; font-weight: bold; text-align: justify;">ПОВНА НАЗВА : <?php echo $bookedTourData['tour_name']; ?></h5>
                        <p class="mb-3" style="font-size: 16px; text-align: justify;"><strong>ОФОРМИВ ТА ОПЛАТИВ ТУР :</strong> <?php echo $bookedTourData['user_name']; ?></p>
                        <p style="font-size: 16px; text-align: justify;"><strong>ОПЛАЧЕНА ВАРТІСТЬ ТУРУ :</strong> <?php echo $bookedTourData['tour_price']; ?>₴</p>
                        <p style="font-size: 16px; text-align: justify;"><strong>ДАТА ОФОРМЛЕННЯ ТА ОПЛАТИ ТУРУ :</strong> <?php echo $bookedTourData['booking_date']; ?></p>
                        <div class="mt-4 d-flex justify-content">
                            <button id="saveButton" onclick="saveReceipt()" class="btn btn-success">СОХРАНИТЬ КВИТАНЦІЮ</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <p class="col-md-4 mb-0 text-muted">© 2024 DISCOVERING.UA, INC</p><a class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none"><img class="bi me-2" width="70" height="32" src="images/footer-logo.png"></a>
        </footer>
    </div>

    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdn.rawgit.com/neocotic/qrious/v4.0.2/qrious.min.js"></script>

    <script>
    function saveReceipt() {
        var saveButton = document.getElementById("saveButton");
        if (saveButton) {
            saveButton.style.display = "none";
        }

        html2canvas(document.getElementById("cardArea"), {
            backgroundColor: null,
        }).then(function(canvas) {
            if (saveButton) {
                saveButton.style.display = "block";
            }

            var pdf = new window.jspdf.jsPDF();

            var imgData = canvas.toDataURL('image/png');
            pdf.addImage(imgData, 'PNG', 10, 10, 190, 130);

            pdf.setTextColor(0, 0, 0);
            pdf.setFontSize(12);

            var localImagePath = 'images/stamp-for-receipt-photo-example.png';
            var stampWidth = 30;
            var stampHeight = 30;
            var stampX = 190 - stampWidth;
            var stampY = 100;
            addImageToPDF(pdf, localImagePath, stampX, stampY, stampWidth, stampHeight);

            var overlayImagePath = 'images/signature-for-receipt-photo-example.png';
            var signatureWidth = 30;
            var signatureHeight = 20;
            var signatureX = 190 - signatureWidth;
            var signatureY = 105;
            addImageToPDF(pdf, overlayImagePath, signatureX, signatureY, signatureWidth, signatureHeight);

            var qrCodeData = 'https://discovering-ukraine.000webhostapp.com/';
            var qrCodeImage = new Image();
            qrCodeImage.src = 'https://api.qrserver.com/v1/create-qr-code/?data=' + encodeURIComponent(qrCodeData) + '&size=50x50';
            var qrCodeWidth = 20;
            var qrCodeHeight = 20;
            var qrCodeX = 190 - qrCodeWidth;
            var qrCodeY = 20;
            pdf.addImage(qrCodeImage, 'PNG', qrCodeX, qrCodeY, qrCodeWidth, qrCodeHeight);

            var currentDate = new Date().toLocaleDateString().replace(/\//g, "-");
            var filename = 'receipt_' + currentDate + '.pdf';

            pdf.save(filename);

            alert('КВИТАНЦІЯ ЗБЕРЕЖЕНА');
        });

        function addImageToPDF(pdf, imagePath, x, y, width, height) {
            var img = new Image();
            img.src = imagePath;
            pdf.addImage(img, 'PNG', x, y, width, height);
        }
    }
    
    </script>

</body> </html>