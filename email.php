<div style="width:640px;margin:0 auto">
    <div style="border-bottom:10px solid #018c39;padding:10px 0px">
        <img src="<?=$this->site_url['main']?>assets/images/header/logo.png" alt="<?=SETTING['Setting_Company']?>">
    </div>
    <div>
        <div style="background:#eee;padding:10px">
            Chào anh <b><?=$post['Invoice_Name']?></b>,<br>
            Cảm ơn anh đã đặt hàng tại <a href="<?=$this->site_url['main']?>" target="_blank"><?=$this->site_url['main']?></a>.
            <br>
            Thông tin đơn hàng:
        </div>
        <div style="padding:10px">
            <span style="float:left">Mã đơn hàng: <b><?=$last_id?></b></span>
            <span style="float:right">Đặt lúc: <?=date('d-m-Y',$post['Invoice_Created'])?></span>
            <div style="clear:both"></div>
        </div>
    </div>
    <div>
        <table width="100%" style="border:1px solid #ccc;border-bottom:none;border-collapse:collapse">
        	<thead>
        		<tr>
                    <th style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px 10px;background:#ddd;font-size:13px">Sản phẩm</th>
                    <th style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px 10px;background:#ddd;font-size:13px">Giá x Số lượng</th>
                    <th style="border-bottom:1px solid #ccc;padding:5px 10px;background:#ddd;font-size:13px">Thành tiền</th>
                </tr>
        	</thead>
            <tbody>
                <?php foreach ($cart as $k => $v): ?>
	                <tr>
	                    <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px 10px;text-align:center"><?=$v['Product_Name_vi']?></td>
	                    <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px 10px;text-align:center"><?=$v['Product_Price']?>&nbsp;VNĐ x&nbsp;<?=$InvoiceProduct[$v['Product_ID']]['InvoiceProduct_Quanlity']?></td>
	                    <td style="border-bottom:1px solid #ccc;padding:5px 10px;text-align:center"><?=number_format($InvoiceProduct[$v['Product_ID']]['InvoiceProduct_Price'],0,',','.')?>&nbsp;VNĐ</td>
	                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <div style="text-align:right;width:300px;float:right;margin:20px 0px">
            <p><span style="float:left">Số tiền cần thanh toán:</span> <strong style="color:#f00"><?=number_format($total_price,0,',','.')?>đ</strong></p>
        </div>
    </div>
    <div style="clear:both">
        <h2 style="font-size:16px;margin:0px;font-weight:bold;margin-bottom:10px">Thông tin nhận hàng</h2>
        <div style="background:#eee;padding:10px 5px"><span style="width:200px;display:inline-block">Tên người nhận:</span> test</div>
        <div style="padding:0px 5px;margin-top:10px"><span style="width:200px;display:inline-block">Điện thoại:</span> <a href="tel:<?=$post['Invoice_Mobile']?>"><?=$post['Invoice_Mobile']?></a></div>
        <div style="padding:0px 5px;margin-top:10px"><span style="width:200px;display:inline-block">Email:</span> <a href="mailto:<?=$post['Invoice_Email']?>" target="_blank"><?=$post['Invoice_Email']?></a></div>
        <div style="padding:0px 5px;margin-top:10px"><span style="width:200px;display:inline-block">Địa chỉ:</span> <?=$post['Invoice_Address']?></div>
        <div style="padding:0px 5px;margin-top:10px"><span style="width:200px;display:inline-block">Ghi chú:</span> <?=$post['Invoice_Note']?></div>
        <div style="border-top:10px solid #eee;margin:10px 0px"></div>
        <p><b>Lưu Ý </b> : Đây là thư hỗ trợ tự động , mọi phản hồi xin gửi về <a href="mailto:<?=SETTING['Setting_Email']?>"><?=SETTING['Setting_Email']?></a>.</p>
    </div>
    <div style="background:#018c39;padding:10px 10px;color:#fff">
        <div>
            <p>
            	<span style="font-size:14px">
                    <font face="verdana, geneva, sans-serif"><i>Thời gian hoạt động</i></font>
                </span>
            </p>
            <p><font face="verdana, geneva, sans-serif"><i>Thứ 2 - Chủ Nhật: 8h - 18h30 (Kể cả các ngày Lễ - Tết)</i></font></p>

            <p><font face="verdana, geneva, sans-serif"><i>Quý khách hàng có thể để lại thông tin tại khung bên cạnh để nhận thông tin khuyến mãi sớm nhất. Hoặc liên hệ theo số hotline bên dưới 24/7.</i></font></p>

            <p class="hotline">
            	<em>
            		<span style="font-family:verdana,geneva,sans-serif"><u>Hotline ( Sỉ và Lẻ ):</u>
            	</em>
            	<strong>
            		<a style="text-decoration: none;color:rgb(255,0,0)" href="tel:<?=SETTING['Setting_Phone']?>"><?=SETTING['Setting_Phone']?></a>
            	</strong>- Mr.Đạt	
            </p>

            <p><em>Email:</em> <a style="color:#fff;font-weight:bold" href="mailto:<?=SETTING['Setting_Email']?>"><?=SETTING['Setting_Email']?></a></p>
            <p><em>Facebook:</em> <a style="color:#fff;font-weight:bold" href="https://www.facebook.com/vattunongnghiepso58" target="_blank">https://www.facebook.com/vattunongnghiepso58</a></p>
            <p><em>Website:</em> <a style="color:#fff;font-weight:bold" href="<?=$this->site_url['main']?>" target="_blank"><?=$this->site_url['main']?></a></p>
        </div>
    </div>
</div>