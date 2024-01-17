<?php require('../include/db_config.php'); require('../include/essentials.php'); adminLoginCheck();

if (isset($_POST['add_image'])) {
    $img_r = uploadImage($_FILES['picture'], CAROUSEL_FOLDER);

    if ($img_r == 'inv_img') {
        echo $img_r;
    } elseif ($img_r == 'inv_size') {
        echo $img_r;
    } elseif ($img_r == 'upd_failed') {
        echo $img_r;
    } else {
        $q = "INSERT INTO `admin_carousel`(`carousel_image`) VALUES (?)";
        $values = [$img_r];
        $res = insert($q, $values, 's');
        echo $res;
    }
}

if (isset($_POST['get_carousel'])) {
    $res = selectAll('admin_carousel');

    while ($row = mysqli_fetch_assoc($res)) {
        $path = CAROUSEL_IMG_PATH;

        echo <<< data
        <div class="col-md-2 mb-3">
            <div class="card text-white border-0">
                <img src="$path$row[carousel_image]" class="card-img">
                <div class="card-img-overlay">
                    <div class="d-flex justify-content-center mt-1">
                        <button type="button" onclick="rem_image($row[settings_id])" class="btn btn-danger btn-sm shadow-none mt-2"> <i class="bi bi-trash"></i> </button>
                    </div>
                </div>
            </div>
        </div>
        data;
    }
}

if (isset($_POST['rem_image'])) {
    $frm_data = filtrationData($_POST);
    $values = [$frm_data['rem_image']];

    $pre_q = "SELECT * FROM `admin_carousel` WHERE `settings_id`=?";
    $res = selectData($pre_q, $values, 'i');
    $img = mysqli_fetch_assoc($res);

    if (deleteImage($img['carousel_image'], CAROUSEL_FOLDER)) {
        $q = "DELETE FROM `admin_carousel` WHERE `settings_id`=?";
        $res = delete($q, $values, 'i');
        echo $res;
    } else {
        echo 0;
    }
}

?>