<?php

namespace App\Http\Controllers;

use App\Models\Frontend\Product;
use App\Traits\FrontendDataTransform;
use App\Traits\LocalizeController;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends Controller
{
    use FrontendDataTransform;
    use LocalizeController;

    public array $data = [];

    public function index(Request $request)
    {
        $this->localized();
        $lc = app()->getLocale();

        $this->data['keyword'] = $request->input('keyword', '');

        if ($request->filled('keyword')) {
            $keyword = '%'.$request->input('keyword').'%';

            $query = Product::query()->where('status', 1);

            if ($lc === 'vi') {
                $query->where('name', 'like', $keyword);
            } else {
                $query->where('name_'.$lc, 'like', $keyword);
            }

            $products = $query
                ->select('id', 'name', 'slug', 'image', 'price', 'price_type')
                ->paginate(6);

            $products->through(fn (Product $product) => $this->transformProductCard($product));

            $this->data['products'] = $products;
        } else {
            $this->data['products'] = new LengthAwarePaginator([], 0, 6);
        }

        return view('frontend.search', $this->data);
    }

    public static function searchMuiltiple($keyword = '')
    {
        $lc = app()->getLocale();
        if ($keyword) {
            $ex = explode(' ', $keyword);

            $db = self::select('*');
            foreach ($ex as $v) {
                $v = '%'.addslashes($v).'%';
                if ($lc == 'vi') {
                    $db->orwhere('name', 'like', $v);
                } else {
                    $db->orwhere('name_'.$lc, 'like', $v);
                }
            }
            foreach ($ex as $v) {
                $db->orwhere('sku', 'like', $v);
            }
        }
        if ($lc == 'vi') {
            $db->orderby('name', 'asc');
        } else {
            $db->orderby('name_'.$lc, 'asc');
        }
        $result = $db->paginate(20)->appends('keyword', $keyword);

        return $result;
    }
}
