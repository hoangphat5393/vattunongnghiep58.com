Xin chào {{ $name }},
<h4>THÔNG TIN ĐƠN HÀNG</h4>
<p>________________________________<i style="color: #F00;">♥♥♥</i>__________________________________</p>
<p>Họ tên: {{ $name }}</p>
<p>Số điện thoại: {{ $phone }}</p>
<p>E-mail: {{ $email }}</p>
<p>Tỉnh/Thành phố: {{ $cart_province }}</p>
<p>Quận/Huyện: {{ $cart_district }}</p>
<p>Phường/Xã: {{ $cart_ward }}</p>
<p>Địa chỉ: {{ $address }}</p>
<p>Mã đơn hàng: <b>{{ $cart_code }}</b></p>
<p>Hình thức thanh toán: <b><i style="color: #F00;"><?php
switch ($cart_pay_method):
    case 'cod':
        echo 'Thanh toán khi nhận hàng';
        break;
    case 'bank':
        echo 'Chuyển khoản qua ngân hàng';
        break;
    default:
        echo 'Không xác định';
endswitch;
?></i></b></p>
<p>Ghi chú: {{ $note }}</p>
@if ($discount > 0)
    <p>Mã giảm giá: <b style="color: #F00;">{{ $code_discount }} (-{{ $discount }}<i>{!! setting_option('currency') !!}</i>)</b></p>
@endif
<?php
$total = str_replace(',', '', $total);
$total = $total + $shipping_fee;
?>
<p>Phí giao hàng: <b style="color: #F00;">+{{ $shipping_fee }}</b> {!! setting_option('currency') !!}</p>
<p>Tổng tiền: <b style="color: #F00;">{!! WebService::formatMoney12($total) !!}</b> {!! setting_option('currency') !!}</p>
<p>________________________________<i style="color: #F00;">♥♥♥</i>__________________________________</p>
<h4>CHI TIẾT ĐƠN HÀNG</h4>
<table width="100%" border="0" style="border: 1px solid #eae8e8;empty-cells: 0px;border-spacing: 0px;border-spacing: 0px;border-collapse: collapse;">
    <tr>
        <th style="background: #adadad; text-align: center; padding:10px 15px; text-transform: uppercase; border: 1px solid #e5e5e5;">Tên SP</td>
        <th style="background: #adadad; text-align: center; padding:10px 15px; text-transform: uppercase; border: 1px solid #e5e5e5;">Giá</td>
        <th style="background: #adadad; text-align: center; padding:10px 15px; text-transform: uppercase; border: 1px solid #e5e5e5;">Số lượng</td>
        <th style="background: #adadad; text-align: center; padding:10px 15px; text-transform: uppercase; border: 1px solid #e5e5e5;">Thành tiền</td>
    </tr>
    @if (Cart::content()->count() > 0)
        @foreach (Cart::content() as $content_cart)
            <tr>
                <td align="center" valign="middle" style="border: 1px solid #eaeaea;">
                    <p><a href="{!! permalink_by_id($content_cart->id) !!}" target="_blank"> {{ $content_cart->name }}</a></p>
                    <ul style="list-style:none;">
                        <?php
                        $avariable_html = '';
                        $group_combo = '';
                        if ($content_cart->options):
                            $avariable = $content_cart->options;
                            if (isset($avariable[0])) {
                                $count_option_arr = count($avariable);
                                if ($count_option_arr == 1) {
                                    $avariable_html = '';
                                } else {
                                    if ($avariable[0] != '') {
                                        $group_combo = unserialize($avariable[0]);
                                        $count_group_combo_item = count($group_combo);
                                    }
                                    if (isset($group_combo) && $group_combo != '') {
                                        for ($i = 0; $i < $count_group_combo_item; $i++) {
                                            $name_color = '';
                                            $avariable_html .= '<p class="item_combo">';
                                            $item_title = App\Models\Theme::where('theme.id', '=', $group_combo[$i])
                                                ->select('theme.title')
                                                ->first();
                                            $check_variable_item = App\Models\Theme_Join_Variable_Theme::where('theme_join_variable_theme.id_theme', '=', $group_combo[$i])->first();
                                            $avariable_html .= "<span class='item_combo_title'>" . $item_title->title . '</span> ';
                                            if ($check_variable_item) {
                                                for ($j = 2; $j < $count_option_arr; $j++) {
                                                    $array = json_decode(json_encode($avariable[$j]), true);
                                                    if (isset($array[$group_combo[$i]])) {
                                                        $arr_temp = $array[$group_combo[$i]];
                                                        foreach ($array[$group_combo[$i]] as $it) {
                                                            foreach ($it as $var) {
                                                                $name_color .= ' ' . $var;
                                                            }
                                                            break;
                                                        }
                                                    }
                                                }
                                                $avariable_html .= "- <span class='avariable_html'><span>" . $name_color . '</span></span>';
                                                $avariable_html .= '</p>';
                                            }
                                        }
                                    } else {
                                        $name_color = '';
                                        for ($j = 1; $j < $count_option_arr; $j++) {
                                            foreach ($avariable[$j] as $row) {
                                                $arr_temp = WebService::objectToArray($row[0]);
                                                foreach ($arr_temp as $it) {
                                                    $name_color .= ' ' . $it;
                                                }
                                            }
                                        }
                                        $avariable_html .= "- <p class='avariable_html'><span>" . $name_color . '</span></p>';
                                    }
                                }
                            } else {
                                $avariable_html = '';
                            }
                        endif;
                        echo $avariable_html;
                        ?>
                    </ul>
                </td>
                <td align="center" valign="middle" style="border: 1px solid #eaeaea;"><b style="color: #F00;">{!! WebService::formatMoney12($content_cart->price) !!}</b> {!! setting_option('currency') !!}</td>
                <td align="center" valign="middle" style="border: 1px solid #eaeaea;"><b><i style="color: #000;">{{ $content_cart->qty }}<i></b></td>
                <td align="center" valign="middle" style="border: 1px solid #eaeaea;"><b style="color: #F00;">{!! WebService::formatMoney12($content_cart->subtotal) !!}</b> <i>{!! setting_option('currency') !!}</i></td>
            </tr>
        @endforeach
    @endif
    <tr>
        <td colspan="4" align="center" valign="middle" style="border: 1px solid #eaeaea; padding:5px 10px;">
            @if ($discount > 0)
                <p><b>Giảm giá:</b> <b style="color: #F00;"> -{!! WebService::formatMoney12($discount) !!} </b> {!! setting_option('currency') !!}</p>
            @endif
            <p><b>Phí giao hàng:</b> <b style="color: #F00;">+{{ $shipping_fee }} {!! setting_option('currency') !!}</b></p>
            <p><b>Tổng tiền:</b> <b style="color: #F00;">{!! WebService::formatMoney12($total) !!} {!! setting_option('currency') !!}</b></p>
        </td>
    </tr>
</table>
<p>--</p>
<p>Thanks and Best Regards,</p>
<h4>Siêu thị Ánh Dương</h4>
