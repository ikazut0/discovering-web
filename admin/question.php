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

    <style>
        body {
            background-color: #f8f9fa;
        }

        #dashboard-menu {
            position: fixed;
            height: 100%;
            z-index: 11;
        }

        @media screen and (max-width: 991px) {
            #dashboard-menu {
                height: auto;
                width: 100%;
            }

            #main-content {
                margin-top: 60px;
            }
        }

        .card {
            border-radius: 15px;
            margin-bottom: 20px;
        }

        .btn {
            border-radius: 10px;
        }

        .modal-content {
            border-radius: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            position: relative;
            z-index: 1;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            position: relative;
            z-index: 2;
        }

        .tooltip-inner {
        max-width: 300px; /* Set the maximum width of the tooltip */
        padding: 10px;
        color: #fff;
        text-align: left;
        background-color: #343a40;
        border-radius: 8px;
        font-size: 14px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .tooltip.bs-tooltip-top .arrow::before,
        .tooltip.bs-tooltip-bottom .arrow::before {
        border-top-color: #343a40;
        }

        .alert-center {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 100;
        }

        .sticky-top {
            z-index: 1000;
        }
    </style>
</head>

<body class="bg-light">

    <?php require('include/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">СПИСОК ЗАПИТАНЬ ВІД КОРИСТУВАЧІВ САЙТУ :</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="text-end mb-4">
                            <a href="?question_watch=all" class="btn btn-dark rounded-pill shadow-none btn-sm">
                                <i class="bi bi-check-all"></i> ПОЗНАЧИТИ ВСІ ПОВІДОМЛЕННЯ, ЯК ПРОЧИТАНІ
                            </a>
                            <a href="?del=all" class="btn btn-danger rounded-pill shadow-none btn-sm">
                                <i class="bi bi-trash"></i> ВИДАЛИТИ ВСІ ПОВІДОМЛЕННЯ
                            </a>
                        </div>

                        <div class="table-responsive-md mx-auto text-center" style="max-height: 480px; overflow-y: scroll;">
                            <table class="table">
                                <thead class="sticky-top">
                                    <tr class="bg-dark text-light">
                                        <th scope="col" style="white-space: nowrap;">#</th>
                                        <th scope="col" style="white-space: nowrap;">ІДЕНТИФІКАТОР : </th>
                                        <th scope="col" style="white-space: nowrap;">ЕЛ. АДРЕСА : </th>
                                        <th scope="col" style="white-space: nowrap;">ПОВІДОМЛЕННЯ : </th>
                                        <th scope="col" style="white-space: nowrap;">ДАТА : </th>
                                        <th scope="col" style="white-space: nowrap;">РЕЗУЛЬТАТ : </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $q = "SELECT * FROM `admin_question` ORDER BY `question_id` DESC";
                                    $data = mysqli_query($connection_info, $q);
                                    $i = 1;

                                    while ($row = mysqli_fetch_assoc($data)) {
                                        $question_watch = '';

                                        if ($row['question_watch'] != 1) {
                                            $question_watch = "<a href='?question_watch=$row[question_id]' class='btn btn-sm rounded-pill btn-primary btn-custom'>ПОВІДОМЛЕННЯ ПРОЧИТАНО</a> <br>";
                                        }

                                        $question_watch .= "<a href='?del=$row[question_id]' class='btn btn-sm rounded-pill btn-danger btn-danger btn-custom mt-2'>ВИДАЛИТИ ПОВІДОМЛЕННЯ?</a> <br>";

                                        $message = strlen($row['question_message']) > 50 ? substr($row['question_message'], 0, 50) . "..." : $row['question_message'];

                                        echo <<< query
                                            <tr>
                                                <td>$i</td>
                                                <td>$row[question_name]</td>
                                                <td>$row[question_email]</td>
                                                <td data-bs-toggle="tooltip" title="$row[question_message]">$message</td>
                                                <td style="white-space: nowrap;">$row[question_date]</td>
                                                <td>$question_watch</td>
                                            </tr>
                                        query;
                                        $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Ensure that bootstrap is defined before initializing Tooltip
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

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>