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

<body class="bg-light"> <?php require 'include/header.php'; ?>

    <div style="text-align: center; font-size: 24px; font-weight: bold; margin: 0 20px;"><img width="64" height="54" src="images/online-tour-pictures/online-tour-picture-ico.gif">ВІРТУАЛЬНІ ТУРИ<img width="64" height="54" src="images/online-tour-pictures/online-tour-picture-ico.gif"></div>

    <p style="text-align: center; font-size: 16px; margin: 0 20px;">ВСІ ПРАВА НА ВІРТУАЛЬНІ ТУРИ НАЛЕЖАТЬ - © KYIV DIGITAL, 2024</p><br>

    <div class="container">
        <div class="row">
            <?php
            $tours = [
                ["1.jpg", "НАЗВА ТУРУ: МУЗЕЙ ВИДАТНИХ ДІЯЧІВ", "Ласкаво просимо вас на захопливу подорож до дивовижного місця, яке присвячено великим постатям української історії та культури.", "https://guide.kyivcity.gov.ua/virtual-tours/onlajn-ekskursia-muzeem-vidatnih-diaciv-ukrainskoi-kulturi"],
                ["2.jpg", "НАЗВА ТУРУ : КИЇВ НЕЗАЛЕЖНИЙ", "Ласкаво просимо вас в ексклюзивний тур, що відкриє перед вами усю багатогранність столиці України як символу незалежності та величі.", "https://guide.kyivcity.gov.ua/virtual-tours/independence-day"],
                ["3.jpg", "НАЗВА ТУРУ : БУДИНОК АКТОРА", "Ласкаво просимо в тур, зачарований світ якого розкриє перед вами відмінність та витонченість театрального мистецтва.", "https://guide.kyivcity.gov.ua/virtual-tours/budinok-aktora"],
                ["4.jpg", "НАЗВА ТУРУ : МУЗЕЙ СТАНОВЛЕННЯ НАЦІЇ", "Ласкаво просимо в захопливий тур, що веде вас через важливі сторінки історії, де ви дізнаєтеся про розвиток національної ідентичності.", "https://guide.kyivcity.gov.ua/virtual-tours/muzej-stanovlenna-ukrainskoi-nacii"],
                ["5.jpg", "НАЗВА ТУРУ : СОФІЙСЬКА ПЛОЩА", "Ласкаво просимо на Софіївську площу - місце в самому центрі Києва, що розкриває перед вами архітектурну велич столиці.", "https://guide.kyivcity.gov.ua/places/sofijska-plosa/street-view"],
                ["6.jpg", "НАЗВА ТУРУ : ПОШТОВА ПЛОЩА", "Ласкаво просимо на Поштову площу, важливий центральний майдан у старому місті, що мандрується крізь часи.", "https://guide.kyivcity.gov.ua/places/postova-plosa/street-view"],
                ["7.jpg", "НАЗВА ТУРУ : МИХАЙЛІВСЬКА ПЛОЩА", "Ласкаво просимо на Михайлівську площу, одну з найвизначніших і культурних площ Києва.", "https://guide.kyivcity.gov.ua/places/mihajlivska-plosa/street-view"],
                ["8.jpg", "НАЗВА ТУРУ : КОНТРАКТОВА ПЛОЩА", "Ласкаво просимо на Контрактову площу, одну з найжвавіших, найцікавіших та історичних площ Києва.", "https://guide.kyivcity.gov.ua/places/kontraktova-plosa/street-view"],
                ["9.jpg", "НАЗВА ТУРУ : ХРЕЩАТИК-UA", "Ласкаво просимо на ХРЕЩАТИК місце, де пульсує життя української столиці.", "https://guide.kyivcity.gov.ua/places/kontraktova-plosa/street-view"],
                ["10.jpg", "НАЗВА ТУРУ : ПЕЙЗАЖНА АЛЕЯ", "Ласкаво просимо на Пейзажну алею місце в серці Києва, де природа вражає.", "https://guide.kyivcity.gov.ua/places/pejzazna-alea/street-view"],
                ["11.jpg", "НАЗВА ТУРУ : ЖИТНІЙ РИНОК", "Ласкаво просимо на Житній ринок, справжнє культурне серце Києва.", "https://guide.kyivcity.gov.ua/places/zitnij-rinok/street-view"],
                ["12.jpg", "НАЗВА ТУРУ : АРКА СВОБОДИ", "Ласкаво просимо до Арки свободи, величного символу незалежності.", "https://guide.kyivcity.gov.ua/places/arka-druzbi-narodiv/street-view"],
            ];
            
            foreach ($tours as $tour) {
                echo '<div class="col-lg-3 col-md-4 col-sm-6 mb-4">'; echo '<div class="card mx-auto" style="width: 18rem;">'; echo '<img src="images/online-tour-pictures/online-tour-picture-' . $tour[0] . '" class="card-img-top">'; echo '<div class="card-body">'; echo '<h5 class="card-title" style="width: 100%; text-align: center;">' . $tour[1] . '</h5>'; echo '<p class="card-text text-center">' . $tour[2] . '</p>'; echo '<a href="' . $tour[3] . '" class="btn btn-primary d-block mx-auto"> >>> ПЕРЕЙТИ <<< </a>'; echo '</div>'; echo '</div>'; echo '</div>';
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body> </html>