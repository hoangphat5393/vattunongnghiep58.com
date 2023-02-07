<!-- LIB -->
<?php require_once('index_c.php');?>
<?php 
    $atz = new index_controller();

    $cat_list = array();
    $post_list = array();

    $cat_list = $atz->get_cats();
    
    // Lấy chuyên mục và sản phẩm
    // $cat_product = array();
    // if(!empty($cat_list)){
    //     foreach ($cat_list as $k=>$v) {
    //         $cat_product[$k] = $v;

    //         $products_list = $atz->get_products($v['Cat_ID']);
    //         if (!empty($products_list)) {
    //             $cat_product[$k]['products'] = $atz->get_products($v['Cat_ID']);
    //         }
    //     }
    // }

    $post_list = $atz->get_posts();

    // Lấy chuyên mục và bài viết được chỉ định cụ thể
    $hostings_hot = $atz->get_hostings_hot();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- HEAD -->
    <?php include('module/head.php')?>
    <!-- END HEAD -->

    <meta name="description" content="<?=SETTING['Setting_Description']?>">
    <meta name="keywords" content="<?=SETTING['Setting_Keywords']?>">

    <title><?=SETTING['Setting_Title']?> | <?=SETTING['Setting_Slogan']?></title>
    
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
        
        <!-- SLIDER -->
        <?php include('module/slider.php')?>
        <!-- END SLIDER -->

        <section class="service-three">

            <div class="section__bubble-1"></div><!-- /.section__bubble-1 -->
            <div class="section__bubble-2"></div><!-- /.section__bubble-2 -->
            <div class="section__bubble-3"></div><!-- /.section__bubble-3 -->
            <div class="section__bubble-4"></div><!-- /.section__bubble-4 -->
            <div class="section__bubble-5"></div><!-- /.section__bubble-5 -->
            <div class="section__bubble-6"></div><!-- /.section__bubble-6 -->
            <div class="section__bubble-7"></div><!-- /.section__bubble-7 -->
            <div class="section__bubble-8"></div><!-- /.section__bubble-8 -->

        </section><!-- /.service-three -->

        <section class="about-two">
            <img src="assets/images/shapes/about-2-bg-1-1.png" class="about-two__bg-shape-1" alt="">
            <div class="container">
                <img src="assets/images/shapes/about-2-bg-1-2.png" class="about-two__bg-shape-2" alt="">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="about-two__images wow fadeInLeft" data-wow-duration="1500ms">
                            <img src="assets/images/mocups/about-2-moc-1.png" class="about-two__image-1 float-bob-y" alt="">
                            <img src="assets/images/mocups/about-2-moc-2.png" class="about-two__image-2" alt="">
                            <img src="assets/images/mocups/about-2-moc-3.png" class="about-two__image-3 float-bob-y" alt="">
                        </div><!-- /.about-two__images -->
                    </div><!-- /.col-lg-6 -->
                    <div class="col-lg-6">
                        <div class="about-two__content">
                            <div class="block-title text-left">
                                <p class="color-2"><span>Giới thiệu</span></p>
                                <h3>Thu hút khách hàng <br> <span>Bằng giá trị thực</span></h3>
                            </div><!-- /.block-title text-left -->
                            <p>Không chỉ hướng dẫn bạn cách đẩy từ khóa lên top Google, chúng tôi còn sát cánh cùng bạn từng bước làm cho website ngày càng trở nên hữu ích.</p>

                            <div class="about-two__counter-wrap">
                                <div class="about-two__counter">
                                    <div class="about-two__count"><span class="counter">200</span><!-- /.counter --> <b>+</b></div><!-- /.about-two__count -->
                                    <h3>Dự án</h3>
                                </div><!-- /.about-two__counter -->
                                <div class="about-two__counter">
                                    <div class="about-two__count"><span class="counter">100</span><!-- /.counter --> <b>+</b></div><!-- /.about-two__count -->
                                    <h3>Khách hàng</h3>
                                </div><!-- /.about-two__counter -->
                                <div class="about-two__counter">
                                    <div class="about-two__count"><span class="counter">5</span><!-- /.counter --> <b>+</b></div><!-- /.about-two__count -->
                                    <h3>Kinh nghiệm</h3>
                                </div><!-- /.about-two__counter -->
                            </div><!-- /.about-two__counter-wrap -->
                        </div><!-- /.about-two__content -->
                    </div><!-- /.col-lg-6 -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </section><!-- /.about-two -->



        <section class="step-one">
            <img src="assets/images/shapes/steps-bg-1-1.png" class="step-one__bg-image-1" alt="">
            <div class="container">
                <div class="block-title text-center">
                    <p><span>Quy trình làm việc</span></p>
                    <h3>Steps to Build a Successful <br> <span>Digital Product</span></h3>
                </div><!-- /.block-title text-center -->
                <div class="row">
                    <img src="assets/images/shapes/steps-line-1-1.png" class="step-one__line" alt="">
                    <div class="step-one__single">
                        <div class="step-one__arrow far fa-angle-right"></div><!-- /.step-one__arrow -->
                        <div class="step-one__count wow fadeInUp" data-wow-duration="1500ms">
                            <span>01</span>
                        </div><!-- /.step-one__count -->
                        <h3><a href="#">Quảng cáo & <br> tiếp thị</a></h3>
                    </div><!-- /.step-one__single -->
                    <div class="step-one__single">
                        <div class="step-one__arrow far fa-angle-right"></div><!-- /.step-one__arrow -->
                        <div class="step-one__count wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="100ms">
                            <span>02</span>
                        </div><!-- /.step-one__count -->
                        <h3><a href="#">Phát triển<br> Website</a></h3>
                    </div><!-- /.step-one__single -->
                    <div class="step-one__single">
                        <div class="step-one__arrow far fa-angle-right"></div><!-- /.step-one__arrow -->
                        <div class="step-one__count wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="200ms">
                            <span>03</span>
                        </div><!-- /.step-one__count -->
                        <h3><a href="#">Giao diện tương thích <br> mọi thiết bị</a></h3>
                    </div><!-- /.step-one__single -->
                    <div class="step-one__single">
                        <div class="step-one__arrow far fa-angle-right"></div><!-- /.step-one__arrow -->
                        <div class="step-one__count wow fadeInUp" data-wow-delay="300ms" data-wow-duration="1500ms">
                            <span>04</span>
                        </div><!-- /.step-one__count -->
                        <h3><a href="#">Tối ưu hóa <br>công cụ tìm kiếm</a></h3>
                    </div><!-- /.step-one__single -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </section><!-- /.step-one -->

        <section class="pricing-one">
            <div class="container">
                <div class="block-title text-center">
                    <p class="color-2"><span>Hosting</span></p>
                    <h3>Cung cấp dịch vụ hosting <br> <span>Gói dịch vụ được đăng ký nhiều nhất</span></h3>
                </div><!-- /.block-title text-center -->

                <div class="row high-gutters">

                    <?php if (!empty($hostings_hot)): ?>
                        <?php $i=0; ?>
                        <?php foreach ($hostings_hot as $v): ?>
                            
                            <?php if ($i==0): ?>
                                <?php $animation_class = 'fadeInLeft';?>
                            <?php elseif($i==1): ?>
                                <?php $animation_class = 'fadeInUp';?>
                            <?php else: ?>
                                <?php $animation_class = 'fadeInRight';?>
                            <?php endif ?>

                            <div class="col-lg-4 wow <?=$animation_class?>" data-wow-duration="1500ms">
                                <div class="pricing-one__single">
                                    <div class="pricing-one__icon">
                                        <img src="<?=str_replace('../','',$v['Hosting_Thumbnail'])?>" alt="<?=$v['Hosting_Name']?>">
                                    </div><!-- /.pricing-one__icon -->
                                    <h3><?=$v['Hosting_Name']?></h3>
                                    <ul class="pricing-one__list list-unstyled">
                                        <li><?=$v['Hosting_Capable']?> dung lượng</li>
                                        <li><?=$v['Hosting_MySql']?> MySql</li>
                                        <li><?=$v['Hosting_Email']?> địa chỉ Email</li>
                                        <li><?=$v['Hosting_Ftp']?> tài khoản FTP</li>
                                        <li><?=$v['Hosting_SubDomain']?> Sub Domain</li>
                                        <li><?=$v['Hosting_SubDomain']?> Park Domain</li>
                                        <li><?=$v['Hosting_AddonDomain']?> Addon Domain</li>
                                        <li class="disabled"><?=$v['Hosting_Bandwidth']?> băng thông</li>
                                        <!-- <li class="disabled">SSL miễn phí</li> -->
                                        <!-- <li class="disabled">Hỗ trợ miễn phí</li> -->
                                    </ul><!-- /.pricing-one__list list-unstyled -->
                                    <p><?=number_format($v['Hosting_Price'],0,',','.')?><sup>đ</sup><sub>/tháng</sub></p>
                                    <a href="#" class="thm-btn pricing-one__btn">Đăng ký</a><!-- /.thm-btn pricing-one__btn -->
                                </div><!-- /.pricing-one__single -->
                            </div><!-- /.col-lg-4 -->
                            <?php $i++; ?>
                        <?php endforeach ?>
                    <?php endif ?>

                </div><!-- /.row -->
            </div><!-- /.container -->
        </section><!-- /.pricing-one -->

        <section class="blog-one blog-one__home-one">
            <div class="container">
                <div class="blog-one__top">
                    <div class="block-title text-left">
                        <p><span>Bài viết</span></p>
                        <h3>Cập nhật thông tin mới nhất từ<br> <span>Cộng đồng của chúng tôi.</span></h3>
                    </div><!-- /.block-title text-center -->

                    <div class="blog-one__carousel-btn">
                        <a href="#" class="blog-one__carousel-btn-left"><i class="far fa-angle-left"></i></a>
                        <a href="#" class="blog-one__carousel-btn-right"><i class="far fa-angle-right"></i></a>
                    </div><!-- /.blog-one__carousel-btn -->
                </div><!-- /.blog-one__top -->


                <div class="thm__owl-carousel blog-one__carousel owl-carousel owl-theme"
                    data-carousel-prev-btn=".blog-one__carousel-btn-left"
                    data-carousel-next-btn=".blog-one__carousel-btn-right" data-options='{
                    "items": 3, "margin": 40, "smartSpeed": 700, "autoplay": true, "autoplayTimeout": 5000,
                    "autoplayHoverPause": true, "nav": false, "dots": false, "loop": true, "responsive": {
                        "0": { "items": 1, "margin": 0},
                        "575": { "items": 1, "margin": 0},
                        "767": { "items": 1, "margin": 0},
                        "991": { "items": 2, "margin": 40},
                        "1199": { "items": 3, "margin": 40}
                    }
                }'>
                    <?php if(!empty($post_list)):?>

                        <?php foreach($post_list as $v):?>
                            <?php 
                                $permalink = $atz->site_url['main'].'tin-tuc/'.$atz->slug($v['Post_Title_vi']).'-'.$v['Post_ID'].'.html';
                                $img_link = str_replace('../','',$v['Post_Thumbnail']);
                            ?>
                            <div class="item">
                                <div class="blog-one__single">
                                    <div class="blog-one__image">
                                        <img src="<?=$img_link?>" alt="<?=$v['Post_Title_vi']?>">
                                        <a href="<?=$permalink?>" title="<?=$v['Post_Title_vi']?>"><i class="fal fa-plus"></i></a>
                                    </div>
                                    <div class="blog-one__content">
                                        <div class="blog-one__meta">
                                            <!-- <a href="blog-details.html">Sara dodly</a>
                                            <span>-</span> -->
                                            <a href="<?=$atz->site_url['main'].'tin-tuc/'.$atz->slug($v['Post_Title_vi']).'-'.$v['Post_ID'].'.html'?>"><?=date('d-m-Y',$v['Post_Created'])?></a>
                                        </div>
                                        <h3><a href="<?=$atz->site_url['main'].'tin-tuc/'.$atz->slug($v['Post_Title_vi']).'-'.$v['Post_ID'].'.html'?>" title="<?=$v['Post_Title_vi']?>"><?=$v['Post_Title_vi']?></a></h3>
                                        <a href="<?=$permalink?>" class="thm-btn blog-one__btn" title="<?=$v['Post_Title_vi']?>"><span>Chi tiết</span></a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach?>
                    <?php endif?>

                </div><!-- /.row -->

            </div><!-- /.container -->
        </section><!-- /.blog-grid -->

        <section class="contact-one">
            <div class="container wow fadeInUp" data-wow-duration="1500ms">
                <div class="inner-container">
                    <img src="assets/images/shapes/contact-form-shape-1-1.png" class="contact-one__shape-1" alt="">
                    <img src="assets/images/mocups/contact-1-moc-1.png" class="contact-one__shape-2" alt="">
                    <img src="assets/images/mocups/contact-1-moc-2.png" class="contact-one__shape-3" alt="">
                    <div class="block-title text-center">
                        <p><span>Liên hệ</span></p>
                        <h3>Tư vấn miễn phí</h3>
                    </div><!-- /.block-title text-center -->
                    <form action="http://ashik.templatepath.net/inovex-html-files/assets/inc/sendemail.php" class="contact-one__form">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" placeholder="Tên*">
                            </div>
                            <div class="col-md-6">
                                <input type="text" placeholder="Email*">
                            </div>
                            <div class="col-md-6">
                                <input type="text" placeholder="Điện thoại">
                            </div>
                            <div class="col-md-6">
                                <input type="text" placeholder="Dịch vụ*">
                            </div>
                            <div class="col-md-12 text-center">
                                <button type="submit" class="thm-btn contact-one__btn">Gửi</button>
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