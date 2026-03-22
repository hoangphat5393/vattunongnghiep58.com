@php
    use Illuminate\Support\Facades\Auth;
    if (Auth::check()) {
        $user = Auth::user();
        $avatar = public_path('img/users/avatar/') . $user->avatar;
    }
    $lc = app()->getLocale();
    $headerMenu = \App\Models\Frontend\Menu::where('name', 'Menu-main-' . $lc)->first();
@endphp

<nav class="main-nav-one stricky">
    <div class="container-fluid">
        <div class="inner-container">
            <div class="logo-box">
                <a href="{{ setting_option('weblink') }}">
                    <img src="{{ setting_option('logo_' . $lc) }}" alt="{{ setting_option('webtitle') }}" title="{{ setting_option('webtitle') }}" width="63" height="54">
                </a>
                <a href="#" class="side-menu__toggler"><i class="fa fa-bars"></i></a>
            </div><!-- /.logo-box -->
            <div class="main-nav__main-navigation">
                <ul class="main-nav__navigation-box">

                    <li class="">
                        <a href="http://getatzold.com.test/danh-sach-tin-tuc/Cong-nghe-3.html" title="Công nghệ">Công nghệ</a>

                    </li>
                    <li class="dropdown">
                        <a href="http://getatzold.com.test/danh-sach-tin-tuc/Dich-vu-4.html" title="Dịch vụ">Dịch vụ</a>

                        <ul>
                            <li>
                                <a href="http://getatzold.com.test/danh-sach-tin-tuc/Thiet-ke-website-5.html" title="Thiết kế website">Thiết kế website</a>
                            </li>
                        </ul>
                    </li>
                    <li class="">
                        <a href="http://getatzold.com.test/danh-sach-du-an/du-an-7.html" title="dự án">dự án</a>

                    </li>
                    <li class="dropdown">
                        <a href="http://getatzold.com.test/danh-sach-tin-tuc/Tin-Tuc-2.html" title="Tin Tức">Tin Tức</a>

                        <ul>
                            <li>
                                <a href="http://getatzold.com.test/danh-sach-tin-tuc/Kien-Thuc-Website-6.html" title="Kiến Thức Website">Kiến Thức Website</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="http://getatzold.com.test/hosting.html">Hosting</a>
                    </li>
                </ul><!-- /.main-nav__navigation-box -->
            </div><!-- /.main-nav__main-navigation -->
            <div class="main-nav__right">
                <a href="#" class="search-popup__toggler main-nav__search"><i class="far fa-search"></i></a>
                <a href="http://getatzold.com.test/lien-he.html" class="thm-btn main-nav-one__btn" title="Liên Hệ"><span>Liên hệ</span></a>
                <!-- /.thm-btn main-nav-one__btn -->
            </div><!-- /.main-nav__right -->
        </div><!-- /.inner-container -->
    </div><!-- /.container-fluid -->
</nav><!-- /.main-nav-one --> <!-- END MENU -->
