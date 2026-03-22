@php
    $lc = app()->getLocale();
    // $footerMenu = \App\Models\Frontend\Menus::where('name', 'Menu-footer-' . $lc)->first();
    $footerMenu = \App\Models\Frontend\Menu::where('name', 'Menu-footer-vi')->first();
@endphp

{{-- Footer Start --}}
<footer class="site-footer">
    <div class="particles-snow" id="footer-snow"></div><!-- /#footer-snow.particles-snow -->

    <img src="assets/images/shapes/footer-shape-1-1.png" class="site-footer__bg-shape-1" alt="">
    <img src="assets/images/shapes/footer-shape-1-2.png" class="site-footer__bg-shape-2" alt="">
    <img src="assets/images/shapes/footer-shape-1-3.png" class="site-footer__bg-shape-3" alt="">
    <img src="assets/images/shapes/footer-shape-1-4.png" class="site-footer__bg-shape-4" alt="">
    <div class="site-footer__upper">
        <div class="container">
            <div class="row">

                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="footer-widget footer-widget__about">
                        <h3 class="footer-widget__title">GetAZ</h3>
                        <p>Chúng tôi tập trung vào nhu cầu của các doanh nghiệp thị trường nhỏ đến trung bình để cải thiện và tăng lợi nhuận của họ.</p>
                        <div class="footer-widget__social">
                            <a href="https://www.facebook.com/Get-ATZ-112703140075288" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <!-- <a href="#"><i class="fab fa-twitter"></i></a> -->
                            <!-- <a href="#"><i class="fab fa-google-plus-g"></i></a> -->
                        </div><!-- /.footer-widget__social -->
                    </div><!-- /.footer-widget footer-widget__about -->
                </div><!-- /.col-lg-3 col-md-6 col-sm-12 -->

                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="footer-widget footer-widget__links__1">
                        <h3 class="footer-widget__title">Thông tin</h3>
                        <ul class="list-unstyled footer-widget__links-list">
                            <li><a href="http://getatzold.com.test/bai-viet/Chinh-sach-bao-mat-2.html" title="Chính sách bảo mật">Chính sách bảo mật</a></li>
                            <li><a href="http://getatzold.com.test/bai-viet/Thoa-Thuan-Su-Dung-3.html" title="Thỏa Thuận Sử Dụng">Thỏa Thuận Sử Dụng</a></li>
                        </ul><!-- /.list-unstyled footer-widget__links-list -->
                    </div><!-- /.footer-widget -->
                </div><!-- /.col-lg-3 col-md-6 col-sm-12 -->

                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="footer-widget footer-widget__links__2">
                        <h3 class="footer-widget__title">Hỗ Trợ</h3>
                        <p>Hotline phục vụ liên tục 365 ngày trong năm và 24 giờ mỗi ngày.</p>
                    </div>
                </div><!-- /.col-lg-3 col-md-6 col-sm-12 -->

                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="footer-widget footer-widget__contact">
                        <h3 class="footer-widget__title">Liên Hệ</h3>
                        <address>127 đường TL29, Phường Thạnh Lộc, Quận 12, Thành phố Hồ Chí Minh, Việt Nam</address>
                        <p>Mã số thuế: 0317271922</p>
                        <p><a href="mailto:getallatz@gmail.com " title="Gửi mail"><i class="fa fa-envelope fa-fw"></i> getallatz@gmail.com </a></p>
                        <p><a href="tel:0937226422" title="Gọi điện"><i class="fa fa-phone fa-fw"></i> 0937 22 64 22</a></p>
                    </div>
                </div><!-- /.col-lg-3 col-md-6 col-sm-12 -->

            </div><!-- /.row -->
        </div><!-- /.container -->

    </div><!-- /.site-footer__upper -->
    <div class="site-footer__bottom">
        <div class="container">
            <p>© 2022 - Bản quyền GETAZ CO.,LTD</p>
            <!-- <a href="http://getatzold.com.test/"><img src="http://getatzold.com.test/assets/images/logo.png" alt="GetAZ" width="51" height="44"></a>
            <ul class="list-unstyled site-footer__bottom-menu">
                <li><a href="#">Privace & Policy.</a></li>
                <li><a href="#">Faq.</a></li>
                <li><a href="#">Terms.</a></li>
            </ul> -->
            <!-- /.list-unstyled site-footer__bottom-menu -->
        </div><!-- /.container -->
    </div><!-- /.site-footer__bottom -->

</footer><!-- /.site-footer --> <!-- END FOOTER -->
