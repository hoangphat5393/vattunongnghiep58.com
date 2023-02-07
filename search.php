<!-- LIB -->
<?php require_once('product_c/product_c.php');?>

<?php 
    $atz = new product_controller();

    
    $product_list = array();


    if(isset($_GET['q']) && !empty($_GET['q'])){
        $product_list = $atz->get_search_product();
    }   
?>

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

        <!-- PRODUCT -->
        <div class="container mt-4">

            <div class="row mt-3">
                <div class="col-lg-3 order-2 order-lg-1 main-cat">

                    <div class="row mb-3">
                        <?php include('modules/left_sidebar.php') ?>
                    </div>
                    
                </div>

                <div class="col-lg-9 order-1 order-lg-2 mb-5 main-product search_rs">

                    <div class="row mb-3">
                        <div class="col-12">
                            <h4>Kết quả tìm kiếm: <span><?=$_GET['q']?></span></h4>
                            <div class="row mt-4">
                                <?php if (!empty($product_list)): ?>
                                    <?php foreach ($product_list as $v): ?>
                                        <div class="col-6 col-md-3 mb-4">
                                            <div class="product-item">
                                                <figure class="text-center">
                                                    <a href="product.php?id=<?=$v['Product_ID']?>" title="<?=$v['Product_Name_vi']?>">
                                                        <img class="w-100" src="<?=str_replace('../', '', $v['Product_Thumbnail'])?>" alt="<?=$v['Product_Name_vi']?>">
                                                    </a>
                                                    <figcaption><a href="#"><?=$v['Product_Name_vi']?></a></figcaption>
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
                                        </div>   
                                    <?php endforeach ?>
                                <?php else: ?>
                                    <div class="col-12 mb-4">
                                        <p class="font-weight-bold text-center">Không có sản phẩm phù hợp</p>
                                    </div>
                                <?php endif ?>
                                
                            </div>
                        </div>

                    </div>
                    <!-- <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">
                                    2 <span class="sr-only">(current)</span>
                                </span>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav> -->

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
                                        <img class="d-block w-100" src="uploads/tieucanh.jpg" alt="First slide">
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

            <div class="row main_news">
                <div class="col-lg-4 blog">
                    <h4>KIẾN THỨC NÔNG NGHIỆP</h4>
                    <ul id="scroller">
                        <li class="row">
                            <div class="col-md-12">
                                <img class="img-fluid" src="uploads/kienthuc1.jpg" alt="Firehouse" align="left">
                                <h5><a href="cham-soc-bao-ve-vat-nuoi-trong-mua-nang-nong">Triển vọng từ giống lúa hữu cơ thảo dược tím</a></h5>
                                <p>Lúa thảo dược tím được đưa vào sản xuất ở Quảng Trị đã vài năm nay nhưng trước đây nông dân thâm canh giống lúa...</p>
                            </div>
                        </li>

                        <li class="row">
                            <div class="col-md-12">
                                <img class="img-fluid" src="uploads/kienthuc2.jpg" alt="Firehouse" align="left">
                                <h5><a href="cham-soc-bao-ve-vat-nuoi-trong-mua-nang-nong">Chăm sóc, bảo vệ vật nuôi trong mùa nắng nóng</a></h5>
                                <p>Để chủ động chăm sóc, bảo vệ đàn vật nuôi trong mùa nắng nóng năm nay, ngành nông nghiệp, chính quyền các địa phương...</p>
                            </div>
                        </li>
                    </ul>
                </div>
                 
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

                <div class="col-lg-4">
                    <h4>TIN TỨC NÔNG NGHIỆP</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="post-slider p-2">
                                
                                <div class="owl-two owl-carousel owl-theme">
                                    <div class="item product-item">
                                        <figure>
                                            <a href="#"><img class="w-100" src="uploads/post1.jpg" alt=""></a>
                                        </figure>
                                        <div class="bgdate float-left text-center">
                                            <strong>01</strong>
                                            <br>
                                            THÁNG 1
                                        </div>
                                        <h5><a href="gia-gao-viet-nam-cao-nhat-trong-vong-1-nam">Giá gạo Việt Nam cao nhất trong vòng 1 năm</a></h5>
                                        <p>Giá gạo tại nhiều nước châu Á đều liên tục đi lên và đứng ở mức cao nhất trong vòng 1 năm qua.</p>
                                    </div>

                                    <div class="item product-item">
                                        <figure>
                                            <a href="#"><img class="w-100" src="uploads/post2.jpg" alt=""></a>
                                        </figure>
                                        <div class="bgdate float-left text-center">
                                            <strong>02</strong>
                                            <br>
                                            THÁNG 1
                                        </div>
                                        <h5><a href="#">Trang chủ » Dự báo sâu bệnh » Dự báo sâu bệnh tổng hợp trong tuần (20.04.2020) Dự báo sâu bệnh tổng </a></h5>
                                        <p>Theo Trung tâm Dự báo khí tượng thuỷ văn trung ương, khu vực Đồng bằng sông Cửu Long tuần này thời tiết giao mùa có xen...</p>
                                    </div>
                                    
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <?php include('modules/footer.php') ?>
        
        <!-- JS LIB-->
        <?php include('modules/js.php') ?>
        <!-- END JS LIB -->
    
    </body>
</html>