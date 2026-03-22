<?php

namespace App\Http\Controllers\Admin;

use Auth, DB, Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Backend\Menu;
use App\Models\Backend\MenuItems;
use App\Models\Backend\Page;
use App\Models\Backend\User;
use App\Models\Backend\District;
use App\Models\Backend\Ward;
use App\Models\Backend\Category;
use App\Models\Backend\Product;
use App\Models\Backend\ProductCategory;
use App\Models\Backend\Slider;
use App\Models\Backend\EmailTemplate;
use App\Models\Backend\Contact;
use App\Models\Backend\Subscription;
use App\Models\Backend\AddToCard;
use App\Models\Backend\AddToCardDetail;


class AjaxController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function ajax_delete(Request $rq)
    {
        $type = $rq->type;
        $check_data = $rq->seq_list;
        $arr = array();
        $values = "";
        for ($i = 0; $i < count($check_data); $i++) :
            $values .= (int)$check_data[$i] . ",";
            $arr[] = (int)$check_data[$i];
        endfor;
        $groupID = substr($values, 0, -1);
        switch ($type) {

            case 'page':
                //xóa thumbnail
                $url_upload = $_SERVER['DOCUMENT_ROOT'] . '/images/page/';
                foreach ($arr as $it) {
                    $data_page = Page::where('id', '=', $it)->get();
                    foreach ($data_page as $row) {
                        $img = $row->thubnail;
                        if ($img != '') {
                            $pt = $url_upload . $img;
                            if (file_exists($pt)) {
                                unlink($pt);
                            }
                        }
                    }
                }
                $loadDelete = Page::whereIn('id', $arr)->delete();

                // SET AUTO_INCREMENT TO 1
                $table = (new Category)->getTable();
                DB::statement("ALTER TABLE $table AUTO_INCREMENT = 1;");

                return 1;
                break;

            case 'email_template':
                EmailTemplate::whereIn('id', $arr)->delete();
                return 1;
                break;
            case 'menuWp':
                $menuWp = Menu::whereIn('id', $arr)->get();

                if ($menuWp->count() > 0) {
                    foreach ($menuWp as $item) {
                        // DELETE LIST CHILD
                        if ($item->items->count() > 0) {
                            $item_child_id = $item->items->pluck('id');
                            MenuItems::whereIn('id', $item_child_id)->delete();
                        }
                        $item->delete();
                    }
                }

                $table = (new Menu)->getTable();
                $table2 = (new MenuItems)->getTable();
                DB::statement("ALTER TABLE $table AUTO_INCREMENT = 1;");
                DB::statement("ALTER TABLE $table2 AUTO_INCREMENT = 1;");
                return 1;
                break;
            case 'post':
                // Bài viết nằm trong bảng pages (type=post), bảng posts đã xóa
                \App\Models\Backend\Page::where('type', 'post')->whereIn('id', $arr)->delete();
                return 1;
                break;
            case 'post-category':
                Category::whereIn('id', $arr)->delete();

                // Bảng post_categories đã xóa, không còn pivot.

                // SET AUTO_INCREMENT TO 1
                $table = (new Category)->getTable();
                DB::statement("ALTER TABLE $table AUTO_INCREMENT = 1;");
                return 1;
                break;
            case 'product':
                // Xóa pivot trước (product_categories), rồi xóa sản phẩm
                ProductCategory::whereIn('product_id', $arr)->delete();
                Product::whereIn('id', $arr)->delete();

                // SET AUTO_INCREMENT TO 1
                $table = (new Product)->getTable();
                DB::statement("ALTER TABLE $table AUTO_INCREMENT = 1;");
                return 1;
                break;
            case 'product-category':
                Category::whereIn('id', $arr)->delete();

                // DELETE DATA FROM PIVOT TABLE
                ProductCategory::whereIn('category_id', $arr)->delete();

                // SET AUTO_INCREMENT TO 1
                $table = (new Category)->getTable();
                DB::statement("ALTER TABLE $table AUTO_INCREMENT = 1;");
                return 1;
                break;
            case 'user_admin':
                //xóa user admin
                $loadDelete = User::whereIn('id', $arr)->delete();

                //delete products
                // $productDelete = Theme::all();
                // if ($loadDelete) {
                //     foreach ($productDelete as $value) {
                //         foreach ($arr as $value_id) {
                //             if ($value->admin_id == $value_id) {
                //                 $value->delete();
                //                 break;
                //             }
                //         }
                //     } //foreach
                // }
                return 1;
                break;
            case 'order':
                $loadDelete = AddToCard::whereIn('cart_id', $arr)->delete();
                $addToCardDelete = AddToCardDetail::whereIn('cart_id', $arr)->delete();
                return 1;
                break;
            case 'contact':
                Contact::whereIn('id', $arr)->delete();

                // SET AUTO_INCREMENT TO 1
                $table = (new Contact)->getTable();
                DB::statement("ALTER TABLE $table AUTO_INCREMENT = 1;");
                return 1;
                break;
            case 'subscription':
                Subscription::whereIn('id', $arr)->delete();

                // SET AUTO_INCREMENT TO 1
                $table = (new Subscription)->getTable();
                DB::statement("ALTER TABLE $table AUTO_INCREMENT = 1;");
                return 1;
                break;
            case 'slider':
                $slider = Slider::whereIn('id', $arr)->get();

                if ($slider->count() > 0) {
                    foreach ($slider as $item) {

                        // DELETE LIST IMAGE
                        if ($item->children->count() > 0) {
                            $image_id = $item->children->pluck('id');
                            Slider::whereIn('id', $image_id)->delete();
                        }

                        // DELETE SLIDER
                        $item->delete();
                    }
                }

                // SET AUTO_INCREMENT TO 1
                $table = (new Slider)->getTable();
                DB::statement("ALTER TABLE $table AUTO_INCREMENT = 1;");
                return 1;
                break;

            default:
                # code...
                break;
        }
    }

    public function ajax_replicate(Request $rq)
    {
        $type = $rq->type;
        $check_data = $rq->seq_list;
        $arr = array();
        $values = "";

        for ($i = 0; $i < count($check_data); $i++) :
            $values .= (int)$check_data[$i] . ",";
            $arr[] = (int)$check_data[$i];
        endfor;

        // dd($rq, $type, $values);

        // $groupID = substr($values, 0, -1);

        if ($type == 'category-post' || $type == 'category-product') {
            $type = 'category';
        }
        switch ($type) {
            case 'page':
                // Replicate Post + Category
                $i = 1;
                $newPage = '';
                foreach ($arr as $id) {
                    $page = Page::find($id);

                    // Replicate post
                    $newPage = $page->replicate();
                    // $newPost->name = $newPost->name . ' ' . $i;
                    // $newPost->slug = Str::slug($newPost->name);
                    $newPage->created_at = Carbon::now(); // changing the created_at date
                    $newPage->save(); // saving it to the database

                    $slug = Str::slug($newPage->name . '-' . $newPage->id);

                    // update sort = id
                    Page::where("id", $newPage->id)->update(['slug' => $slug, 'sort' => $newPage->id]);

                    // Replicate Post Category
                    $newPage = Page::find($newPage->id);
                    $i++;
                }
                return 1;
                break;
            case 'email_template':
                // Replicate Email Template
                $i = 1;
                $newTemplate = '';
                foreach ($arr as $id) {

                    $template = EmailTemplate::find($id);

                    // Replicate post
                    $newTemplate = $template->replicate();
                    $newTemplate->created_at = Carbon::now(); // changing the created_at date
                    $newTemplate->save(); // saving it to the database

                    // update sort = id
                    EmailTemplate::where("id", $newTemplate->id)->update(['sort' => $newTemplate->id]);
                    $i++;
                }
                return 1;
                break;
            case 'menuwp':
                // Replicate Slider + list image
                $i = 1;
                $newMenuWP = '';
                foreach ($arr as $id) {
                    $menuWP = Menu::find($id);

                    // Get menu list items
                    $list_items = $menuWP->items;

                    // Replicate Slider
                    $newMenuWP = $menuWP->replicate();
                    $newMenuWP->created_at = Carbon::now(); // changing the created_at date
                    $newMenuWP->save(); // saving it to the database

                    // update sort = id
                    // Slider::where("id", $newSlider->id)->update(['sort' => $newSlider->id]);

                    // Replicate Slider list image
                    if ($list_items->count() > 0) {
                        foreach ($list_items as $item) {
                            $menuItem = MenuItems::find($item->id);
                            $newMenuItem = $menuItem->replicate();
                            $newMenuItem->menu_id = $newMenuWP->id; // changing the slider_id
                            $newMenuItem->created_at = Carbon::now(); // changing the created_at date
                            $newMenuItem->save(); // saving it to the database
                        }
                    }
                    $i++;
                }
                return 1;
                break;
            case 'category':
                // Replicate Category Product
                $i = 1;
                $newCaterory = '';
                foreach ($arr as $id) {
                    $category = Category::find($id);

                    // Get categories of current post 
                    // $category_id = $post->categories->pluck('id')->toArray();

                    // Replicate category
                    $newCaterory = $category->replicate();
                    $newCaterory->name = $newCaterory->name . ' ' . $i;
                    $newCaterory->slug = Str::slug($newCaterory->name);
                    $newCaterory->created_at = Carbon::now(); // changing the created_at date
                    $newCaterory->save(); // saving it to the database

                    // update sort = id
                    Category::where("id", $newCaterory->id)->update(['sort' => $newCaterory->id]);

                    // Replicate Post Category
                    // $newPost = Post::find($newCaterory->id);
                    // $newPost->categories()->sync($category_id);
                    $i++;
                }
                return 1;
                break;
            case 'post':
                // Replicate bài viết (Page type=post), bảng posts đã xóa
                $i = 1;
                foreach ($arr as $id) {
                    $page = \App\Models\Backend\Page::where('type', 'post')->find($id);
                    if (!$page) continue;
                    $newPage = $page->replicate();
                    $newPage->created_at = Carbon::now();
                    $newPage->save();
                    $slug = Str::slug(($newPage->name ?? 'post') . '-' . $newPage->id);
                    \App\Models\Backend\Page::where('id', $newPage->id)->update(['slug' => $slug, 'sort' => $newPage->id]);
                    $i++;
                }
                return 1;
                break;
            case 'product':
                // Replicate Product + Category
                $i = 1;
                $newProduct = '';
                foreach ($arr as $id) {
                    $product = Product::find($id);

                    // Get categories of current product 
                    $category_id = $product->categories->pluck('id')->toArray();

                    // Replicate post
                    $newProduct = $product->replicate();
                    // $newProduct->name = $newProduct->name . ' ' . $i;
                    // $newProduct->slug = Str::slug($newProduct->name);
                    $newProduct->created_at = Carbon::now(); // changing the created_at date
                    $newProduct->save(); // saving it to the database

                    $slug = Str::slug($newProduct->name . '-' . $newProduct->id);

                    // update sort = id
                    Product::where("id", $newProduct->id)->update(['slug' => $slug, 'sort' => $newProduct->id]);

                    // Replicate Post Category
                    $newProduct = Product::find($newProduct->id);
                    $newProduct->categories()->sync($category_id);
                    $i++;
                }
                return 1;
                break;
            case 'slider':
                // Replicate Slider + list image
                $i = 1;
                $newSlider = '';
                foreach ($arr as $id) {
                    $slider = Slider::find($id);

                    // Get slider list image
                    $list_image = Slider::where('status', 0)
                        ->where('slider_id', $slider->id)
                        ->orderBy('sort', 'asc')
                        ->get();

                    // Replicate Slider
                    $newSlider = $slider->replicate();
                    $newSlider->created_at = Carbon::now(); // changing the created_at date
                    $newSlider->save(); // saving it to the database

                    // update sort = id
                    Slider::where("id", $newSlider->id)->update(['sort' => $newSlider->id]);

                    // Replicate Slider list image
                    if ($list_image->count() > 0) {
                        foreach ($list_image as $item) {
                            $list_image = Slider::find($item->id);
                            $newImage = $list_image->replicate();
                            $newImage->slider_id = $newSlider->id; // changing the slider_id
                            $newImage->created_at = Carbon::now(); // changing the created_at date
                            $newImage->save(); // saving it to the database
                        }
                    }
                    $i++;
                }
                return 1;
                break;
            default:
                # code...
                break;
        }
    }

    // Quick change value of data list
    public function ajax_quickchange(Request $rq)
    {
        $id = $rq->id;
        $column = $rq->column;
        $value = $rq->value;

        // Call model
        (new $rq->model)::where('id', $id)->update([$column => $value]);
    }

    public function processThemeFast(Request $request)
    {
        $id = (int)$request->id;
        $origin_price = $request->origin_price;
        $promotion_price = $request->promotion_price;
        // $order_short = $request->order_short;
        $start_event = $request->start_event;
        $end_event = $request->end_event;
        if ($id > 0) :
            $data = array(
                'price_origin' => $origin_price,
                'price_promotion' => $promotion_price,
                // 'order_short' => $order_short,
                'start_event' => $start_event,
                'end_event' => $end_event,
                'updated' => date('Y-m-d H:i:s')
            );
            // $respons = Theme::where("id", "=", $id)->update($data);
            echo 'OK';
        else :
            echo 'Lỗi';
        endif;
        exit();
    }

    public function update_new_item_status(Request $request)
    {
        if (isset($request['check']) && $request['sid'] != "") :
            $status = $request['check'];
            $postID = (int)$request['sid'];
            if ($postID > 0) :
                // $respons1 = Theme::where("id", "=", $postID)->update(array('item_new' => $status));
                echo "OK";
            else :
                echo "Lỗi";
            endif;
        endif;
    }

    public function update_process_flash_sale(Request $request)
    {
        if (isset($request['check']) && $request['sid'] != "") :
            $status = $request['check'];
            $postID = (int)$request['sid'];
            if ($postID > 0) :
                // $respons1 = Theme::where("id", "=", $postID)->update(array('flash_sale' => $status));
                echo "OK";
            else :
                echo "Lỗi";
            endif;
        endif;
    }

    public function update_process_sale_top_week(Request $request)
    {
        if (isset($request['check']) && $request['sid'] != "") :
            $status = $request['check'];
            $postID = (int)$request['sid'];
            if ($postID > 0) :
                // $respons1 = Theme::where("id", "=", $postID)->update(array('sale_top_week' => $status));
                echo "OK";
            else :
                echo "Lỗi";
            endif;
        endif;
    }

    public function update_process_propose(Request $request)
    {
        if (isset($request['check']) && $request['sid'] != "") :
            $status = $request['check'];
            $postID = (int)$request['sid'];
            if ($postID > 0) :
                // $respons1 = Theme::where("id", "=", $postID)->update(array('propose' => $status));
                echo "OK";
            else :
                echo "Lỗi";
            endif;
        endif;
    }

    public function updateStoreStatus(Request $request)
    {
        if (isset($request['check']) && $request['sid'] != "") :
            $status = $request['check'];
            $postID = (int)$request['sid'];
            if ($postID > 0) :
                // $respons1 = Theme::where("id", "=", $postID)->update(array('store_status' => $status));
                echo "OK";
            else :
                echo "Lỗi";
            endif;
        endif;
    }

    public function checkPassword(Request $request)
    {
        $current_password = $request->current_password;
        if (!Hash::check($request->current_password, Auth::guard('admin')->user()->password)) {
            echo 'Mật khẩu hiện tại không chính xác';
        } else {
            //echo 'Mật khẩu chính xác';
        }
    }

    public function getPlace(Request $request)
    {
        $data = [
            'label' => 'Chọn Quận / Huyện',
            'options' => '',
            'name' => '',
            'class' => 'place_select',
            'type' => '',
            'child' => '',
            'item' => '',
            'hasDefaultOption' => true,
        ];

        if ($request->type == 'province') {
            $options = District::where('province_id', $request->id)->get();

            $data['label'] = 'Chọn Quận / Huyện';
            $data['options'] = $options;
            $data['name'] = 'district_id';
            $data['type'] = 'district';
            $data['child'] = 'ward';
        } elseif ($request->type == 'district') {
            $options = Ward::where('district_id', $request->id)->get();
            $data['label'] = 'Chọn Phường / Xã';
            $data['options'] = $options;
            $data['name'] = 'ward_id';
            $data['type'] = 'ward';
            $data['child'] = 'street';
        }
        return view('backend.partials.select-label', $data);
    }
}
