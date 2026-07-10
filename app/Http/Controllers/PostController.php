<?php

namespace App\Http\Controllers;

use App\Models\Frontend\Page;
use App\Traits\FrontendDataTransform;
use App\Traits\LocalizeController;
use Gornymedia\Shortcodes\Facades\Shortcode;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PostController extends Controller
{
    use FrontendDataTransform;
    use LocalizeController;

    public array $data = [];

    public function index(): string
    {
        $posts = Page::posts()->where('status', 1)
            ->with(['user'])
            ->orderbyDesc('sort')
            ->paginate(10);

        $posts->through(fn (Page $post) => $this->transformPostListItem($post));

        $this->data['posts'] = $posts;

        $html = view('frontend.news.index', $this->data)->render();

        try {
            $html = Shortcode::compile($html);
        } catch (\Throwable $e) {
        }

        return $html;
    }

    public function show(string $slug, int $id): View|RedirectResponse
    {
        $post = Page::posts()
            ->where('slug', $slug)
            ->where('id', (int) $id)
            ->with('user')
            ->first();

        if (! $post) {
            return redirect()->route('news');
        }

        $this->data['post'] = $this->transformPostDetail($post);
        $this->data['categories'] = collect([]);

        $relatedPosts = Page::posts()
            ->where('status', 1)
            ->where('id', '<>', $post->id)
            ->with('user')
            ->limit(3)
            ->orderByDesc('id')
            ->get();

        $this->data['related_posts'] = $this->transformHomeNews($relatedPosts);

        $latestPosts = Page::posts()
            ->where('status', 1)
            ->where('id', '<>', $post->id)
            ->with('user')
            ->limit(5)
            ->orderByDesc('id')
            ->get();

        $this->data['latest_posts'] = $this->transformHomeNews($latestPosts);

        $this->data['seo'] = [
            'seo_title' => $post->seo_title != '' ? $post->seo_title : $post->title,
            'seo_image' => $post->image,
            'seo_description' => $post->seo_description ?? '',
            'seo_keyword' => $post->seo_keyword ?? '',
        ];

        return view('frontend.news.single', $this->data);
    }
}
