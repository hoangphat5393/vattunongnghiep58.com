<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Frontend\Page;
use App\Models\Frontend\Category;
// use App\Models\Frontend\Post;
use DB;

class NewsController extends Controller
{
    use \App\Traits\LocalizeController;

    public $data = [];

    // All categories
    public function index()
    {
        // All category
        // $categories = Category::where(['status' => 1, 'type' => 'post', 'parent' => 0])->get();

        // All news 
        $news = Page::posts()->where('status', 1)
            ->orderbyDesc('sort')
            ->paginate(10);

        // Lastest news
        $feature_news = Page::posts()->where('status', 1)
            ->orderbyDesc('id')
            ->limit(1)
            ->get();

        // default data
        // $this->data['categories'] = $categories;
        $this->data['news'] = $news;

        // extra data
        $this->data['feature_news'] = $feature_news;

        return view('frontend.news.index', $this->data);
    }

    // Single category
    // public function categoryDetail($slug)
    // {

    //     $category = Category::where('slug', $slug)->first();

    //     if ($category) {
    //         $this->data['category'] = $category;
    //         $this->data['category_child'] = $category->children();

    //         $this->data['news'] = $news = $category->posts()
    //             ->where('status', 1)
    //             ->orderbyDesc('sort')->orderbyDesc('id')
    //             ->paginate(6);

    //         $this->data['seo'] = [
    //             'seo_title' => $category->seo_title != '' ? $category->seo_title : $category->name,
    //             'seo_image' => $category->image,
    //             'seo_description'   => $category->seo_description ?? '',
    //             'seo_keyword'   => $category->seo_keyword ?? '',
    //         ];
    //         // return view($this->templatePath . '.news.index', $this->data);

    //         // Nếu chỉ có 1 bài viết thì điều hướng tới bài vô bài viết đó luôn
    //         // if ($news->count() == 1) {
    //         //     return $this->newsDetail($news->first()->slug);
    //         // }
    //         return view('frontend.news.category', $this->data);
    //     } else
    //         return view('errors.404');
    //     // return $this->newsDetail($slug);
    // }

    // News detail
    public function newsDetail($slug)
    {
        $news = Page::posts()->where('slug', $slug)->with('user')->first();

        if (!$news) {
            return redirect()->route('news');
        }

        $this->data['news'] = $news;
        $this->data['categories'] = collect([]);

        // Related News (bảng category_page đã xóa, lấy tin mới nhất)
        $related_news = Page::posts()
            ->where('status', 1)
            ->where('id', '<>', $news->id)
            ->with('user')
            ->limit(3)
            ->orderByDesc('id')
            ->get();
        $this->data['related_news'] = $related_news;

        // Latest news for sidebar
        $latest_news = Page::posts()
            ->where('status', 1)
            ->where('id', '<>', $news->id)
            ->with('user')
            ->limit(5)
            ->orderByDesc('id')
            ->get();
        $this->data['latest_news'] = $latest_news;

        $this->data['seo'] = [
            'seo_title' => $news->seo_title != '' ? $news->seo_title : $news->title,
            'seo_image' => $news->image,
            'seo_description'   => $news->seo_description ?? '',
            'seo_keyword'   => $news->seo_keyword ?? '',
        ];

        return view('frontend.news.single', $this->data);
    }
}
