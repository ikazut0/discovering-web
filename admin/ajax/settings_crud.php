<?php require('../include/db_config.php'); require('../include/essentials.php'); adminLoginCheck();

if (isset($_POST['get_general'])) {
    $q = "SELECT * FROM `admin_settings` WHERE `settings_id`=?";
    $values = [1];
    $res = selectData($q, $values, "i");
    $data = mysqli_fetch_assoc($res);
    $json_data = json_encode($data);
    echo $json_data;
}

if (isset($_POST['upd_general'])) {
    $frm_data = filtrationData($_POST);

    $q = "UPDATE `admin_settings` SET `site_title`=? WHERE `settings_id`=?";
    $values = [$frm_data['site_title'], 1];
    $res = updateData($q, $values, 'si');
    echo $res;
}

if (isset($_POST['upd_shutdown'])) {
    $frm_data = ($_POST['upd_shutdown'] == 0) ? 1 : 0;

    $q = "UPDATE `admin_settings` SET `site_shutdown`=? WHERE `settings_id`=?";
    $values = [$frm_data, 1];
    $res = updateData($q, $values, 'ii');
    echo $res;
}

if (isset($_POST['get_contacts'])) {
    $q = "SELECT * FROM `admin_contact` WHERE `contact_id`=?";
    $values = [1];
    $res = selectData($q, $values, "i");
    $data = mysqli_fetch_assoc($res);
    $json_data = json_encode($data);
    echo $json_data;
}

if (isset($_POST['upd_contacts'])) {
    $frm_data = filtrationData($_POST);

    $q = "UPDATE `admin_contact` SET `contact_address`=?, `contact_phone_one`=?, `contact_phone_two`=?, `contact_email`=?, `contact_facebook`=?, `contact_instagram`=?, `contact_iframe`=? WHERE `contact_id`=?";
    
    $values = [
        $frm_data['contact_address'],
        $frm_data['contact_phone_one'],
        $frm_data['contact_phone_two'],
        $frm_data['contact_email'],
        $frm_data['contact_facebook'],
        $frm_data['contact_instagram'],
        $frm_data['contact_iframe'],
        1
    ];

    $res = updateData($q, $values, 'sssssssi');
    echo $res;
}

?>