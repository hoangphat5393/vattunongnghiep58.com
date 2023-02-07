<?php require_once('post_c.php');?>

<?php
    $atz = new post_controller();
    
    $post = $atz->get_post($atz->id);
    if (!empty($post)) {
        $cat = $atz->get_cat($post['Post_Cat']);

        // Update View
        $atz->update_view($post['Post_ID'],$post['Post_View_vi']);
    }
    if(!empty($post)){
        $keywords = explode(',', $post['Post_Keywords_vi']);
    }
    $relative_posts = $atz->get_relative_posts($atz->id);
?>

<!DOCTYPE html>
<html lang="vi">

    <head>       
        <!-- HEAD -->
        <?php include('modules/head.php') ?>
        <!-- END HEAD -->

        <!-- SEO -->
        <meta name="description" content="<?=$post['Post_Description_vi']?>">
        <meta name="keywords" content="<?=$post['Post_Keywords_vi']?>">

        <title><?=$post['Post_Title_vi']?> | <?=SETTING['Setting_Title']?></title>
    </head>

    <body>

        <!-- HEADER -->
        <header>
            <?php include('modules/header.php');?>
        </header>
        <!-- END HEADER -->

        <!-- MENU -->
        <?php include('modules/menu.php')?>
        <!-- MENU HEADER -->

        <!-- POST -->
        <div class="container mt-4">

            <div class="row mt-3">
                <div class="col-lg-3 order-2 order-lg-1 main-cat">
                    <div class="row mb-3">
                        <?php include('modules/left_sidebar.php')?>
                    </div>
                </div>

                <div class="col-lg-9 order-1 order-lg-2 mb-5 main-product">

                    <div class="row mb-3">
                        <div class="col-12 post-detail">
                            <?php if (!empty($post)): ?>
                                <h3 class="mb-4"><?=$cat['Cat_Name_vi']?></h3>
                                <h1><?=$post['Post_Title_vi']?></h1>
                                
                                <div class="follow mb-3">
                                    <i class="far fa-calendar-alt"></i> <i><?=date('d-m-Y',$post['Post_Created'])?></i>&emsp;
                                    <i class="fa fa-eye"></i> <i>Lượt xem: <?=$post['Post_View_vi']?></i>
                                </div>

                                <p><?=$post['Post_Description_vi']?></p>

                                <!-- <h2>Chi tiết</h2> -->
                                <div>
                                    <?=$post['Post_Content_vi']?>
                                </div>
                            <?php else: ?>
                                <p>Không có bài viết này</p>
                            <?php endif ?>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!-- END POST -->

        <?php include('modules/footer.php') ?>
        
        <!-- JS LIB-->
        <?php include('modules/js.php') ?>
        <!-- END JS LIB -->
    </body>
</html>