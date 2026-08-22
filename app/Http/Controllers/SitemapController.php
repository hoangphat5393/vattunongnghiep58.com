<?php

namespace App\Http\Controllers;

use DB;

class SitemapController extends Controller
{
    public function Home()
    {

        $protocol = '';

        $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === false ? 'http' : 'https';

        if (isset($_SERVER['HTTPS'])) {

            if ($_SERVER['HTTPS'] == 'on') {

                $protocol = 'https:';

            } else {

                $protocol = 'http:';

            }

        } else {

            $protocol = 'http:';

        }

        $url_map = setting_option('url-sitemap');

        $datetime_format_php = date('Y-m-d');

        return response()->view('sitemap.index', [

            'url_map' => $url_map,

            'datetime_format_php' => $datetime_format_php,

        ])->header('Content-Type', 'text/xml');

    }

    public function mapStatic()
    {

        $datetime_format_php = date('Y-m-d');

        $url_map = setting_option('url-home');

        return response()->view('sitemap.static', [

            'url_map' => $url_map,

            'datetime_format_php' => $datetime_format_php,

        ])->header('Content-Type', 'text/xml');

    }

    public function Page()
    {

        // list page

        $url_map = setting_option('url-home');

        $data_customers_news = DB::table('page')

            ->where('page.status', '=', 0)

            ->where('page.template', '=', 0)

            ->where('page.content', '!=', '')

            ->select('page.*')

            ->orderBy('page.updated', 'DESC')

            ->get();

        return response()->view('sitemap.page', [

            'url_map' => $url_map,

            'data_customers' => $data_customers_news,

        ])->header('Content-Type', 'text/xml');

    }

    public function CatPost()
    {

        // list the loai tin

        $url_map = setting_option('url-home');

        $categories = DB::table('category')

            ->where('category.status_category', '=', 0)

            ->select('category.*')

            ->get();

        return response()->view('sitemap.category_post', [

            'url_map' => $url_map,

            'categories' => $categories,

        ])->header('Content-Type', 'text/xml');

    }

    public function Post()
    {

        // list tin tuc

        $url_map = setting_option('url-home');

        $datas = DB::table('category')

            ->join('join_category_post', 'category.categoryID', '=', 'join_category_post.id_category')

            ->join('post', 'join_category_post.id_post', '=', 'post.id')

            ->where('post.status', '=', 0)

            ->orderByRaw('post.order_short DESC,post.updated DESC')

            ->select('post.*', 'category.categoryName', 'category.categorySlug', 'category.categoryDescription', 'category.categoryID', 'category.categoryParent')

            ->get();

        return response()->view('sitemap.post', [

            'url_map' => $url_map,

            'datas' => $datas,

        ])->header('Content-Type', 'text/xml');

    }

    public function CatTheme()
    {

        // list the loai du an

        $url_map = setting_option('url-home');

        $categories = DB::table('category_theme')

            ->where('category_theme.status_category', '=', 0)

            ->select('category_theme.*')

            ->get();

        return response()->view('sitemap.category_theme', [

            'url_map' => $url_map,

            'categories' => $categories,

        ])->header('Content-Type', 'text/xml');

    }

    public function Theme()
    {

        // list du an

        $url_map = setting_option('url-home');

        $datas = DB::table('category_theme')

            ->join('join_category_theme', 'category_theme.categoryID', '=', 'join_category_theme.id_category_theme')

            ->join('theme', 'join_category_theme.id_theme', '=', 'theme.id')

            ->where('theme.status', '=', 0)

            ->orderByRaw('theme.order_short DESC,theme.updated DESC')

            ->select('theme.*', 'category_theme.categorySlug', 'category_theme.categoryDescription', 'category_theme.categoryID', 'category_theme.categoryParent')

            ->get();

        return response()->view('sitemap.theme', [

            'url_map' => $url_map,

            'datas' => $datas,

        ])->header('Content-Type', 'text/xml');

    }
}
