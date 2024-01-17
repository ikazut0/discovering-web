<?php require('admin/include/db_config.php'); require('admin/include/essentials.php'); session_start();

$contact_q = "SELECT * FROM `admin_contact` WHERE `contact_id`=?";
$values = [1];
$contact_r = mysqli_fetch_assoc(selectData($contact_q, $values, 'i'));

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
    <style> .alert-center { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); } </style>
</head>

<body class="bg-light"> <?php require('include/header.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">ЗВОРОТНИЙ ЗВ'ЯЗОК</h2>
        <hr class="mx-auto my-3" style="border: 1px solid #000; width: 85px;">
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4 d-flex flex-column align-items-start">
                    <iframe class="w-100 rounded mb-4" height="320px" src="<?php echo $contact_r['contact_iframe'] ?>" width="600" height="450" style="border:0;" allowfullscreen=""></iframe>
                    <h5 class="mb-0" style="font-size: 13px;">РОЗТАШУВАННЯ ГОЛОВНОГО ОФІСУ : </h5>
                    <br>
                    <p class="mb-0 d-flex align-items-center">
                        <img src="https://media4.giphy.com/media/3BMX9JtQImFgdbZbIV/giphy.gif?cid=82a1493bhdf7lv70mm6kma7zdxjiuczb3i8tvj4ao73d50iq&ep=v1_gifs_search&rid=giphy.gif&ct=s" width="18" height="18" class="mr-3">
                        <?php echo $contact_r['contact_address'] ?>
                    </p>
                    <br>
                    <h5 class="mb-0" style="font-size: 13px;">НОМЕРА ТЕЛЕФОНІВ : </h5>
                    <br>
                    <a class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i> +<?php echo $contact_r['contact_phone_one'] ?>
                    </a>
                    <?php
                    if ($contact_r['contact_phone_two'] != '') {
                        echo "<a class='d-inline-block mb-2 text-decoration-none text-dark'>
                            <i class='bi bi-telephone-fill'></i> +{$contact_r['contact_phone_two']}
                        </a>";
                    }
                    ?>
                    <br>
                    <h5 class="mb-0" style="font-size: 13px;">ЕЛ. АДРЕСА ДЛЯ ЗВОРОТНОГО ЗВ'ЯЗКУ : </h5>
                    <br>
                    <a href="mailto: <?php echo $contact_r['contact_email'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-envelope-at-fill"></i> <?php echo $contact_r['contact_email'] ?>
                    </a>
                </div>
                <br>
                <div class="bg-white p-4 rounded mb-4 ml-auto mt-2">
                    <h5 style="font-size: 13px;">ПОСИЛАННЯ НА СОЦІАЛЬНІ МЕРЕЖІ :</h5>
                    <a href="#" class="d-inline-block mb-3">
                        <span class="badge bg-light text-dark fs-6 p-2">
                            <i class="bi bi-facebook me-1"></i> <?php echo $contact_r['contact_facebook'] ?>
                        </span>
                    </a>
                    <a href="#" class="d-inline-block mb-3">
                        <span class="badge bg-light text-dark fs-6 p-2">
                            <i class="bi bi-instagram me-1"></i> <?php echo $contact_r['contact_instagram'] ?>
                        </span>
                    </a>
                    <a href="#" class="d-inline-block mb-3">
                        <span class="badge bg-light text-dark fs-6 p-2">
                            <i class="bi bi-instagram me-1"></i> <?php echo $contact_r['contact_instagram'] ?>
                        </span>
                    </a>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4 d-flex flex-column">
                    <form method="POST">
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">ПРІЗВИЩЕ ІМ'Я ТА ПО БАТЬКОВІ :</label>
                            <input name="name" required type="text" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">ЕЛ. АДРЕСА :</label>
                            <input name="email" required type="email" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">ВАШЕ ПОВІДОМЛЕННЯ :</label>
                            <textarea name="message" required class="form-control shadow-none" rows="21" style="resize: none;"></textarea>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" name="send" class="btn text-white custom-bg mt-3">НАДІСЛАТИ</button>
                        </div>
                    </form>
                </div>
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
    
    <?php
    function alert($type, $message, $position = 'top', $duration = 4000) {
        echo "<div class='alert alert-$type alert-$position' id='alert'>$message</div>";
        echo "<script>
                setTimeout(function() {
                    var alertElement = document.getElementById('alert');
                    alertElement.style.display = 'none';
                }, $duration);
              </script>";
    }

    if(isset($_POST['send'])) {
        $frm_data = filtrationData($_POST);

        $q = "INSERT INTO `admin_question`(`question_name`, `question_email`, `question_message`) VALUES (?, ?, ?)";
        $values = [$frm_data['name'], $frm_data['email'], $frm_data['message']];

        $res = insert($q, $values, 'sss');

        if ($res == 1) {
            alert('success', 'ПОВІДОМЛЕННЯ ВІДПРАВЛЕНО УСПІШНО!', 'center');
        } else {
            alert('error', 'ПРИ ВІДПРАВЛЕННІ ПОВІДОМЛЕННЯ ВІДБУЛАСЯ ПОМИЛКА!', 'center');
        }
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body> </html>