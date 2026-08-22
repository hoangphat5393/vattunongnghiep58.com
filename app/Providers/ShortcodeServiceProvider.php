<?php

namespace App\Providers;

use App\Models\Frontend\Album;
use App\Models\Frontend\News;
use App\Models\Frontend\Page;
use App\Models\Frontend\Product;
use App\Models\Frontend\Work;
use Gornymedia\Shortcodes\Facades\Shortcode;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ShortcodeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

        //

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        // Short code

        // Slider

        Shortcode::add('slider', function ($atts, $id, $items = 4) {

            $data = Shortcode::atts([

                'id' => $id,

                'items' => $items,

            ], $atts);

            $slider = Album::find($data['id']);

            $file = 'shortcode/slider'; // ex: resource/views/partials/ $atts['name'] .blade.php

            if (View::exists($file)) {

                return view($file, compact('data', 'slider'));

            }

        });

        // Block1 | TẦM NHÌN, SỨ MỆNH, TÔNG QUAN GIẢI CHẠY

        Shortcode::add('block1', function ($atts, $id) {

            $file = 'shortcode/block1'; // ex: resource/views/partials/ $atts['name'] .blade.php

            if (View::exists($file)) {

                return view($file);

            }

        });

        // Block2 | HÌNH THỨC THAM GIA, ĐỐI TƯỢNG THAM GIA

        Shortcode::add('block2', function ($atts, $id) {

            $file = 'shortcode/block2'; // ex: resource/views/partials/ $atts['name'] .blade.php

            if (View::exists($file)) {

                return view($file);

            }

        });

        // Block3 | RACE KIT, GIẤY CHỨNG NHẬN

        Shortcode::add('block3', function ($atts, $id) {

            $file = 'shortcode/block3'; // ex: resource/views/partials/ $atts['name'] .blade.php

            if (View::exists($file)) {

                return view($file);

            }

        });

        // Block4

        Shortcode::add('block4', function ($atts, $id, $items = 4) {

            // Chuyển đổi $items thành số nguyên

            $data = Shortcode::atts([

                'items' => $items,

            ], $atts);

            // Bỏ giới hạn và chỉ phân trang

            $products = Product::limit($data['items'])->get();

            // dd($id, $work);

            $file = 'shortcode/block4'; // ex: resource/views/partials/ $atts['name'] .blade.php

            if (View::exists($file)) {

                return view($file, compact('data', 'products'));

            }

        });

        // Block5

        Shortcode::add('block5', function (

            $atts,

            $id,

            $items = 4

        ) {

            // Chuyển đổi $items thành số nguyên

            $data = Shortcode::atts([

                'items' => $items,

            ], $atts);

            // Bỏ giới hạn và chỉ phân trang

            $products = Product::limit($data['items'])->get();

            // dd($id, $work);

            $file = 'shortcode/block5'; // ex: resource/views/partials/ $atts['name'] .blade.php

            if (View::exists($file)) {

                return view($file, compact('data', 'products'));

            }

        });

        // Block6

        Shortcode::add('block6', function ($atts, $id) {

            $file = 'shortcode/block6'; // ex: resource/views/partials/ $atts['name'] .blade.php

            if (View::exists($file)) {

                return view($file);

            }

        });

        // Block9

        Shortcode::add('block_news_single', function ($atts, $id, $items = 4) {

            // Chuyển đổi $items thành số nguyên

            $data = Shortcode::atts([

                'items' => $items,

            ], $atts);

            // Bỏ giới hạn và chỉ phân trang

            $news = News::limit($data['items'])->get();

            $file = 'shortcode/block9'; // ex: resource/views/partials/ $atts['name'] .blade.php

            if (View::exists($file)) {

                return view($file, compact('data', 'news'));

            }

        });

        // Menu + Slider

        Shortcode::add('menu_slider', function ($atts, $id, $items = 3) {

            $data = Shortcode::atts([

                'id' => $id,

                'items' => $items,

            ], $atts);

            $file = 'shortcode/menu_slider'; // ex: resource/views/partials/ $atts['name'] .blade.php

            if (View::exists($file)) {

                return view($file, compact('data'));

            }

        });

        // Menu + Banner

        Shortcode::add('menu_banner', function ($atts, $id, $slug = 'home') {

            $data = Shortcode::atts([

                'id' => $id,

                'slug' => $slug,

            ], $atts);

            // Bỏ giới hạn và chỉ phân trang

            $page = Page::where('slug', $data['slug'])->first();

            $file = 'shortcode/menu_banner'; // ex: resource/views/partials/ $atts['name'] .blade.php

            if (View::exists($file)) {

                return view($file, compact('data', 'page'));

            }

        });

        // Menu

        Shortcode::add('menu_no_banner', function ($atts, $id) {

            $file = 'shortcode/menu_no_banner'; // ex: resource/views/partials/ $atts['name'] .blade.php

            return view($file);

        });

        // Campagin list

        Shortcode::add('campagin', function ($atts, $id, $items = 3) {

            // Chuyển đổi $items thành số nguyên

            $items = intval($items);

            $data = Shortcode::atts([

                'items' => $items,

            ], $atts);

            // Bỏ giới hạn và chỉ phân trang

            $projects = Work::paginate($items);

            $file = 'shortcode/campagin'; // ex: resource/views/partials/ $atts['name'] .blade.php

            // dd($data);

            if (View::exists($file)) {

                return view($file, compact('data', 'projects'));

            }

        });

        // Video iframe

        // Shortcode::add('video_iframe', function ($atts, $url) {

        //     $data = Shortcode::atts([

        //         'url' => $url

        //     ], $atts);

        //     $file = 'shortcode/video_iframe'; // ex: resource/views/partials/ $atts['name'] .blade.php

        //     if (View::exists($file)) {

        //         return view($file, compact('data'));

        //     }

        // });

    }
}
