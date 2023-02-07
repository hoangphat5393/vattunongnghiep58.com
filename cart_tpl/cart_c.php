<?php require_once('lib/atz.php');?>
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class cart_controller extends atz{

	public function __construct() { 
		
		parent::__construct();
		
		$this->post = array(
			'Invoice_Name'=>'',
			'Invoice_Email'=>'',
			'Invoice_Mobile'=>'',
			'Invoice_Address' =>'',
			'Invoice_Price' =>'',
			'Invoice_PriceUnit'=>'VNĐ',
			'Invoice_Note'=>'',
			'Invoice_Paid'=> 0, // -1: Hủy, 0: Chưa thanh toán, 1: Đã thanh toán
			'Invoice_Created'=> time(),
		);
	}

	public function update_cart(){

		// Thêm sản phẩm vào giỏ hàng
		if(isset($_POST['id']) && !empty($_POST['id'])){

			$products = $this->select('product',array('Product_ID' => $_POST['id']),'','Product_ID, Product_Name_vi, Product_Thumbnail, Product_Price');
			
			if(!empty($products)){
				$products = $products[0];
				$_SESSION['cart'][$products['Product_ID']] = $products;
			}
		}

		// Xóa sản phẩm trong giỏ hàng
		if(isset($_POST['delete']) && !empty($_POST['delete'])){
			unset($_SESSION['cart'][$_POST['delete']]);
			echo 1;exit;
		}
	}

	public function checkout_cart(){
		
		if(isset($_POST['submit']) && !empty($_POST)){
	
			foreach ($_POST as $k => $v) {
				if(isset($this->post[$k])){
					$this->post[$k] =$v;
				}
			}

			$post = $this->post;

			$quanlity = '';
			if(isset($_POST['quanlity'])){
				$quanlity = $_POST['quanlity'];
			}
			
			$price = '';
			if(isset($_POST['price'])){
				$price = $_POST['price'];
			}

			$total_price = 0;
			
			$InvoiceProduct = array();
			if(!empty($_POST['product_id'])){

				foreach ($_POST['product_id'] as $v) {
					$rs = $this->select('product',array('Product_ID' => $v),'','Product_ID, Product_Name_vi, Product_Thumbnail, Product_Price');
					if(!empty($rs)){
						$rs = $rs[0];

						//$product[$rs['Product_ID']] = $rs;
						
						$InvoiceProduct[$rs['Product_ID']]['InvoiceProduct_Product'] = $rs['Product_ID'];
						$InvoiceProduct[$rs['Product_ID']]['InvoiceProduct_Price'] = $price[$rs['Product_ID']];
						$InvoiceProduct[$rs['Product_ID']]['InvoiceProduct_Quanlity'] = $quanlity[$rs['Product_ID']];
							
						$total_price += $price[$rs['Product_ID']];
					}
				}
			}
			

			$post['Invoice_Price'] = $total_price;

			$errors = array();


			if(empty($_SESSION['cart'])){
				$errors['main'] = 'Không có sản phẩm nào trong giỏ hàng';
			}
			if(!$post['Invoice_Name']){
				$errors['Invoice_Name'] = 'Chưa nhập họ tên';
			}
			if($post['Invoice_Mobile']==''){
				$errors['Invoice_Mobile'] = 'Chưa nhập số điện thoại';
			}elseif(!is_numeric($post['Invoice_Mobile'])){
				$errors['Invoice_Mobile'] = 'Chỉ được nhập số';
			}elseif (strlen($post['Invoice_Mobile'])<10) {
				$errors['Invoice_Mobile'] = 'Điện thoại phải ít nhất có 10 số!';
			}
			if($this->post['Invoice_Email'] && !filter_var($post['Invoice_Email'], FILTER_VALIDATE_EMAIL)){
				$errors['Invoice_Email'] = 'Email không hợp lệ';
			}
			if(!$post['Invoice_Address']){
				$errors['Invoice_Address'] = 'Chưa nhập địa chỉ';
			}
						
			
			// dirname(str_replace(getcwd().'/', '', __FILE__)).'/email.tpl'));
			// Nội dung email
			// ob_start();
			// eval('>'.file_get_contents($this->site_url['main'].'email.php'));
			// $body = ob_get_clean();		
			
			if(empty($errors)){
				
				// Thêm đơn hàng
				
				$last_id = $this->insert('invoice', $post);

				if(!empty($last_id)){
						
					// Thêm chi tiết đơn hàng
					foreach ($InvoiceProduct as $v) {
						$v['InvoiceProduct_Invoice'] = $last_id;
						$rs = $this->insert('invoiceproduct', $v);
					}

					// SEND MAIL
					ob_start();

					$cart = $_SESSION['cart'];
						
					// local host
					include_once $this->site_url['root'].'email.php';

					$body = ob_get_contents();
						
					ob_end_clean();

					$mail = $this->send_mail($body,$post['Invoice_Email'],$post['Invoice_Name']);
					// END SEND MAIL

					$success['main'] = 'Đặt hàng thành công! Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.\nThông tin đơn hàng đã được gửi vào mail của bạn (Nếu không thấy mail xin hay kiểm tra thư mục spam)';
					$rs = array('success' => $success);

					unset($_POST);
					unset($_SESSION['cart']);

					$this->post = array();
				}else{
					$errors['main'] = 'Có lỗi xảy ra. Vui lòng thử lại sau!';
					$rs = array('error' => $errors);
				}
				return $rs;

			}else{
				$rs = array('errors' => $errors);
				return $rs;	
			}
		}
		
	}

	public function send_mail($body,$mail_to='',$mail_name=''){
		
		// echo $body;exit;

		//Instantiation and passing `true` enables exception
		$mail = new PHPMailer(true);
		$mail->CharSet = 'UTF-8';

		//Server settings
		// $mail->SMTPDebug = 2;
		$mail->IsSMTP(); // set mailer to use SMTP
		$mail->Host = "smtp.yandex.com"; // specify main and backup server
		$mail->Port = 587; // set the port to use
		$mail->SMTPAuth = true; // turn on SMTP authentication
		$mail->SMTPSecure = 'tls'; 
		$mail->Username = "noreply@vattunongnghiep58.com"; // your SMTP username or your gmail username
		$mail->Password = "hjsk89hjs72"; // your SMTP password or your gmail password
			
		//Recipients
		$from = "noreply@vattunongnghiep58.com"; // Reply to this email
		$to = $mail_to; // Recipients email ID
		$name = $mail_name; // Recipient's name

		$replyto = SETTING['Setting_Email']; // Reply email ID
		$replyname = SETTING['Setting_Title']; // Reply's name
		
		// $ccto = SETTING['Setting_Email']; // CC email ID
		// $ccname = SETTING['Setting_Title']; // CC's name
		
		$mail->From = $from;
		$mail->FromName = SETTING['Setting_Title']; // Name to indicate where the email came from when the recepient received

		if($to && $name){
			$mail->AddAddress($to,$name);	
		}
		$mail->AddAddress(SETTING['Setting_Email'],SETTING['Setting_Title']);

		// $mail->AddCC($ccto,$ccname);
		$mail->AddReplyTo($replyto,$replyname);
		
		$mail->WordWrap = 50; // set word wrap
		$mail->IsHTML(true); // send as HTML
		$mail->Subject = "Đặt hàng tại website ".$this->site_url['main'];
		$mail->Body = $body;
		$mail->AltBody = "Mail nay duoc gửi từ ".$this->site_url['main']; //Text Body

		if(!$mail->Send()){
		    // return "<h1>Loi khi goi mail: " . $mail->ErrorInfo . '</h1>';
		    return 0;
		}else{
		    // return "<h1>Send mail thanh cong</h1>";
		    return 1;
		}
	}
	
}
?>