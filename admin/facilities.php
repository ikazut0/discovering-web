<?php require('include/db_config.php'); require('include/essentials.php'); adminLoginCheck();

if (isset($_GET['question_watch'])) {
    $frm_data = filtrationData($_GET);

    if ($frm_data['question_watch'] == 'all') {
        $q = "UPDATE `admin_question` SET `question_watch`=?";
        $values = [1];

        if (updateData($q, $values, 'i')) {
            alert('success', 'ПОВІДОМЛЕННЯ УСПІШНО ПОЗНАЧЕНО, ЯК ПРОЧИТАНЕ!', 'center');
        }
    } else {
        $q = "UPDATE `admin_question` SET `question_watch`=? WHERE `question_id`=?";
        $values = [1, $frm_data['question_watch']];

        if (updateData($q, $values, 'ii')) {
            alert('success', 'ПОВІДОМЛЕННЯ УСПІШНО ПОЗНАЧЕНО, ЯК ПРОЧИТАНЕ!', 'center');
        }
    }
}

if (isset($_GET['del'])) {
    $frm_data = filtrationData($_GET);

    if ($frm_data['del'] == 'all') {
        $q = "DELETE FROM `admin_question`";

        if (mysqli_query($connection_info, $q)) {
            alert('success', 'ПОВІДОМЛЕННЯ УСПІШНО ПОЗНАЧЕНО, ЯК ПРОЧИТАНЕ!', 'center');
        }
    } else {
        $q = "DELETE FROM `admin_question` WHERE `question_id`=?";
        $values = [$frm_data['del']];

        if (delete($q, $values, 'i')) {
            alert('success', 'ПОВІДОМЛЕННЯ УСПІШНО ПОЗНАЧЕНО, ЯК ПРОЧИТАНЕ!', 'center');
        }
    }
}
?>

<!DOCTYPE HTML>
<html lang="UA">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>aPanel - НАЛАШТУВАННЯ</title>
    <?php require('include/links.php'); ?>
    <style>body{background-color:#f8f9fa;}#dashboard-menu{position:fixed;height:100%;z-index:11;}@media screen and (max-width:991px){#dashboard-menu{height:auto;width:100%;}#main-content{margin-top:60px;}.card{border-radius:15px;margin-bottom:20px;}.btn{border-radius:10px;}.modal-content{border-radius:15px;}}table{width:100%;border-collapse:collapse;position:relative;z-index:1;}th,td{padding:12px;text-align:left;position:relative;z-index:2;}.tooltip-inner{max-width:300px;padding:10px;color:#fff;text-align:left;background-color:#343a40;border-radius:8px;font-size:14px;box-shadow:0 2px 10px rgba(0,0,0,0.1);}.tooltip.bs-tooltip-top .arrow::before,.tooltip.bs-tooltip-bottom .arrow::before{border-top-color:#343a40;}.alert-center{position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:100;}.sticky-top{z-index:1000;}</style>
</head>

<body class="bg-light">

    <?php require('include/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">FACILITIES PAGE</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">НАЛАШТУВАННЯ ОФОРМЛЕННЯ САЙТУ [SLIDER SECTION] :</h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#feature-s">
                                <i class="bi bi-plus-square"></i> ЗАВАНТАЖИТИ ФОТО | UPLOAD PHOTO
                            </button>
                        </div>

                        <div class="table-responsive-md mx-auto text-center" style="max-height: 350px; overflow-y: scroll;">
                            <table class="table">
                                <thead class="sticky-top">
                                    <tr class="bg-dark text-light">
                                        <th scope="col" style="white-space: nowrap;">#</th>
                                        <th scope="col" style="white-space: nowrap;">ЕЛ. АДРЕСА : </th>
                                        <th scope="col" class="text-end" style="white-space: nowrap;">ВИДАЛИТИ МОЖЛИВОСТІ? : </th>
                                    </tr>
                                </thead>
                                <tbody id="features-data">
                                </tbody>
                            </table>
                        </div>

                        <div class="modal fade" id="feature-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form id="feature_s_form">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">ADD FEATURE :</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Name</label>
                                                <input type="text" name="feature_name" class="form-control shadow-none">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">ЗАКРИТИ</button>
                                            <button type="submit" class="btn custom-bg text-white shadow-none">ЗБЕРЕГТИ</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('include/scripts.php'); ?>

    <script src="scripts/feature-info.js"></script>

    <script src="scripts/settings-admin.js"></script>
    <script src="scripts/greeting-admin.js"></script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof bootstrap !== 'undefined') {
                var tooltips = new bootstrap.Tooltip(document.body, {
                    selector: '[data-bs-toggle="tooltip"]'
                });
            } else {
                console.error('Bootstrap is not defined. Make sure you have included Bootstrap JS.');
            }
        });
    </script>

    <script src="scripts/greeting-admin.js"></script>
    <script src='include/scripts.php'></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</body> </html>