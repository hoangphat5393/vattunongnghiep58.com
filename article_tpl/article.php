<!-- LIB -->
<?php require_once('article_c.php');?>

<?php
    $atz = new article_controller();
    
    $article = $atz->get_article($atz->id);

    $relative_articles = $atz->get_relative_articles($atz->id); 
?>

<!DOCTYPE html>
<html lang="en">


<head>
    
    <!-- HEAD -->
    <?php include('module/head.php')?>
    <!-- END HEAD -->

    <title><?=SETTING['Setting_Title']?> | <?=SETTING['Setting_Slogan']?></title>
    
    <meta name="description" content="<?=SETTING['Setting_Description']?>">
    <meta name="keywords" content="<?=SETTING['Setting_Keywords']?>">

</head>

<body>

    <!-- PRELOAD -->
    <?php include('module/preload.php')?>
    <!-- END PRELOAD -->

    <!-- HEDAER -->
    <?php include('module/header.php')?>
    <!-- END HEDAER -->

    <div class="page-wrapper">

        <!-- MENU -->
        <?php include('module/menu.php')?>
        <!-- END MENU -->

        <section class="page-header">
            <div class="particles-snow" id="header-snow"></div><!-- /#header-snow.particles-snow -->

            <?php include('module/page_header_bg.php')?>
            <div class="container text-center">
                <h2><?=$article['Article_Title_vi']?></h2>
                <ul class="list-unstyled thm-breadcrumb">
                    <li><a href="<?=$atz->site_url['main']?>">Trang chủ</a></li>
                    <li><span><?=$article['Article_Title_vi']?></span></li>
                </ul><!-- /.thm-breadcrumb -->
            </div><!-- /.container text-center -->
        </section><!-- /.page-header -->

        <section class="blog-standard blog-details">
            <img src="assets/images/shapes/bg-shape-1-1.png" class="section__bg-shape-1" alt="">
            <img src="assets/images/shapes/bg-shape-1-2.png" class="section__bg-shape-2" alt="">
            <img src="assets/images/shapes/bg-shape-1-3.png" class="section__bg-shape-3" alt="">

            <div class="section__bubble-1"></div><!-- /.section__bubble-1 -->
            <div class="section__bubble-2"></div><!-- /.section__bubble-2 -->
            <div class="section__bubble-3"></div><!-- /.section__bubble-3 -->
            <div class="section__bubble-4"></div><!-- /.section__bubble-4 -->
            <div class="section__bubble-5"></div><!-- /.section__bubble-5 -->
            <div class="section__bubble-6"></div><!-- /.section__bubble-6 -->
            <div class="section__bubble-7"></div><!-- /.section__bubble-7 -->
            <div class="section__bubble-8"></div><!-- /.section__bubble-8 -->
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="blog-details__main">
                            <div class="blog-two__meta">
                                <!-- <a href="article.php?id=<?=$article['Article_ID']?>">Sara dodly</a>
                                <span>-</span> -->
                                <!-- <time datetime="2017-03-08">Quốc tế Phụ nữ</time> -->
                                <!-- <p>I have a date on <time datetime="2008-02-14 20:00">Valentines day</time>.</p> -->
                                <?php 
                                    $permanlink = $atz->site_url['main'].'bai-viet/'.$atz->slug($article['Article_Title_vi']).'-'.$article['Article_ID'].'.html';
                                ?>
                                <a href="<?=$permanlink?>"><?=date('d-m-Y', $article['Article_Created'])?></a>
                            </div><!-- /.blog-two__meta -->
                            <h3><?=htmlspecialchars($article['Article_Title_vi'])?></h3>
                            <?=$article['Article_Content_vi']?>
                        </div><!-- /.blog-details__main -->

                    </div><!-- /.col-lg-8 -->

                </div><!-- /.row -->
            </div><!-- /.container -->
        </section><!-- /.blog-standard -->


        <!-- SUBSCRIBE -->
        <?php // include('module/subscribe.php') ?>
        <!-- END SUBSCRIBE -->

        
        <!-- FOOTER -->
        <?php include('module/footer.php') ?>
        <!-- END FOOTER -->



    </div><!-- /.page-wrapper -->

    <!-- MOBILE MENU -->
    <?php include('module/mobile_menu.php') ?>
    <!-- END MOBILE MENU -->

    

    <!-- MAIN JS -->
    <?php include('module/js.php') ?>
    <!-- END MAIN JS -->


</body>


<!-- Mirrored from ashik.templatepath.net/inovex-html-files/blog-details.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 15 Jun 2020 09:17:20 GMT -->
</html>