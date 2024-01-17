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
    echo '</div>';
    exit;
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
    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">ПРО НАС</h2>
        <hr class="mx-auto my-3" style="border: 1px solid #000; width: 85px;">
        <p class="text-center mt-3">
            ДОПОМОГТИ ПОДОРОЖУЮЧИМ ВІДКРИВАТИ НАЙКРАЩЕ, ЩО ПРОПОНУЄ УКРАЇНА
        </p>
    </div>

    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
                <h3 class="mb-3">"DISCOVERING.UA": ПОДОРОЖІ УКРАЇНОЮ</h3>
                <p style="text-align: justify;">
                    Відома компанія, що спеціалізується на організації турів та подорожей по всій Україні. Наша місія - допомогти мандрівникам відкривати найкраще, що пропонує Україна, та забезпечувати незабутніми враженнями від кожної подорожі. Ми пишаємося нашою роботою та прагнемо надавати клієнтам найвищу якість обслуговування.
                </p>
                <p style="text-align: justify;">
                    Наші послуги включають організацію різноманітних турів, від культурних та історичних екскурсій до активних пригодницьких подорожей. Ми співпрацюємо з досвідченими гідами та експертами, щоб забезпечити нашим клієнтам унікальні враження та незабутні пригоди.
                </p>
                <p style="text-align: justify;">
                    Однією з основних цілей "DISCOVERING.UA" є сприяння розвитку туризму в Україні, показуючи світу всю різноманітність та красу цієї країни. Ми прагнемо зробити кожну подорож надзвичайною та освітньою, дозволяючи нашим клієнтам відкрити справжню душу України.
                </p>
            </div>
            <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
                <img src="https://img.freepik.com/free-photo/man-checking-some-important-documents_329181-16082.jpg?size=626&ext=jpg&ga=GA1.1.386372595.1698192000&semt=ais" class="w-100">
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6 col-md-5 mb-4 order-2">
                <h3 class="mb-3">"DISCOVERING.UA": ПОДОРОЖІ СЕЛАМИ</h3>
                <p style="text-align: justify;">
                    DISCOVERING.UA - компанія, яка вкладає свою душу та старання в організацію подорожей, спрямованих на відкриття найчарівніших та найзагадковіших місць в Україні. Ми прагнемо відновити історію та культуру забутих сіл, які стали свідками минулих епох і приховують найцінніші скарби.
                </p>
                <p style="text-align: justify;">
                    Наша команда працює в ужитку з місцевими жителями, що дозволяє нам зануритися в аутентичну атмосферу та розкрити всю красу і унікальність цих місць. Ми вивчаємо історію, традиції та народні звичаї, щоб створити подорожі, які допоможуть вам поглибити своє знайомство з Україною.
                </p>
                <p style="text-align: justify;">
                    DISCOVERING.UA вірить у те, що подорожі до забутих сіл України допомагають зберегти культурну спадщину та зробити ці місця живими. Ми розвиваємо туризм в Україні та робимо це з незменшеним ентузіазмом та вірою в цю чудову країну. Приєднуйтеся до нас та вирушайте у незабутню подорож в минуле та майбутнє України разом із DISCOVERING.UA.
                </p>
            </div>
            <div class="col-lg-5 col-md-5 mb-4 order-1">
                <img src="https://guideme.com.ua/wp-content/uploads/2018/05/nature-of-Ukraine.jpg" class="w-100">
            </div>
        </div>
    </div>

    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <p class="col-md-4 mb-0 text-muted">© 2024 DISCOVERING.UA, Inc</p>
            <a href="#" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                <img class="bi me-2" width="70" height="32" src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ab/%D0%9B%D0%BE%D0%B3%D0%BE%D1%82%D0%B8%D0%BF_%D1%81%D0%B5%D1%80%D0%B2%D1%96%D1%81%D1%83_%D0%94%D1%96%D1%8F.svg/2560px-%D0%9B%D0%BE%D0%B3%D0%BE%D1%82%D0%B8%D0%BF_%D1%81%D0%B5%D1%80%D0%B2%D1%96%D1%81%D1%83_%D0%94%D1%96%D1%8F.svg.png">
            </a>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

</body> </html>