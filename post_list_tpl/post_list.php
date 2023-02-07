<?php require_once('post_list_c.php');?>
<?php 
    $atz = new post_list_controller();

    $cat = array();
    $post_list = array();

    if(!empty($atz->id)){
        $cat = $atz->get_cat($atz->id);
            
        if(!empty($cat)){
            $cat_list = $atz->get_cats($cat['Cat_ID']);
            $post_list = $atz->get_posts($cat_list);
        }
    }   
?>

<!doctype html>
<html lang="vi">

    <head>
        
        <!-- HEAD -->
        <?php include('modules/head.php') ?>
        <!-- END HEAD -->

        <!-- SEO -->
        <meta name="description" content="<?=$cat['Cat_Description_vi']?>">
        <meta name="keywords" content="<?=$cat['Cat_Keywords_vi']?>">

        <title><?=$cat['Cat_Name_vi']?></title>
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

                <div class="col-lg-9 order-1 order-lg-2 mb-5 main-product">

                    <div class="row mb-3">
                        <div class="col-12">
                            
                            <?php if ($cat): ?>
                                
                                <h3><?=$cat['Cat_Name_vi']?></h3>
                            
                                <div class="row post-item mt-4">
                                    <?php if (!empty($post_list)): ?>
                                        <?php foreach ($post_list as $v): ?>
                                            <?php 
                                                $permalink = $atz->site_url['main'].'tin-tuc/'.$atz->slug($v['Post_Title_vi']).'-'.$v['Post_ID'].'.html';
                                                $img_link = $v['Post_Thumbnail'];
                                            ?>
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <a href="<?=$permalink?>" title="<?=$v['Post_Title_vi']?>">
                                                            <img src="<?=$img_link?>" alt="<?=$v['Post_Title_vi']?>">
                                                        </a>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <h4><a href="<?=$permalink?>" title="<?=$v['Post_Title_vi']?>"><?=$v['Post_Title_vi']?></a></h4>
                                                        <i class="far fa-calendar-alt"></i> <i><?=date('d-m-Y',$v['Post_Created'])?></i>
                                                    </div>
                                                </div>
                                                <p class="mt-2"><?=$v['Post_Description_vi']?></p>
                                            </div>        
                                        <?php endforeach ?>
                                    <?php else: ?>
                                        <div class="col-12">
                                            <p class="font-weight-bold text-center">Chuyên mục này chưa có bài viết</p>
                                        </div>
                                    <?php endif ?>
                            
                                </div>
                            <?php else: ?>
                                <p class="font-weight-bold text-center">Không có chuyên mục này</p>
                            <?php endif ?>
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


        <?php include('modules/footer.php') ?>
        
        <!-- JS LIB-->
        <?php include('modules/js.php') ?>
        <!-- END JS LIB -->
    </body>
</html>