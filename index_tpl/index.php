<!-- LIB -->
<?php require_once('index_c.php');?>
<?php 
    $atz = new index_controller();

    $cat_list = array();
    $post_list = array();

    $cat_list = $atz->get_cats();

    // Lấy chuyên mục và sản phẩm
    $cat_product = array();
    if(!empty($cat_list)){
        foreach ($cat_list as $k=>$v) {
            $cat_product[$k] = $v;

            $products_list = $atz->get_products($v['Cat_ID']);
            if (!empty($products_list)) {
                $cat_product[$k]['products'] = $atz->get_products($v['Cat_ID']);
            }
        }
    }


    // Lấy chuyên mục và bài viết được chỉ định cụ thể
    $cat_post_1 = $atz->get_cat_post(5);
        
    $cat_post_2 = $atz->get_cat_post(6);


    $products_hot = $atz->get_product_hot();

        
?>

<!-- END LIB -->

<!doctype html>
<html lang="vi">

    <head>

        <!-- HEAD -->
        <?php include('modules/head.php') ?>
        <!-- END HEAD -->

        <!-- SEO -->
        <meta name="description" content="<?=SETTING['Setting_Description']?>">
        <meta name="keywords" content="<?=SETTING['Setting_Keywords']?>">

        <title><?=SETTING['Setting_Title']?></title>
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

        <!-- SLIDER -->
        <?php include('modules/slider.php')?>
        <!-- END SLIDER -->

        <div class="container-fluid">
            <div class="row hot-slider justify-content-center">    
                <div class="col-md-10">
                    <?php if (!empty($products_hot)): ?>
                        <div class="owl-one owl-carousel owl-theme">
                            <?php foreach ($products_hot as $v): ?>
                            <div class="item product-item">
                                <figure class="text-center">
                                    <a href="product.php?id=<?=$v['Product_ID']?>" title="<?=$v['Product_Name_vi']?>"><img class="w-100" src="<?=str_replace('../', '', $v['Product_Thumbnail'])?>" alt="<?=$v['Product_Name_vi']?>"></a>
                                    <figcaption><a href="product.php?id=<?=$v['Product_ID']?>"><?=$v['Product_Name_vi']?></a></figcaption>
                                    Giá:
                                    <?php if ($v['Product_Price']): ?>
                                        <span class="price">
                                            <?=number_format($v['Product_Price'],0,',','.').'đ'?>        
                                        </span>
                                    <?php else: ?>
                                        <span class="price">
                                            <a href="tel:<?=str_replace(' ', '', SETTING['Setting_Phone'])?>">Liên hệ</a>
                                        </span>
                                    <?php endif ?>
                                </figure>
                            </div>
                            <?php endforeach ?>
                            
                        </div>
                    <?php endif ?>
                </div>            
            </div>  
            
              
        </div>

        <!-- PRODUCT -->
        <div class="container mt-4">

            <div class="row">
                <div class="col-lg-3 order-2 order-lg-1">

                    <div class="row main-cat mb-3">
                        <?php include('modules/left_sidebar.php') ?>
                    </div>
                    
                </div>

                <div class="col-lg-9 order-1 order-lg-2 mb-5 main-product">


                    <?php if (!empty($cat_product)): ?>
                        <?php foreach ($cat_product as $v): ?>
                            
                            <div class="row mb-3">
                                <div class="col-12">
                                    <h3><?=$v['Cat_Name_vi']?></h3>
                                    <div class="row mt-4">

                                        <?php if (!empty($v['products'])): ?>
                                            <?php foreach ($v['products'] as $v1): ?>
                                                
                                                <div class="col-6 col-md-3">
                                                    <div class="product-item">
                                                        <figure class="text-center">
                                                            <a href="product.php?id=<?=$v1['Product_ID']?>" title="<?=$v1['Product_Name_vi']?>">
                                                                <img class="w-100" src="<?=str_replace('../', '', $v1['Product_Thumbnail'])?>" alt="<?=$v1['Product_Name_vi']?>">
                                                            </a>
                                                            <figcaption><a href="#"><?=$v1['Product_Name_vi']?></a></figcaption>
                                                            Giá: 
                                                            <?php if ($v1['Product_Price']): ?>
                                                                <span class="price">
                                                                    <?=number_format($v1['Product_Price'],0,',','.').'đ'?>        
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="price">
                                                                    <a href="tel:<?=str_replace(' ', '', SETTING['Setting_Phone'])?>">Liên hệ</a>
                                                                </span>
                                                            <?php endif ?>
                                                            
                                                        </figure>
                                                    </div>
                                                </div>

                                            <?php endforeach ?>
                                        <?php endif ?>
                                        
                                    </div>

                                </div>

                            </div>



                        <?php endforeach ?>    
                    <?php endif ?>
                    

                </div>

            </div>
        </div>
        <!-- END PRODUCT -->



        <div class="container-fluid">

            <div class="row justify-content-center align-items-center bg-contact py-3">

                <div class="col-lg-5 mb-xl-0 mb-3">

                    <div class="row justify-content-center">
                        
                        <div class="col-9 text-center">
                            
                            <div id="carouselExampleControls" class="carousel slide text-center" data-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img class="d-block w-100" src="uploads/thiet_ke_tieu_canh.jpg" alt="First slide">
                                    </div>
                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>

                        </div>

                    </div>

                </div>


                <!-- FORM -->
                <div class="col-lg-5">

                    <fieldset class="reg-form">
                        <legend class="text-center"><strong>ĐĂNG KÝ NHẬN TIN</strong></legend>
                    
                        <form method="post" class="text-center">
                            <p class="text-white">Nhập thông tin của bạn vào Form bên dưới để nhận tin từ chúng tôi!</p>
                            <div class="form-row">

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <input type="text" class="form-control" placeholder="Họ và tên*">        
                                        </div>

                                        <div class="col-12 mb-3">
                                            <input type="text" class="form-control" placeholder="Điện thoại*">        
                                        </div>

                                        <div class="col-12 mb-3">
                                            <input type="text" class="form-control" placeholder="Email*">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <textarea class="form-control" rows="6">Nội dung*</textarea>
                                </div>
                            </div>
                            <input type="submit" class="btn btn-warning btn-contact" value="Liên hệ ngay">    
                            
                        </form>

                    </fieldset>
                    
                </div>
                <!-- END FORM -->
            </div>
        </div>
        


        <div class="container my-5">
            <div class="row main_news justify-content-center">

                <?php if (!empty($cat_post_1)): ?>
                    <div class="col-lg-4 blog">
                        <h4><?=$cat_post_1['Cat_Name_vi']?></h4>
                        <?php if (!empty($cat_post_1['post'])): ?>
                            <ul id="scroller">
                                <?php foreach ($cat_post_1['post'] as $v): ?>
                                    <li class="row">
                                        <div class="col-md-12">
                                            <img class="img-fluid" src="<?=str_replace('../', '', $v['Post_Thumbnail'])?>" alt="<?=$v['Post_Title_vi']?>" align="left">
                                            <h5><a href="post.php?id=<?=$v['Post_ID']?>" title="<?=$v['Post_Title_vi']?>"><?=$v['Post_Title_vi']?></a></h5>
                                            <p><?=$v['Post_Description_vi']?></p>
                                        </div>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        <?php endif ?>
                    </div>
                <?php endif ?>
                 
                <div class="col-lg-4">
                    <h4>VIDEO CLIP</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="ajax_video" class="ajax_video p-2">
                                <iframe width="100%" height="290" src="https://www.youtube.com/embed/blgRF0H8gVk" frameborder="0" allowfullscreen=""></iframe>
                                <select name="list-video" class="form-control list-video">
                                    <option value="blgRF0H8gVk">Cách trồng rau cải hữu cơ: xà lách, bẹ xanh và bẹ ngọt tại nhà.</option>
                                    <option value="qr6bTDJefMs">Cách trồng và chăm sóc hoa đồng tiền cho hoa đẹp 4 mùa</option>
                                    <option value="eBDh72nyDvs">Trồng và chăm sóc cúc đồng tiền sau khi chơi tết</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($cat_post_2)): ?>
                <div class="col-lg-4">
                    <h4><?=$cat_post_2['Cat_Name_vi']?></h4>

                    <?php if (!empty($cat_post_2['post'])): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="post-slider p-2">
                                    
                                    <div class="owl-two owl-carousel owl-theme">

                                        <?php foreach ($cat_post_2['post'] as $v): ?>
                                            <div class="item product-item">
                                                <figure>
                                                    <a href="post.php?id=<?=$v['Post_ID']?>" title="<?=$v['Post_Title_vi']?>">
                                                        <img class="w-100" src="<?=str_replace('../', '', $v['Post_Thumbnail'])?>" alt="<?=$v['Post_Title_vi']?>">
                                                    </a>
                                                </figure>
                                                <div class="bgdate float-left text-center">
                                                    <strong><?=date('d',$v['Post_Created'])?></strong>
                                                    <br>
                                                    THÁNG <?=date('n',$v['Post_Created'])?>
                                                </div>
                                                <!-- <h5><a href="gia-gao-viet-nam-cao-nhat-trong-vong-1-nam"><?=$v['Post_Title_vi']?></a></h5> -->
                                                <h5><a href="post.php?id=<?=$v['Post_ID']?>"><?=$v['Post_Title_vi']?></a></h5>
                                                <p><?=$v['Post_Description_vi']?></p>
                                            </div>
                                        <?php endforeach ?>
                                        
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    <?php endif ?>

                </div>
                <?php endif ?>

            </div>
        </div>

        <?php include('modules/footer.php') ?>
        
        <!-- JS LIB-->
        <?php include('modules/js.php') ?>
        <!-- END JS LIB -->

        <script>
            
            var owl = $('.owl-one');
            owl.owlCarousel({
                loop:true,
                margin: 20,
                responsiveClass:true,
                responsive:{
                    0:{
                        items:2,
                        nav:false
                    },
                    600:{
                        items:3,
                        nav:false
                    },
                    1000:{
                        items:5,
                        nav:false,
                    }
                }
            });

            var owl2 = $('.owl-two');
            owl2.owlCarousel({
                loop:true,
                margin: 20,
                autoplay:true,
                dots: false,
                
                responsiveClass:true,
                responsive:{
                    0:{
                        items:1,
                        nav:false
                    },
                    600:{
                        items:2,
                        nav:false
                    },
                    1000:{
                        items:1,
                        nav:false,
                        // loop:false
                    }
                }
            });

            (function($) {
                $(function() {
                    $("#scroller").simplyScroll({orientation:'vertical',customClass:'vert'});
                });
            })(jQuery);


            // marqueeInit({
            //     uniqueid: 'mycrawler2',
            //     style: {
            //         'padding-left': '0px', 
            //     },
            //     inc: 5, //speed - pixel increment for each iteration of this marquee's movement
            //     mouse: 'cursor driven', //mouseover behavior ('pause' 'cursor driven' or false)
            //     moveatleast: 1,
            //     neutral: 150,
            //     savedirection: true,
            //     random: true
            // });

            $().ready(function(e) {
                $('.list-video').change(function(){
                  var url='https://www.youtube.com/embed/'+$(this).val();
                  $('#ajax_video iframe').attr('src',url);
                })
            });
            
        </script>
    
    </body>
</html>