<?php

defined('SITE_URL') || define('SITE_URL', 'http://127.0.0.1/discovering-web/');
defined('CAROUSEL_IMG_PATH') || define('CAROUSEL_IMG_PATH', SITE_URL.'images/carousel-pictures/');
defined('TOURS_IMG_PATH') || define('TOURS_IMG_PATH', SITE_URL.'images/tour-pictures/');
defined('UPLOAD_IMAGE_PATH') || define('UPLOAD_IMAGE_PATH', $_SERVER['DOCUMENT_ROOT'].'/discovering-web/images/');
defined('CAROUSEL_FOLDER') || define('CAROUSEL_FOLDER', 'carousel-pictures/');
defined('TOUR_FOLDER') || define('TOUR_FOLDER', 'tour-pictures/');
defined('USERS_FOLDER') || define('USERS_FOLDER', 'users-pictures/');


if (!function_exists('adminLoginCheck')) {
    function adminLoginCheck() {
    session_start();
    if (!(isset($_SESSION['adminLoginCheck']) && $_SESSION['adminLoginCheck'] == true)) {
        echo "<script>window.location.href='index.php';</script>";
        exit;
    } 
}
}

if (!function_exists('adminHome')) {
function adminHome($url) {
    echo "<script>window.location.href='$url';</script>";
    exit;
}
} 

if (!function_exists('alertHeader')) {
function alertHeader($message) {
    echo '
    <div class="alert alert-warning alert-dismissible fade show text-center custom-alert" role="alert">
        <strong class="me-3">' . $message . '</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
} 

if (!function_exists('uploadImage')) {
function uploadImage($image, $folder) {
    $valid_mime = ['image/jpeg', 'image/png', 'image/webp'];
    $img_mime = $image['type'];

    if (!in_array($img_mime, $valid_mime)) {
        return 'inv_img';
    } elseif (($image['size'] / (1024 * 1024)) > 10) {
        return 'inv_size';
    } else {
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $rname = 'IMG_' . random_int(11111, 99999) . ".$ext";

        $img_path = UPLOAD_IMAGE_PATH . $folder . $rname;
        if (move_uploaded_file($image['tmp_name'], $img_path)) {
            return $rname;
        } else {
            return 'upd_failed';
        }
    }
    }
}

if (!function_exists('deleteImage')) {
function deleteImage($image, $folder) {
    if (unlink(UPLOAD_IMAGE_PATH.$folder.$image)) {
        return true;
    } else {
        return false;
    }
}
}

if (!function_exists('uploadUserImage')) {
function uploadUserImage($image) {
    $valid_mime = ['image/jpeg', 'image/png'];
    $img_mime = $image['type'];

    if (!in_array($img_mime, $valid_mime)) {
        return 'inv_img';
    } else {
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $rname = 'IMG_' . random_int(11111, 99999) . ".jpeg";

        $img_path = UPLOAD_IMAGE_PATH . USERS_FOLDER . $rname;

        if($ext == 'png' || $ext == 'PNG') {
            $img = imagecreatefrompng($image['tmp_name']);
        } else {
            $img = imagecreatefromjpeg($image['tmp_name']);
        }

        if (imagejpeg($img, $img_path, 75)) {
            return $rname;
        } else {
            return 'upd_failed';
        }
    }
    }
}

if (!function_exists('getUserInfoByEmail')) {
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
}

?>