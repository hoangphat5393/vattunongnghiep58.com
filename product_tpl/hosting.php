<?php require_once('hosting_c.php');?>

<?php 
    $hosting_class = new hosting_controller();

    $hostings = $hosting_class->get_hosting();
?>

<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from ashik.templatepath.net/inovex-html-files/pricing.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 15 Jun 2020 09:16:24 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->
<head>
    

    <!-- HEAD -->
    <?php include('module/head.php')?>
    <!-- END HEAD -->

    <meta name="description" content="<?=SETTING['Setting_Description']?>">
    <meta name="keywords" content="<?=SETTING['Setting_Keywords']?>">

    <title>Hosting | <?=SETTING['Setting_Title']?></title>

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

        <!-- PAGE HEADER -->
        <section class="page-header">
            <div class="particles-snow" id="header-snow"></div><!-- /#header-snow.particles-snow -->

            <?php include('module/page_header_bg.php')?>
            
            <div class="container text-center">
                <h2>Hosting</h2>
                <ul class="list-unstyled thm-breadcrumb">
                    <li><a href="<?=$atz->site_url['main']?>">Trang chủ</a></li>
                    <li><span>Hosting</span></li>
                </ul><!-- /.thm-breadcrumb -->
            </div><!-- /.container text-center -->
        </section><!-- /.page-header -->
        <!-- END PAGE HEADER -->

        <section class="pricing-one">
            <div class="container">
                <div class="block-title text-center">
                    <p class="color-2"><span>Pricing Plan 1</span></p>
                    <h3>Bảng giá hosting <br> <span>Hosting Linux</span></h3>
                </div><!-- /.block-title text-center -->

                <div class="row high-gutters">
                    <?php if (!empty($hostings)): ?>
                        <?php foreach ($hostings as $k => $v): ?>
                            <div class="col-lg-4 wow fadeInLeft" data-wow-duration="1500ms">
                                <form action="cart.php" method="post">
                                    <div class="pricing-one__single">
                                        <div class="pricing-one__icon">
                                            <img src="assets/images/shapes/pricing-icon-1-1.png" alt="">
                                        </div><!-- /.pricing-one__icon -->
                                        <h3><?=$v['Hosting_Name']?></h3>
                                        <ul class="pricing-one__list list-unstyled">
                                            <li><?=$v['Hosting_Capable']?> dung lượng</li>
                                            <li><?=$v['Hosting_Email']?> Email</li>
                                            <li><?=$v['Hosting_Ftp']?> FTP</li>
                                            <li><?=$v['Hosting_MySql']?> MySQL</li>
                                            <li><?=$v['Hosting_SubDomain']?> Sub Domain</li>
                                            <li class="disabled"><?=$v['Hosting_Bandwidth']?> Bandwidth</li>
                                        </ul><!-- /.pricing-one__list list-unstyled -->
                                        <p><?=number_format($v['Hosting_Price'],0,',','.')?><sup>đ</sup><sub>/tháng</sub></p>
                                        <input type="hidden" name="id" class="form-control" value="<?=$v['Hosting_ID']?>">
                                        <button class="thm-btn pricing-one__btn">Đăng ký</button>
                                        <!-- <a href="cart.php" class="thm-btn pricing-one__btn">Đăng ký</a>-->
                                    </div><!-- /.pricing-one__single -->
                                </form>
                            </div><!-- /.col-lg-4 -->
                        <?php endforeach ?>
                    <?php endif ?>


                </div><!-- /.row -->
            </div><!-- /.container -->
        </section><!-- /.pricing-one -->

        <!-- SUBSCRIBE -->
        <?php include('module/subscribe.php') ?>
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

</html>