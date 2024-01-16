<?php require('admin/include/db_config.php'); require('admin/include/essentials.php'); session_start(); ?>

<!DOCTYPE HTML>
<HTML lang="UA">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('include/links.php'); ?>
    <link rel="shortcut icon" href="https://cdn.icon-icons.com/icons2/1928/PNG/512/iconfinder-compass-direction-maps-holiday-vacation-icon-4602027_122100.png" type="image/x-icon">
    <title>DISCOVERING.UA</title>
</head>

<body class="bg-light"> <?php require('include/header.php'); ?>

<?php

    if (!isset($_GET['tour_id'])) {
        adminHome('tour.php');
    }
    
    $data = filtrationData($_GET);
    
    $tour_res = selectData("SELECT * FROM `admin_tours` WHERE `tour_id`=? AND `tour_status`=? AND `tour_removed`=?", [$data['tour_id'], 1, 0], 'iii');
    
    if (mysqli_num_rows($tour_res) == 0) {
        adminHome('tour.php');
    }
    
    $tour_data = mysqli_fetch_assoc($tour_res);
    
    ?>
    
    <div class="container">
        <div class="row">
            <div class="col-12 my-5 mb-3 px-4">
                <h2 class="fw-bold">НАЗВА : <?php echo $tour_data['tour_name'] ?></h2>
            </div>
            
            <div class="col-lg-7 col-md-12 px-4">
                <div id="tourCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        
                        $tour_img = TOURS_IMG_PATH . "empty-image-alert.gif";
                        
                        $img_q = mysqli_query($connection_info, "SELECT * FROM `admin_tour_images` WHERE `tour_id`='$tour_data[tour_id]'");
                        
                        if (mysqli_num_rows($img_q) > 0) {
                            $active_class = 'active';
                            
                            while ($img_res = mysqli_fetch_assoc($img_q)) {
                                echo "<div class='carousel-item $active_class'><img src='" . TOURS_IMG_PATH . $img_res['tour_image'] . "' class='d-block w-100 rounded' style='max-height: 310px;'></div>";
                                $active_class = '';
                            }
                        } else {
                            echo "<div class='carousel-item active'><img src='$tour_img' class='d-block w-100 rounded' style='max-height: 310px;'></div>";
                        }
                        ?>
                        </div>
                          <button class="carousel-control-prev" type="button" data-bs-target="#tourCarousel" data-bs-slide="prev"></button>
                          <button class="carousel-control-next" type="button" data-bs-target="#tourCarousel" data-bs-slide="next"></button>
                        </div>
                    </div>
                    
                    <div class="col-lg-5 col-md-12 px-4">
                        <div class="card mb-4 border-0 shadow-sm rounded-3">
                            <div class="card-body">
                                <?php
                                
                                echo <<< price
                                <h4>ЦІНА ТУРУ : $tour_data[tour_price] ₴</h4>
                                <p class="mb-2" style="font-size: 10px;"><i class="bi bi-exclamation-octagon"></i> ВКАЗАНА ЦІНА ТІЛЬКИ ЗА ТУР </p>
                                price;
                                
                                $fea_q = selectData("SELECT f.feature_name FROM `admin_feature` f INNER JOIN `admin_tours_feature` tfea ON f.feature_id = tfea.feature_id WHERE tfea.tour_id = ?", [$tour_data['tour_id']], 'i');
                                
                                $features_data = "";
                                
                                while ($fea_row = mysqli_fetch_assoc($fea_q)) {
                                    $features_data .= "<span class='badge bg-light text-dark text-wrap me-1 mb-1'>$fea_row[feature_name]</span>";
                                }
                                
                                echo <<< features
                                <div class="mb-3">
                                <h6 class="mb-1">КЛЮЧОВІ ОСОБЛИВОСТІ ДАНОГО ТУРА : </h6>
                                $features_data
                                </div>
                                features;
                                
                                echo <<< people
                                <div class="peoples mb-3">
                                <h6 class="mb-1">ДАНИЙ ТУР ОРІЄНТОВАНИЙ НА : </h6>
                                <span class="badge bg-light text-dark text-wrap">$tour_data[tour_adult] ДОРОСЛИХ</span>
                                <span class="badge bg-light text-dark text-wrap">$tour_data[tour_children] ДИТИНИ</span>
                                </div>
                                people;
                                
                                echo <<< tour_guide
                                <div class="mb-3">
                                <h6 class="mb-1">КІЛЬКІСТЬ ЕКСКУРСОВОДІВ : </h6>
                                <span class="badge bg-light text-dark text-wrap">$tour_data[tour_area] ЕКСКУРСОВОД / ГІД [ІВ]</span>
                                </div>
                                tour_guide;
                                
                                $buttonHTML = '';
                                
                                if ($userLoggedIn) {
                                    $buttonHTML = '<a href="book-tour.php?tour_id=' . $tour_data['tour_id'] . '" class="btn btn-sm w-100 text-white custom-bg shadow-none mb-2">ПРИДБАТИ ТУР 🛍️</a>';
                                }
                                
                                echo $buttonHTML;
                                
                                ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 mt-4 px-4">
                            <div class="mb-5">
                                <h5>ДЕТАЛЬНИЙ ОПИС ТУРА : </h5>
                                <p> <?php echo nl2br($tour_data['tour_desc']); ?> </p>
                            </div>
                            
                            <div class="mt-5">
                                <div id="comments">
                                    <h5>КОМЕНТАРІ : </h5><br>
                                    <?php
                                    
                                    $comments_query = selectData("SELECT c.*, u.user_name FROM `tour_comments` c LEFT JOIN `user_info` u ON c.user_id = u.user_id WHERE c.`tour_id`=?", [$tour_data['tour_id']], 'i');
                                    
                                    while ($comment_data = mysqli_fetch_assoc($comments_query)) {
                                        echo "<p><strong>КОРИСТУВАЧ : </strong> " . $comment_data['user_name'] . "</p>";
                                        echo "<p><strong>КОМЕНТАР : </strong> " . nl2br($comment_data['comment_text']) . "</p>";
                                        echo "<p><strong>ДАТА : </strong> " . nl2br($comment_data['comment_date']) . "</p>";
                                        echo "<hr>";
                                    }
                                    ?>
                                    </div>
                                    
                                    <?php if ($userLoggedIn): ?>
                                        <form method="post" action="process_comment.php">
                                            <div class="mb-3">
                                                <label for="comment_text" class="form-label">👋 ХОЧЕШ ЗАЛИШИТИ КОМЕНТАР?</label>
                                                <textarea class="form-control" id="comment_text" name="comment_text" rows="3" required></textarea>
                                            </div>
                                            <input type="hidden" name="tour_id" value="<?php echo $tour_data['tour_id']; ?>">
                                            <button type="submit" class="btn btn-primary">ВІДПРАВИТИ КОМЕНТАР</button>
                                        </form>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="container">
                                    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
                                        <p class="col-md-4 mb-0 text-muted">© 2024 DISCOVERING.UA, INC</p><a class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none"><img class="bi me-2" width="70" height="32" src="images/footer-logo.png"></a>
                                    </footer>
                                </div>
                                
                                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body> </html>