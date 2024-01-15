<?php require('include/essentials.php'); adminLoginCheck(); ?>

<!DOCTYPE HTML>
<html lang="UA">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>aPanel - ПАНЕЛЬ УПРАВЛІННЯ</title>

    <?php require('include/links.php'); ?>

    <style>
        #dashboard-menu {
            position: fixed;
            height: 100%;
        }

        @media screen and (max-width: 991px) {
            #dashboard-menu {
                height: auto;
            }

            #main-content {
                margin-top: 60px;
            }
        }
    </style>

</head>

<body class="bg-light">

    <?php require('include/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
            </div>
        </div>
    </div>

    <?php require('include/scripts.php'); ?>

    <script src="scripts/greeting-admin.js"></script>

</body>
</html>