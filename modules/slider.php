<?php require_once ('slider_c.php');?>
<?php 
    $atz = new module_slide_controller();
    $slides = $atz->get_slides();
?>
 <?php if (!empty($slides)): ?>       
    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <?php $i = 0; ?>
            <?php foreach ($slides as $v): ?>
                <div class="carousel-item <?=$i==0?'active':''?>">
                  <img class="d-block w-100" src="<?=str_replace('../', '', $v['Slide_Img'])?>" alt="<?=$v['Slide_Title_vi']?>" title="<?=$v['Slide_Title_vi']?>">
                </div>
                <?php $i++; ?>
            <?php endforeach ?>
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
<?php endif ?>