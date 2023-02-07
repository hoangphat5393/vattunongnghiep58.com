<div class="container-fluid">
    <div class="row justify-content-center align-items-center my-2">
        <div class="col-4 col-lg-2 text-center">
            <a href="<?=$atz->site_url['main']?>"><img src="<?=$atz->site_url['main']?>assets/images/header/logo.png" alt="" class="img-fluid logo"></a><br>
            <p class="text-1 mb-0"><strong>VẬT TƯ NÔNG NGHIỆP 58</strong></p>
        </div>
        <div class="col-7 col-lg-7 text-center font-weight-bold">
            <h1 class="d-none d-lg-block"><?=str_replace('58','<span>58</span>',SETTING['Setting_Title'])?></h1>
            <p class="text-2 slogan mb-0"><?=SETTING['Setting_Slogan']?></p>
            <p class="d-none d-lg-block text-3 mb-0">Địa chỉ: <?=SETTING['Setting_Address']?></p>
        </div>

        <div class="col-lg-2 text-center d-none d-lg-block">
            <a href="tel:<?=str_replace(' ', '', SETTING['Setting_Phone'])?>" class="btn btn-call" title="Gọi điện"><i class="fa fa-phone fa-fw"></i> <?=SETTING['Setting_Phone']?></a>
        </div>
    </div>
</div>