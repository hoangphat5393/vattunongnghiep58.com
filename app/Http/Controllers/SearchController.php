<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Frontend\Page;
use App\Models\Frontend\News;
use App\Models\Frontend\Product;
use Carbon\Carbon;

class SearchController extends Controller
{
    use \App\Traits\LocalizeController;
    public $data = [];

    public function index(Request $request)
    {
        $this->localized();
        $lc = app()->getLocale();

        $this->data['keyword'] = $request->input('keyword');

        if ($request->input('keyword')) {

            $keyword = '%' . addslashes($request->input('keyword')) . '%';
            if ('vi' == $lc) {
                $this->data['product'] = Product::where('name', 'like', $keyword)->paginate(6);
            } else {
                $this->data['product'] = Product::where('name_' . $lc, 'like', $keyword)->paginate(6);
            }
        }

        // dd($this->data);

        return view('frontend.search', $this->data);
    }

    // public function index(Request $rq)
    // {
    //     $this->localized();
    //     $lc = app()->getLocale();

    //     $this->data['keyword'] = $rq->input('keyword');

    //     if ($rq->input('keyword')) {

    //         $keyword = '%' . addslashes($rq->input('keyword')) . '%';
    //         if ('vi' == $lc) {
    //             $this->data['news'] = News::where('name', 'like', $keyword)->paginate(6);
    //         } else {
    //             $this->data['news'] = News::where('name_' . $lc, 'like', $keyword)->paginate(6);
    //         }
    //     } else {
    //         $this->data['news'] = [];
    //     }

    //     return view('theme.search', $this->data);
    // }

    public static function searchMuiltiple($keyword = '')
    {
        // Show mysql query
        // DB::enableQueryLog(); // Enable query log
        $lc = app()->getLocale();
        if ($keyword) {
            $ex = explode(' ', $keyword);

            $db = self::select('*');
            foreach ($ex as $v) {
                $v = '%' . addslashes($v) . '%';
                if ('vi' == $lc) {
                    $db->orwhere('name', 'like', $v);
                } else {
                    $db->orwhere('name_' . $lc, 'like', $v);
                }
            }
            foreach ($ex as $v) {
                $db->orwhere('sku', 'like', $v);
            }
        }
        if ('vi' == $lc) {
            $db->orderby('name', 'asc');
        } else {
            $db->orderby('name_' . $lc, 'asc');
        }
        $result = $db->paginate(20)->appends('keyword', $keyword); // pagination &keyword
        return $result;
    }
}
