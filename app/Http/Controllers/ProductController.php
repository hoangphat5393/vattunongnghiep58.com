<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Http\Filters\ProductFilter;
use App\Models\Frontend\Category;

use App\Models\Frontend\Product;
use App\Models\Frontend\Page;
use Session, DB;

// use Carbon\Carbon;

class ProductController extends Controller
{
    use \App\Traits\LocalizeController;
    use \App\Traits\FrontendDataTransform;
    public $data = [];

    // All categories
    public function index($slug = '')
    {
        // All category
        $categories = Category::where(['status' => 1, 'parent' => 0])
            ->orderBy('sort', 'asc')
            ->with(['products' => function ($query) {
                $query->where('status', 1)
                    ->select(
                        'products.id',
                        'products.name',
                        'products.slug',
                        'products.image',
                        'products.price',
                        'products.price_type',
                        'products.sort'
                    );
            }])
            ->get(['id', 'name', 'slug', 'sort', 'parent', 'status']);

        // All news 
        $news = Product::where('status', 1)
            ->orderbyDesc('sort')
            ->paginate(10);

        // Lastest news
        $feature_news = Product::where('status', 1)
            ->orderbyDesc('id')
            ->limit(1)
            ->get();

        // default data
        $this->data['categories'] = $this->transformHomeCategories($categories);
        $this->data['news'] = $news;

        // extra data
        $this->data['feature_news'] = $feature_news;

        // dd($slug);
        // if has slug then get single category data
        if ($slug) {
            return $this->categoryDetail($slug);
        }

        return view('frontend.product.index', $this->data);
    }

    // Single category
    public function categoryDetail($slug)
    {
        $category = Category::where('slug', $slug)->first();

        if ($category) {
            $this->data['category'] = $category;
            $this->data['category_child'] = $category->children();

            $this->data['product'] = $product = $category->products()
                ->where('status', 1)
                ->orderbyDesc('sort')->orderbyDesc('name')->orderbyDesc('id')
                ->paginate(12);

            // dd($this->data['product']);
            $this->data['seo'] = [
                'seo_title' => $category->seo_title != '' ? $category->seo_title : $category->name,
                'seo_image' => $category->image,
                'seo_description'   => $category->seo_description ?? '',
                'seo_keyword'   => $category->seo_keyword ?? '',
            ];
            // return view($this->templatePath . '.product.index', $this->data);

            // Nếu chỉ có 1 bài viết thì điều hướng tới bài vô bài viết đó luôn
            // if ($product->count() == 1) {
            //     return $this->product($product->first()->slug);
            // }
            return view('frontend.product.category', $this->data);
        } else
            return view('errors.404');
        // return $this->productDetail($slug);
    }

    // Product detail
    public function productDetail($slug)
    {
        $product = Product::where('slug', $slug)->first();

        if (!$product) {
            return abort(404);
        }

        // All category
        $categories = Category::where(['status' => 1, 'parent' => 0])->get();

        // Related products (same category, exclude current)
        $related_products = collect();
        $primaryCategory = Category::whereHas('products', function ($query) use ($product) {
            $query->where('products.id', $product->id);
        })->first();

        if ($primaryCategory) {
            $related_products = $primaryCategory->products()
                ->where('status', 1)
                ->where('products.id', '<>', $product->id)
                ->orderByDesc('sort')
                ->orderByDesc('id')
                ->limit(4)
                ->get([
                    'products.id',
                    'products.name',
                    'products.slug',
                    'products.image',
                    'products.price',
                ]);
        }

        // default data
        $this->data['categories'] = $categories;
        $this->data['product'] = $product;
        $this->data['related_products'] = $related_products;

        // dd($product);

        $this->data['seo'] = [
            'seo_title' => $product->seo_title ?? $product->name,
            'seo_image' => $product->image,
            'seo_description'   => $product->seo_description ?? '',
            'seo_keyword'   => $product->seo_keyword ?? '',
        ];

        return view('frontend.product.single', $this->data);
    }

    public function getBuyNow()
    {
        $data = request()->all();

        $id = $data['product'];
        $product = Product::select('id', 'name', 'unit', 'price', 'stock')->find($id);

        // $list_promotion = \App\Models\ShopProductPromotion::where('shop_product_id', $product->id)
        //     ->orderby('qty_to_promotion')
        //     ->orderby('id')
        //     ->get();

        if (!$product) {
            return response()->json(
                [
                    'error' => 1,
                    'msg' => 'Item is not exist',
                ]
            );
        }

        $promotion = 0;
        $promotion_unit = '$';
        if (!empty($list_promotion)) {
            foreach ($list_promotion as $v) {
                if ($data['qty'] >= $v['qty_to_promotion']) {
                    $promotion = $v['promotion'];
                    $promotion_unit = $v['promotion_unit'];
                }
            }
        }

        // $variables = \App\Variable::where('status', 0)->where('parent', 0)->orderBy('stt', 'asc')->get();
        // $attr = $data['option'] ?? '';

        $price = $product->price;

        $form_attr = ['promotion' => $promotion, 'promotion_unit' => $promotion_unit, 'unit' => $product->unit];

        // Check product allow for sale
        $option = array(
            'id'      => $id,
            'title'   => $product->name,
            'qty'     => $data['qty'],
            'price'   => $price,
            'options' => $form_attr ?? []
        );

        // Cart::add(
        //     array(
        //         'id'      => $product->id,
        //         'name'    => $product->name,
        //         'qty'     => $data['qty'],
        //         'price'   => $price,
        //         'options' => $form_attr ?? []
        //     )
        // );

        session()->forget('option');
        session()->push('option', json_encode($option, JSON_UNESCAPED_SLASHES));

        return response()->json(
            [
                'error' => 0,
                'msg' => 'Success',
            ]
        );
    }

    public function buyNow($id)
    {
        $product = Product::find($id);
        $option = session()->get('option');

        if ($option) {
            $option = json_decode($option[0], true);
            if ($option['id'] != $product->id)
                return redirect()->route('game.detail', $product->slug);
        } else
            return redirect()->route('game.detail', $product->slug);

        if ($product) {
            $this->data['product'] = $product;

            $this->data['seo'] = [
                'seo_title' => 'Mua ngay - ' . $product->name,
            ];
            if (session()->has('cart-info')) {
                $data = session()->get('cart-info');
                $this->data["cart_info"] = $data;
            }

            // dd($this->data);
            // return view($this->templatePath . '.cart.quick-buy', $this->data);
            return view('frontend.cart.quick-buy', $this->data);
        }
    }

    public function quickView()
    {
        $id = request()->id;
        if ($id) {
            $this->localized();

            $product = Product::find($id);

            if ($product) {
                $session_products = session()->get('products.recently_viewed');

                if (!is_array($session_products) ||  array_search($product->id, $session_products) === false)
                    session()->push('products.recently_viewed', $product->id);

                $this->data['product'] = $product;
                // $this->data['related'] = Product::with('getInfo')->whereHas('getInfo', function($query) use($product){
                //     return $query->where('province_id', $product->getInfo->province_id)->where('theme_id', '<>', $product->id);
                // })->limit(10)->get();

                // dd($this->data['product']);
                return response()->json([
                    'error' => 0,
                    'msg'   => 'Success',
                    'view'   => view('frontend.product.product-quick-view', ['data' => $this->data])->render(),
                ]);
                // return view($this->templatePath .'.product.product-single', ['data'=>$this->data])->compileShortcodes();
            }
        }
    }

    public function ajax_categoryDetail($slug)
    {
        $this->localized();
        $category = Category::where('categorySlug', $slug)->first();
        // dd($this->data['category']);
        $lc = $this->data['lc'];
        // dd($category->products);
        $view = view('frontend.partials.product-banner-home', compact('category', 'lc'))->render();
        return response()->json($view);
    }

    // public function wishlist(Request $request)
    // {
    //     $this->localized();

    //     $id = $request->id;
    //     $type = 'save';
    //     if (auth()->check()) {
    //         $data_db = array(
    //             'product_id' => $id,
    //             'user_id' => auth()->user()->id,
    //         );

    //         $db = \App\Models\Wishlist::where('product_id', $id)->where('user_id', auth()->user()->id)->first();
    //         if ($db != '') {
    //             $db->delete();
    //             $type = 'remove';
    //         } else
    //             \App\Models\Wishlist::create($data_db)->save();

    //         $count_wishlist = \App\Models\Wishlist::where('user_id', auth()->user()->id)->count();
    //         $this->data['count_wishlist'] = $count_wishlist;
    //         $this->data['status'] = 'success';
    //     } else {
    //         $wishlist = json_decode(\Cookie::get('wishlist'));
    //         $key = false;


    //         if ($wishlist != '')
    //             $key = array_search($id, $wishlist);
    //         if ($key !== false) {
    //             unset($wishlist[$key]);
    //             $type = 'remove';
    //         } else {
    //             $wishlist[] = $id;
    //         }
    //         $this->data['count_wishlist'] = count($wishlist);
    //         $this->data['status'] = 'success';
    //         $cookie = Cookie::queue('wishlist', json_encode($wishlist), 1000000000);
    //     }
    //     $this->data['type'] = $type;
    //     // $this->data['view'] = view('frontend.product.includes.wishlist-icon', ['type'=>$type, 'id'=>$id])->render();

    //     return response()->json($this->data);
    // }


    /*==================attr select=====================*/

    public function changeAttr()
    {
        $data = request()->all();
        // dd($data);
        // $attr_id = $data['attr_id'] ? explode('_', $data['attr_id'])[1] : '';
        // dd($attr_id);
        $product = Product::find($data['product']);
        if ($product) {
            return response()->json(
                [
                    'error' => 0,
                    'show_price' => $product->showPriceDetail($data['option'])->render(),
                    'view'  => view('frontend.product.includes.product-variations', ['product' => $product, 'attr_id' => $data['attr_id'], 'attr_list_selected' => $data['option']])->render(),
                    'msg'   => 'Success'
                ]
            );
        }
    }

    /*==================end attr select=====================*/
}
