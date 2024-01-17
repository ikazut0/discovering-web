<?php require('../include/db_config.php'); require('../include/essentials.php'); adminLoginCheck();

if (isset($_POST['add_feature'])) {
    $frm_data = filtrationData($_POST);

    $q = "INSERT INTO `admin_feature`(`feature_name`) VALUES (?)";

    $values = [$frm_data['feature_name']];
    $res = insert($q, $values, 's');
    echo $res;
}

if (isset($_POST['get_features'])) {
    $res = selectAll('admin_feature');

    $i = 1;
    while ($row = mysqli_fetch_assoc($res)) {
        echo <<< data
        <tr class='align-middle'>
            <td>$i</td>
            <td>$row[feature_name]</td>
            <td class="text-end pe-4">
                <button type="button" onclick="rem_feature($row[feature_id])" class="btn btn-danger btn-sm shadow-none mt-2"> <i class="bi bi-trash"></i> </button>
            </td>
        </tr>
        data;
        $i++;
    }
}


if (isset($_POST['rem_feature'])) {
    $frm_data = filtrationData($_POST);
    $values = [$frm_data['rem_feature']];

    $check_q = selectData('SELECT * FROM `admin_tours_feature` WHERE `feature_id`=?', [$frm_data['rem_feature']], 'i');

    if(mysqli_num_rows($check_q)==0) {
        $q = "DELETE FROM `admin_feature` WHERE `feature_id`=?";
        $res = delete($q, $values, 'i');
        echo $res;
    } else {
        echo 'tour_added';
    }
}

?>