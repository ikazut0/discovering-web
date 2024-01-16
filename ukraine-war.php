<?php require 'admin/include/db_config.php'; require 'admin/include/essentials.php'; session_start(); 

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

<body class="bg-light">
    <?php require 'include/header.php'; ?>

    <br><div style="text-align: center; font-size: 24px; font-weight: bold; margin: 0 20px;">ІСТОРІЯ ВІЙНИ, АБО ЯК ВОНО БУЛО - #WARINUA2022</div>

    <br><p style="text-align: center; font-size: 16px; margin: 0 20px;">ВСІ ПРАВА НА VR-ТУР НАЛЕЖАТЬ - WAR.CITY</p><br>

    <div id="street-view-container" style="width: calc(100vw - 40px); height: 100vh; margin: 0 20px;">
        <iframe id="pano-item" src="https://tourmkr.com/F1CvuetAd8/38960695p&11.81h&82.55t" width="100%" height="100%" allowfullscreen=""></iframe>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body> </html>