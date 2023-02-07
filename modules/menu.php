<?php require_once ('menu_c.php');?>
<?php 
    $atz = new module_menu_controller();
    $cats = $atz->get_cats();

    $product_list_url = $atz->site_url['main'].'danh-sach-san-pham/';
    $post_list_url = $atz->site_url['main'].'danh-sach-tin-tuc/';
?>

<nav class="navbar sticky-top navbar-expand-lg navbar-dark main-menu py-1 custom-toggler">

    <div class="container">
        <button class="custom-toggler navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span> Menu
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?=$atz->site_url['main']?>" title="Trang chủ">Trang chủ</a>
                </li>
                <?php if (!empty($cats)): ?>
                    <?php foreach ($cats as $k => $v): ?>
                        <li class="nav-item <?=!empty($v['Cat_Child'])?'dropdown':''?>">
                            <?php if (!empty($v['Cat_Child'])): ?>
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?=$v['Cat_Name_vi']?>">
                                    <?=$v['Cat_Name_vi']?>
                                </a>
                            <?php else: ?>
                                <?php if ($v['Cat_Type']=='product'): ?>
                                    <a class="nav-link" href="<?=$product_list_url.$atz->slug($v['Cat_Name_vi']).'-'.$v['Cat_ID'].'.html'?>" title="<?=$v['Cat_Name_vi']?>"><?=$v['Cat_Name_vi']?></a>
                                <?php elseif($v['Cat_Type']=='post'):?>
                                    <a class="nav-link" href="<?=$post_list_url.$atz->slug($v['Cat_Name_vi']).'-'.$v['Cat_ID'].'.html'?>" title="<?=$v['Cat_Name_vi']?>"><?=$v['Cat_Name_vi']?></a>
                                <?php endif ?>
                            <?php endif ?>
                            
                            <?php if (!empty($v['Cat_Child'])): ?>
                                <ul class="dropdown-menu">
                                    <?php foreach ($v['Cat_Child'] as $v1): ?>
                                        <li class="<?=!empty($v1['Cat_Child'])?'dropdown-submenu':''?>">

                                            <?php if (!empty($v1['Cat_Child'])):?>
                                                <a class="dropdown-item dropdown-toggle" href="#" title="<?=$v1['Cat_Name_vi']?>"><?=$v1['Cat_Name_vi']?></a>
                                            <?php else: ?>
                                                <?php if ($v1['Cat_Type']=='product'): ?>
                                                    <a class="dropdown-item" href="<?=$product_list_url.$atz->slug($v1['Cat_Name_vi']).'-'.$v1['Cat_ID'].'.html'?>" title="<?=$v1['Cat_Name_vi']?>"><?=$v1['Cat_Name_vi']?></a>
                                                <?php elseif($v1['Cat_Type']=='post'):?>
                                                    <a class="dropdown-item" href="<?=$post_list_url.$atz->slug($v1['Cat_Name_vi']).'-'.$v1['Cat_ID'].'.html'?>" title="<?=$v1['Cat_Name_vi']?>"><?=$v1['Cat_Name_vi']?></a>
                                                <?php endif ?>
                                            <?php endif ?>

                                            <?php if (!empty($v1['Cat_Child'])): ?>
                                                <div class="dropdown-menu">
                                                    <?php foreach ($v1['Cat_Child'] as $v2):?>
                                                        <?php if ($v2['Cat_Type']=='product'): ?>
                                                            <a class="dropdown-item" href="<?=$product_list_url.$atz->slug($v2['Cat_Name_vi']).'-'.$v2['Cat_ID'].'.html'?>" title="<?=$v2['Cat_Name_vi']?>"><?=$v2['Cat_Name_vi']?></a>
                                                        <?php elseif($v2['Cat_Type']=='post'):?>
                                                            <a class="dropdown-item" href="<?=$post_list_url.$atz->slug($v2['Cat_Name_vi']).'-'.$v2['Cat_ID'].'.html'?>" title="<?=$v2['Cat_Name_vi']?>><?=$v2['Cat_Name_vi']?></a>
                                                        <?php endif ?>
                                                    <?php endforeach ?>
                                                </div>
                                            <?php endif?>
                                        </li>
                                    <?php endforeach ?>
                                </ul>
                            <?php endif ?>
                        </li>
                    <?php endforeach ?>
                <?php endif ?>
                
                <li class="nav-item">
                    <a class="nav-link" href="<?=$atz->site_url['main']?>lien-he.html" title="Liên hệ">Liên hệ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?=$atz->site_url['main']?>gio-hang.html" title="Giỏ hàng">Giỏ hàng</a>
                </li>
            </ul>
            <form action="search.php" class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Tìm kiếm" name=q value="<?=isset($_GET['q'])?$_GET['q']:''?>">
                <button type="submit" class="btn btn-success my-2 my-sm-0"><i class="fa fa-search fa-fw"></i></</button>
            </form>
        </div>
    </div>
    
</nav>