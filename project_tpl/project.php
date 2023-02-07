<?php require_once('project_c.php');?>

<?php
    $atz = new project_controller();
    
    $project = $atz->get_project($atz->id);
    
    if (!empty($project)) {
        $cat = $atz->get_cat($project['Project_Cat']);

        // Update View
        $atz->update_view($project['Project_ID'],$project['Project_View_vi']);
    }

    $relative_projects = $atz->get_relative_projects($atz->id);    
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <!-- HEAD -->
    <?php include('module/head.php')?>
    <!-- END HEAD -->

    <meta name="description" content="<?=$cat['Cat_Description_vi']?>">
    <meta name="keywords" content="<?=$cat['Cat_Keywords_vi']?>">

    <title><?=$cat['Cat_Name_vi']?> | <?=SETTING['Setting_Title']?></title>

</head>

<body>

    <!-- PRELOAD -->
    <?php include('module/preload.php')?>
    <!-- END PRELOAD -->

    <div class="page-wrapper">

        <!-- MENU -->
        <?php include('module/menu.php')?>
        <!-- END MENU -->

        <!-- PAGE HEADER -->
        <section class="page-header">
            <div class="particles-snow" id="header-snow"></div><!-- /#header-snow.particles-snow -->

            <?php include('module/page_header_bg.php')?>

            <div class="container text-center">
                <h2><?=$cat['Cat_Name_vi']?></h2>
                <ul class="list-unstyled thm-breadcrumb">
                    <li><a href="<?=$atz->site_url['main']?>">Trang chủ</a></li>
                    <li><span><?=$cat['Cat_Name_vi']?></span></li>
                </ul><!-- /.thm-breadcrumb -->
            </div><!-- /.container text-center -->
        </section><!-- /.page-header -->
        <!-- END PAGE HEADER -->

        <section class="portfolio-details">
            <div class="portfolio-details__image">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-9">
                            <img src="<?=$project['Project_Thumbnail']?>" alt="<?=$project['Project_Name_vi']?>">
                        </div><!-- /.col-lg-9 -->
                    </div><!-- /.row -->
                </div><!-- /.container -->
            </div><!-- /.portfolio-details__image -->
            <div class="portfolio-details__main">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="portfolio-details__content">
                                <?=$project['Project_Content_vi']?>
                            </div><!-- /.portfolio-details__content -->
                            <div class="blog-post__navigations">
                                <a class="blog-post__navigations-left" href="#">Previous Post <i
                                        class="far fa-angle-left"></i></a>
                                <a class="blog-post__navigations-right" href="#">Next Post <i
                                        class="far fa-angle-right"></i></a>
                            </div><!-- /.blog-post__navigations -->
                        </div><!-- /.col-lg-8 -->

                        <div class="col-lg-4 wow fadeInRight" data-wow-duration="1500ms">
                            <div class="portfolio-details__info">
                                <h3>Thông tin dự án</h3>
                                <div class="portfolio-details__info-single">
                                    <div class="portfolio-details__info-title">
                                        <i class="far fa-calendar-alt"></i>
                                        <span>Hoạt động :</span>
                                    </div><!-- /.portfolio-details__info-title -->
                                    <div class="portfolio-details__info-text">
                                        <p><?=date('d-m-Y', $project['Project_Start'])?></p>
                                        <!-- <p>29 January 2020</p> -->
                                    </div><!-- /.portfolio-details__info-text -->
                                </div><!-- /.portfolio-details__info-single -->
                                
                                <!-- <div class="portfolio-details__info-single">
                                    <div class="portfolio-details__info-title">
                                        <i class="far fa-map-marker-alt"></i>
                                        <span>Link :</span>
                                    </div>
                                    <div class="portfolio-details__info-text">
                                        <p>Royal Orville Road Apt. <br> 728 California, USA</p>
                                    </div>
                                </div> -->
                                
                                <div class="portfolio-details__info-single">
                                    <div class="portfolio-details__info-title">
                                        <i class="far fa-tag"></i>
                                        <span>Từ khóa :</span>
                                    </div><!-- /.portfolio-details__info-title -->
                                    <div class="portfolio-details__info-text">
                                        <!-- <p><?=str_replace(',','<br>',$project['Project_Keywords_vi'])?></p> -->
                                        <p><?=$project['Project_Keywords_vi']?></p>
                                    </div><!-- /.portfolio-details__info-text -->
                                </div><!-- /.portfolio-details__info-single -->
                                <div class="portfolio-details__info-single">
                                    <div class="portfolio-details__info-title">
                                        <i class="far fa-bookmark"></i>
                                        <span>Loại hình:</span>
                                    </div><!-- /.portfolio-details__info-title -->
                                    <div class="portfolio-details__info-text">
                                        <p><a href="#">Bán Hàng</a></p>
                                    </div><!-- /.portfolio-details__info-text -->
                                </div><!-- /.portfolio-details__info-single -->
                            </div><!-- /.portfolio-details__info -->
                        </div><!-- /.col-lg-4 -->
                    </div><!-- /.row -->
                </div><!-- /.container -->
            </div><!-- /.portfolio-details__main -->
        </section><!-- /.portfolio-details -->

        <section class="portfolio-grid portfolio-related">
            <div class="container">
                <div class="portfolio-grid__title">Releted Portfolio</div><!-- /.portfolio-grid__title -->
                <div class="portfolio-grid__carousel owl-carousel owl-theme thm__owl-carousel" data-options='{
                    "items": 3, "margin": 40, "loop": true, "autoplay": true, "autoplayTimeout": 5000, "autoplayHoverPause": true, "dots": false, "nav": false, "smartSpeed": 700,
                    "responsive": {
                        "0": { "margin": 0, "items": 1},
                        "575": { "margin": 0, "items": 1},
                        "991": { "margin": 40, "items": 2},
                        "1199": { "margin": 40, "items": 3}
                    }
                }'>
                    <div class="item">
                        <div class="portfolio-one__single">
                            <div class="portfolio-one__image">
                                <img src="assets/images/portfolio/portfolio-1-1.jpg" alt="">
                                <a class="img-popup" href="assets/images/portfolio/portfolio-1-1.jpg"><i
                                        class="fal fa-plus"></i></a>
                            </div><!-- /.portfolio-one__image -->
                            <div class="portfolio-one__content">
                                <h3><a href="portfolio-details.html">Content Strategy</a></h3>
                                <p>Customized SEO services</p>
                            </div><!-- /.portfolio-one__content -->
                        </div><!-- /.portfolio-one__single -->
                    </div><!-- /.item -->
                    <div class="item">
                        <div class="portfolio-one__single">
                            <div class="portfolio-one__image">
                                <img src="assets/images/portfolio/portfolio-1-2.jpg" alt="">
                                <a class="img-popup" href="assets/images/portfolio/portfolio-1-2.jpg"><i
                                        class="fal fa-plus"></i></a>
                            </div><!-- /.portfolio-one__image -->
                            <div class="portfolio-one__content">
                                <h3><a href="portfolio-details.html">SEO Optimization</a></h3>
                                <p>Customized SEO services</p>
                            </div><!-- /.portfolio-one__content -->
                        </div><!-- /.portfolio-one__single -->
                    </div><!-- /.item -->
                    <div class="item">
                        <div class="portfolio-one__single">
                            <div class="portfolio-one__image">
                                <img src="assets/images/portfolio/portfolio-1-3.jpg" alt="">
                                <a class="img-popup" href="assets/images/portfolio/portfolio-1-3.jpg"><i
                                        class="fal fa-plus"></i></a>
                            </div><!-- /.portfolio-one__image -->
                            <div class="portfolio-one__content">
                                <h3><a href="portfolio-details.html">Content Marketing</a></h3>
                                <p>Customized SEO services</p>
                            </div><!-- /.portfolio-one__content -->
                        </div><!-- /.portfolio-one__single -->
                    </div><!-- /.item -->
                    <div class="item">
                        <div class="portfolio-one__single">
                            <div class="portfolio-one__image">
                                <img src="assets/images/portfolio/portfolio-1-1.jpg" alt="">
                                <a class="img-popup" href="assets/images/portfolio/portfolio-1-1.jpg"><i
                                        class="fal fa-plus"></i></a>
                            </div><!-- /.portfolio-one__image -->
                            <div class="portfolio-one__content">
                                <h3><a href="portfolio-details.html">Content Strategy</a></h3>
                                <p>Customized SEO services</p>
                            </div><!-- /.portfolio-one__content -->
                        </div><!-- /.portfolio-one__single -->
                    </div><!-- /.item -->
                    <div class="item">
                        <div class="portfolio-one__single">
                            <div class="portfolio-one__image">
                                <img src="assets/images/portfolio/portfolio-1-2.jpg" alt="">
                                <a class="img-popup" href="assets/images/portfolio/portfolio-1-2.jpg"><i
                                        class="fal fa-plus"></i></a>
                            </div><!-- /.portfolio-one__image -->
                            <div class="portfolio-one__content">
                                <h3><a href="portfolio-details.html">SEO Optimization</a></h3>
                                <p>Customized SEO services</p>
                            </div><!-- /.portfolio-one__content -->
                        </div><!-- /.portfolio-one__single -->
                    </div><!-- /.item -->
                    <div class="item">
                        <div class="portfolio-one__single">
                            <div class="portfolio-one__image">
                                <img src="assets/images/portfolio/portfolio-1-3.jpg" alt="">
                                <a class="img-popup" href="assets/images/portfolio/portfolio-1-3.jpg"><i
                                        class="fal fa-plus"></i></a>
                            </div><!-- /.portfolio-one__image -->
                            <div class="portfolio-one__content">
                                <h3><a href="portfolio-details.html">Content Marketing</a></h3>
                                <p>Customized SEO services</p>
                            </div><!-- /.portfolio-one__content -->
                        </div><!-- /.portfolio-one__single -->
                    </div><!-- /.item -->
                </div><!-- /.thm__owl-carousel -->
            </div><!-- /.container -->
        </section><!-- /.portfolio-grid -->


        <section class="contact-one">
            <div class="container wow fadeInUp" data-wow-duration="1500ms">
                <div class="inner-container">
                    <img src="assets/images/shapes/contact-form-shape-1-1.png" class="contact-one__shape-1" alt="">
                    <img src="assets/images/mocups/contact-1-moc-1.png" class="contact-one__shape-2" alt="">
                    <img src="assets/images/mocups/contact-1-moc-2.png" class="contact-one__shape-3" alt="">
                    <div class="block-title text-center">
                        <p><span>Analysis</span></p>
                        <h3>Get Free SEO Analysis?</h3>
                    </div><!-- /.block-title text-center -->
                    <form action="" class="contact-one__form">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" placeholder="Your Name*">
                            </div><!-- /.col-md-6 -->
                            <div class="col-md-6">
                                <input type="text" placeholder="Email*">
                            </div><!-- /.col-md-6 -->
                            <div class="col-md-6">
                                <input type="text" placeholder="Website*">
                            </div><!-- /.col-md-6 -->
                            <div class="col-md-6">
                                <input type="text" placeholder="Subject">
                            </div><!-- /.col-md-6 -->
                            <div class="col-md-12 text-center">
                                <button type="submit" class="thm-btn contact-one__btn">Send Now</button>
                            </div><!-- /.col-md-12 -->
                        </div><!-- /.row -->
                    </form><!-- /.contact-one__form -->

                </div><!-- /.inner-container -->
            </div><!-- /.container -->
        </section><!-- /.contact-one -->

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