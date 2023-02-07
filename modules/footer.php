<?php require_once ('footer_c.php');?>
<?php 
    $atz = new module_footer_controller();
    $articles = $atz->get_articles();
?>

<footer class="footer-area">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="noidung">
                    <h2 class="footer-title"><?=SETTING['Setting_Title']?></h2>
                    <p><strong><i>Thời gian hoạt động:</i></strong></p>
                    <p><i>Thứ 2 - Chủ Nhật: 8h - 18h30 (Kể cả các ngày Lễ - Tết)</i></p>
                    <p><u><strong>Địa chỉ:</strong></u> <?=SETTING['Setting_Address']?></p>
                    <p><a href="tel:<?=str_replace(' ', '', SETTING['Setting_Phone'])?>"><u><i>Hotline (Sỉ và Lẻ):</i></u> <strong><?=SETTING['Setting_Phone']?></strong> - Mr.Đạt</a></p>
                    <p><a href="mailto:<?=SETTING['Setting_Email']?>"><i>Email:</i> <?=SETTING['Setting_Email']?></a></p>
                    <p><i>Website:</i> https://vattunongnghiep58.com/</p>
                </div>

                <div class="box-mxh my-4">
                    <!-- <a href="https://mail.google.com/mail/u/0/#inbox" target="_blank">
                        <img src="<?=$atz->site_url['main']?>assets/images/icon-google.png" alt="Googe">
                    </a> -->
                    <a href="https://www.facebook.com/vattunongnghiepso58" target="_blank">
                        <img src="<?=$atz->site_url['main']?>assets/images/icon-facebook.png" alt="Facebook">
                    </a>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <h2 class="tieude-footer">Chính sách hỗ trợ</h2>
                <?php if (!empty($articles)): ?>
                    <ul class="list-unstyled">
                        <?php foreach ($articles as $v): ?>
                            <li><a href="<?=$atz->site_url['main'].'bai-viet/'.$atz->slug($v['Article_Title_vi']).'-'.$v['Article_ID'].'.html'?>" title="<?=$v['Article_Title_vi']?>"><?=$v['Article_Title_vi']?></a></li>    
                        <?php endforeach ?>
                    </ul>    
                <?php endif ?>
                
            </div>
            <div class="col-md-3 col-sm-6">
                <h2 class="tieude-footer">Fanpage facebook</h2>
                <div class="fb-page fb_iframe_widget" data-href="https://www.facebook.com/vattunongnghiepso58" data-tabs="timeline" data-width="500" data-height="205" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" fb-xfbml-state="rendered" fb-iframe-plugin-query="adapt_container_width=true&amp;app_id=&amp;container_width=278&amp;height=205&amp;hide_cover=false&amp;href=https%3A%2F%2Fwww.facebook.com%2Fvattunongnghiepso58&amp;locale=en_US&amp;sdk=joey&amp;show_facepile=true&amp;small_header=false&amp;tabs=timeline&amp;width=500"><span style="vertical-align: bottom; width: 278px; height: 205px;"><iframe name="f3e7b36f7fb9fa" width="500px" height="205px" data-testid="fb:page Facebook Social Plugin" title="fb:page Facebook Social Plugin" frameborder="0" allowtransparency="true" allowfullscreen="true" scrolling="no" allow="encrypted-media" src="https://www.facebook.com/v3.0/plugins/page.php?adapt_container_width=true&amp;app_id=&amp;channel=https%3A%2F%2Fstaticxx.facebook.com%2Fx%2Fconnect%2Fxd_arbiter%2F%3Fversion%3D46%23cb%3Df39545c3bdb21c4%26domain%3Dvattunongnghiep58.com%26origin%3Dhttp%253A%252F%252Fvattunongnghiep58.com%252Ff3cde020d0cdb08%26relation%3Dparent.parent&amp;container_width=278&amp;height=205&amp;hide_cover=false&amp;href=https%3A%2F%2Fwww.facebook.com%2Fvattunongnghiepso58&amp;locale=en_US&amp;sdk=joey&amp;show_facepile=true&amp;small_header=false&amp;tabs=timeline&amp;width=500" style="border: none; visibility: visible; width: 278px; height: 205px;" class=""></iframe></span></div>
            </div>
        </div>
        
        <div id="copyright">Copyright © 2021 <strong><?=SETTING['Setting_Title']?></strong>. All rights reserved. Design by <strong>GetAtZ</strong></div>
    </div>
</footer>

<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1959.1661648542636!2d106.67085005798424!3d10.86230823221918!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTDCsDUxJzQ0LjMiTiAxMDbCsDQwJzE5LjAiRQ!5e0!3m2!1svi!2s!4v1590738223317!5m2!1svi!2s" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
