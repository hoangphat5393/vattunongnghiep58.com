<!-- LIB -->
<?php require_once('product_c/product_c.php');?>

<?php 
    $atz = new product_controller();

    $product = $atz->get_product();
    if (!empty($product)) {
        $cat = $atz->get_cat($product['Product_Cat']);

        // Update View
        $atz->update_view($product['Product_ID'],$product['Product_View_vi']);
    }
        
?>

<!doctype html>
<html lang="vi">

    <head>       
        <!-- HEAD -->
        <?php include('modules/head.php') ?>
        <!-- END HEAD -->

        <!-- SEO -->
        <meta name="description" content="<?=$product['Product_Description_vi']?>">
        <meta name="keywords" content="<?=$product['Product_Keywords_vi']?>">

        <title><?=$product['Product_Name_vi']?></title>
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
                        <?php include('modules/left_sidebar.php')?>
                    </div>
                </div>

                <div class="col-lg-9 order-1 order-lg-2 mb-5 main-product">

                    <div class="row mb-3">
                        <div class="col-12 product-item product-detail">
                            <?php if (!empty($product)): ?>
                                <h3 class="mb-4"><?=$cat['Cat_Name_vi']?></h3>
                                
                                <form action="cart.php" method="post">
                                    <div class="row">
                                        <div class="col-md-6">
                                            
                                            
                                            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                                
                                                <!-- Indicators -->
                                                <ol class="carousel-indicators">
                                                    <?php $i=0; ?>
                                                    <?php foreach ($product['Product_Imgs'] as $v): ?>
                                                        <li class="<?=$i==0?'active':''?>" data-target="#carouselExampleIndicators" data-slide-to="<?=$i?>"></li>
                                                    <?php $i++ ?>
                                                    <?php endforeach ?>
                                                </ol>

                                                <!-- slides -->
                                                <div class="carousel-inner">
                                                    <?php $i=0; ?>
                                                    <?php foreach ($product['Product_Imgs'] as $v): ?>
                                                        <div class="carousel-item <?=$i==0?'active':''?>"> 
                                                            <img class="d-block w-100" src="<?=$atz->site_url['upload'].'product/'.$v?>" alt="<?=$product['Product_Name_vi']?>">
                                                        </div>
                                                        <?php $i++; ?>
                                                    <?php endforeach ?>
                                                </div>

                                                <!-- Left right -->
                                                <a class="carousel-control-prev" href="#carouselExampleIndicators" data-slide="prev"> 
                                                    <span class="carousel-control-prev-icon"></span> 
                                                </a> 
                                                <a class="carousel-control-next" href="#carouselExampleIndicators" data-slide="next"> 
                                                    <span class="carousel-control-next-icon"></span> 
                                                </a>

                                                
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <h1><?=$product['Product_Name_vi']?></h1>
                                            <div class="follow mb-3">
                                                <i class="far fa-calendar-alt"></i> <i><?=date('d-m-Y',$product['Product_Created'])?></i>&emsp;
                                                <i class="fa fa-eye"></i> <i>Lượt xem: <?=$product['Product_View_vi']?></i>
                                            </div>
                                            <p><?=$product['Product_Description_vi']?></p>
                                            <big>Giá:</big>
                                            <?php if ($product['Product_Price']): ?>
                                                <span class="price">
                                                    <?=number_format($product['Product_Price'],0,',','.').'đ'?>        
                                                </span>
                                            <?php else: ?>
                                                <span class="price">
                                                    <a href="tel:<?=str_replace(' ', '', SETTING['Setting_Phone'])?>">Liên hệ</a>
                                                </span>
                                            <?php endif ?>
                                            <br>
                                            <input type="hidden" name="id" class="form-control" value="<?=$product['Product_ID']?>">
                                            <button type="submit" class="btn add_to_cart transition mt-3"><i class="fas fa-cart-plus"></i> Mua Ngay</button>
                                        </div>
                                    </div>
                                </form>
                                
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="product-tab_content">Thông tin chi tiết</div>
                                        <div class="product-content">
                                            <?=$product['Product_Content_vi']?>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p>Không có sản phẩm này</p>
                            <?php endif ?>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!-- END PRODUCT -->

        <?php include('modules/footer.php') ?>
        
        <!-- JS LIB-->
        <?php include('modules/js.php') ?>
        <!-- END JS LIB -->
    </body>
</html>