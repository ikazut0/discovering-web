<?php require('../include/db_config.php'); require('../include/essentials.php'); adminLoginCheck();

if (isset($_POST['add_tour'])) {
    $frm_data = filtrationData($_POST);
    $flag = 0;
    $features = filtrationData(json_decode($_POST['tour_features']));

    $q1 = "INSERT INTO `admin_tours` (`tour_name`, `tour_area`, `tour_price`, `tour_quantity`, `tour_adult`, `tour_children`, `tour_desc`) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $values = [$frm_data['tour_name'], $frm_data['tour_area'], $frm_data['tour_price'], $frm_data['tour_quantity'], $frm_data['tour_adult'], $frm_data['tour_children'], $frm_data['tour_desc']];

    if (insert($q1, $values, 'siiiiis')) {
        $flag = 1;
    }

    $tour_id = mysqli_insert_id($connection_info);
    $q2 = "INSERT INTO `admin_tours_feature`(`tour_id`, `feature_id`) VALUES (?, ?)";

    if ($stmt = mysqli_prepare($connection_info, $q2)) {
        foreach ($features as $f) {
            mysqli_stmt_bind_param($stmt, 'ii', $tour_id, $f);
            mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $flag = 0;
        die('TEST');
    }

    if ($flag) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['get_tour'])) {
    $res = selectData("SELECT * FROM `admin_tours` WHERE `tour_removed`=?", [0], 'i');
    $i = 1;
    $data = "";

    while ($row = mysqli_fetch_assoc($res)) {
        if ($row['tour_status'] == 1) {
            $tour_status = "<button onclick='change_status($row[tour_id], 0)' class='btn btn-success btn-sm shadow-none'>ВКЛ.</button>";
        } else {
            $tour_status = "<button onclick='change_status($row[tour_id], 1)' class='btn btn-danger btn-sm shadow-none'>ВИКЛ.</button>";
        }

        $data .= "
        <tr class='align-middle'>
         <td class='text-center' style='font-size: 13px;'>$i</td>
         <td class='text-center' style='font-size: 13px;'>$row[tour_name]</td>
         <td class='text-center' style='font-size: 13px;'>$row[tour_area] ЛЮД. </td>
         <td class='text-center' style='font-size: 13px;'>
            <div class='badge-container'>
                <span class='badge rounded-pill bg-light text-dark'>
                ДОРОСЛИХ : $row[tour_adult]
                </span>
                <span class='badge rounded-pill bg-light text-dark'>
                ДІТЕЙ : $row[tour_children]
                </span>
            </div>
         </td>
         <td class='text-center' style='font-size: 13px;'>$row[tour_price]₴</td>
         <td class='text-center' style='font-size: 13px;'>$row[tour_quantity]</td>
         <td class='text-center' style='font-size: 13px;'>$tour_status</td>
         <td class='text-center'>
         <div class='d-flex justify-content-end'>
         <button type='button' onclick='edit_tour($row[tour_id])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit_tour'> <i class='bi bi-pencil-square'></i> </button>
         <button type='button' onclick=\"tour_images($row[tour_id], '$row[tour_name]')\" class='btn btn-info shadow-none btn-sm ms-2' data-bs-toggle='modal' data-bs-target='#tour_image'> <i class='bi bi-images'></i> </button>
         <button type='button' onclick='remove_tour($row[tour_id])' class='btn btn-danger shadow-none btn-sm ms-2'> <i class='bi bi-trash'></i> </button>
         </div>
         </td>
         </tr>
        ";
        $i++;
    }
    echo $data;
}

if (isset($_POST['change_status'])) {
    $frm_data = filtrationData($_POST);
    $q = "UPDATE `admin_tours` SET `tour_status`=? WHERE `tour_id`=?";
    $v = [$frm_data['value'], $frm_data['change_status']];
    if (updateData($q, $v, 'ii')) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['get_tour_info'])) {
    $frm_data = filtrationData($_POST);
    $res1 = selectData("SELECT * FROM `admin_tours` WHERE `tour_id`=?", [$frm_data['get_tour_info']], 'i');
    $res2 = selectData("SELECT * FROM `admin_tours_feature` WHERE `tour_id`=?", [$frm_data['get_tour_info']], 'i');
    $tourdata = mysqli_fetch_assoc($res1);
    $features = [];

    if (mysqli_num_rows($res2) > 0) {
        while ($row = mysqli_fetch_assoc($res2)) {
            array_push($features, $row['feature_id']);
        }
    }

    $data = ["tourdata" => $tourdata, "features" => $features];
    $data = json_encode($data);

    echo $data;
}

if (isset($_POST['edit_tour'])) {
    $frm_data = filtrationData($_POST);
    $flag = 0;
    $features = filtrationData(json_decode($_POST['tour_features']));
    $q1 = "UPDATE `admin_tours` SET `tour_name`=?,`tour_area`=?,`tour_price`=?,`tour_quantity`=?,`tour_adult`=?,`tour_children`=?,`tour_desc`=? WHERE `tour_id`=?";
    $values = [$frm_data['tour_name'], $frm_data['tour_area'], $frm_data['tour_price'], $frm_data['tour_quantity'], $frm_data['tour_adult'], $frm_data['tour_children'], $frm_data['tour_desc'], $frm_data['tour_id']];
    
    if (updateData($q1, $values, 'siiiiisi')) {
        $flag = 1;
    }

    $del_features = delete("DELETE FROM `admin_tours_feature` WHERE `tour_id`=?", [$frm_data['tour_id']], 'i');

    if (!($del_features)) {
        $flag = 0;
    } else {
        $q2 = "INSERT INTO `admin_tours_feature`(`tour_id`, `feature_id`) VALUES (?, ?)";

        if ($stmt = mysqli_prepare($connection_info, $q2)) {
            foreach ($features as $f) {
                mysqli_stmt_bind_param($stmt, 'ii', $frm_data['tour_id'], $f);
                mysqli_stmt_execute($stmt);
            }
            $flag = 1;
            mysqli_stmt_close($stmt);
        } else {
            $flag = 0;
            die('TEST');
        }
    }

    if ($flag) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['add_image'])) {

    $frm_data = filtrationData($_POST);

    $img_r = uploadImage($_FILES['image'], TOUR_FOLDER);

    if ($img_r == 'inv_img') {
        echo $img_r;
    } elseif ($img_r == 'inv_size') {
        echo $img_r;
    } elseif ($img_r == 'upd_failed') {
        echo $img_r;
    } else {
        $q = "INSERT INTO `admin_tour_images` (`tour_id`, `tour_image`) VALUES (?, ?)";
        $values = [$frm_data['tour_id'], $img_r];
        $res = insert($q, $values, 'is');
        echo $res;
    }
}

if (isset($_POST['get_tour_images'])) {

    $frm_data = filtrationData($_POST);

    $res = selectData("SELECT * FROM `admin_tour_images` WHERE `tour_id`=?", [$frm_data['get_tour_images']], 'i');

    $path = TOURS_IMG_PATH;

    while($row = mysqli_fetch_assoc($res)) {
        if($row['tour_thumb'] == 1) {
            $thumb_btn = "<i class='bi bi-check-lg text-light bg-success px-2 py-1 rounded fs-5'></i>";
        } else {
            $thumb_btn = "<button onclick='thumb_image($row[image_id], $row[tour_id])' class='btn btn-secondary shadow-none'>
            <i class='bi bi-check-lg'></i>
        </button>";
        }

        echo <<< data
        <tr class='align-middle'>
            <td><img src='$path$row[tour_image]' class='img-fluid'></td>
            <td class="text-center">$thumb_btn</td>
            <td class="text-center">
            <button onclick='rem_image($row[image_id], $row[tour_id])' class='btn btn-danger shadow-none'>
            <i class='bi bi-trash'></i>
        </button>
            </td>       
        </tr>
    data;
    }
}

if(isset($_POST['rem_image'])) {

    $frm_data = filtrationData($_POST);

    $values = [$frm_data['image_id'], $frm_data['tour_id']];

    $pre_q = "SELECT * FROM `admin_tour_images` WHERE `image_id`=? AND `tour_id`=?";

    $res = selectData($pre_q, $values, 'ii');

    $img = mysqli_fetch_assoc($res);

    if(deleteImage($img['tour_image'], TOUR_FOLDER)) {
        $q = "DELETE FROM `admin_tour_images` WHERE `image_id`=? AND `tour_id`=?";
        $res = delete($q, $values, 'ii');
        echo $res;
    } else {
        echo 0;
    }
}

if(isset($_POST['thumb_image'])) {

    $frm_data = filtrationData($_POST);

    $pre_q = "UPDATE `admin_tour_images` SET `tour_thumb`=? WHERE `tour_id`=?";

    $pre_v = [0, $frm_data['tour_id']];

    $pre_res = updateData($pre_q, $pre_v, 'ii');

    $q = "UPDATE `admin_tour_images` SET `tour_thumb`=? WHERE `image_id`=? AND `tour_id`=?";

    $v = [1, $frm_data['image_id'], $frm_data['tour_id']];

    $res = updateData($q, $v, 'iii');

    echo $res;
}

if(isset($_POST['remove_tour'])) {

    $frm_data = filtrationData($_POST);

    $res1 = selectData("SELECT * FROM `admin_tour_images` WHERE `tour_id`=?", [$frm_data['tour_id']], 'i');

    while($row = mysqli_fetch_assoc($res1)) {
        deleteImage($row['tour_image'], TOUR_FOLDER);
    }

    $res2 = delete("DELETE FROM `admin_tour_images` WHERE `tour_id`=?", [$frm_data['tour_id']], 'i');

    $res3 = delete("DELETE FROM `admin_tours_feature` WHERE `tour_id`=?", [$frm_data['tour_id']], 'i');

    $res4 = updateData("UPDATE `admin_tours` SET `tour_removed`=? WHERE `tour_id`=?", [1, $frm_data['tour_id']], 'ii');

    if($res2 || $res3 || $res4) {
        echo 1;
    } else {
        echo 0;
    }
}
?>