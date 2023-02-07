<?php require_once ('left_sidebar_c.php');?>
<?php 
    $module_left_sidebar_class = new module_left_sidebar_controller();
    $left_cats = $module_left_sidebar_class->get_cats();
?>


<!-- DANH MỤC SẢN PHẨM -->
<div class="col-sm-12 col-md-6 col-md-12 mb-4 d-none d-lg-block">
    <div class="bg-title">
        <h2>DANH MỤC SẢN PHẨM</h2> 
    </div>

    <div class="main-cat-content">
        <?php if (!empty($left_cats)): ?>
            <ul class="list-group list-group-flush">

                <?php foreach ($left_cats as $v): ?>
                    <li class="list-group-item">    
                        <?php if (!empty($v['Cat_Child'])): ?>
                            <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                                <?=$v['Cat_Name_vi']?>
                            </a>
                        <?php else: ?>
                            <a class="" href="<?=$v['Cat_Type']=='product'?'product_list.php':'post_list.php'?>?id=<?=$v['Cat_ID']?>"><?=$v['Cat_Name_vi']?></a>
                        <?php endif ?>

                        <?php if (!empty($v['Cat_Child'])): ?>
                            <ul class="dropdown-menu">
                                <?php foreach ($v['Cat_Child'] as $v1): ?>
                                    <li class="<?=!empty($v1['Cat_Child'])?'dropdown-submenu':''?>">

                                        <?php if (!empty($v1['Cat_Child'])): ?>
                                            <a class="dropdown-item dropdown-toggle" href="#"><?=$v1['Cat_Name_vi']?></a>
                                        <?php else: ?>
                                            <a class="dropdown-item" href="<?=$v1['Cat_Type']=='product'?'product_list.php':'post_list.php'?>?id=<?=$v1['Cat_ID']?>"><?=$v1['Cat_Name_vi']?></a>
                                        <?php endif ?>

                                        <?php if (!empty($v1['Cat_Child'])): ?>
                                            <div class="dropdown-menu">
                                                <?php foreach ($v1['Cat_Child'] as $v2): ?>
                                                    <a class="dropdown-item" href="<?=$v2['Cat_Type']=='product'?'product_list.php':'post_list.php'?>?id=<?=$v2['Cat_ID']?>"><?=$v2['Cat_Name_vi']?></a>
                                                <?php endforeach ?>
                                            </div>
                                        <?php endif?>
                                    </li>
                                <?php endforeach ?>
                            </ul>

                         
                        <?php endif ?>

                    </li>
                <?php endforeach ?>
            </ul>
        <?php endif?>
    </div>   
</div>

<!-- HỖ TRỢ TRỰC TUYẾN -->
<div class="col-sm-12 col-md-6 col-md-12">
    <div class="bg-title">
        <h2>HỖ TRỢ TRỰC TUYẾN</h2> 
    </div>

    <div class="main-cat-content main-support">
        <img class="w-100" src="<?=$atz->site_url['main']?>assets/images/bg-hotro.jpg" alt="">
        <div class="phone p-1">
            <img class="img-fluid mr-2" src="<?=$atz->site_url['main']?>assets/images/icon-phone.png" alt="">
            <p>HOTLINE:<br><span><?=SETTING['Setting_Phone']?></span></p>
        </div>
        <div class="box-support mt-1">
            <strong>Liên Hệ Đặt Hàng: </strong>
            <a href="https://zalo.me/<?=str_replace(' ', '', SETTING['Setting_Phone'])?>" target="_blank">
            <img src="<?=$atz->site_url['main']?>assets/images/icon-zalo.png" alt="icon-zalo"></a>
            <p class="text-break">
                Hotline: <a href="tel:<?=str_replace(' ', '', SETTING['Setting_Phone'])?>" class="phone-number"><?=SETTING['Setting_Phone']?></a>
                <br>
                <a href="mailto:<?=SETTING['Setting_Email']?>"><?=SETTING['Setting_Email']?></a>
            </p>
        </div>
    </div>
</div>