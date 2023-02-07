<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

//Instantiation and passing `true` enables exception
$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';

//Server settings
$mail->SMTPDebug = 2;
$mail->IsSMTP(); // set mailer to use SMTP
$mail->Host = "smtp.yandex.com"; // specify main and backup server
$mail->Port = 587; // set the port to use
$mail->SMTPAuth = true; // turn on SMTP authentication
$mail->SMTPSecure = 'tls'; 
$mail->Username = "noreply@vattunongnghiep58.com"; // your SMTP username or your gmail username
$mail->Password = "hjsk89hjs72"; // your SMTP password or your gmail password


//Recipients
$from = "noreply@vattunongnghiep58.com"; // Reply to this email
$to="hoangphat5393@gmail.com"; // Recipients email ID
$name="Ky Thuat GetAtZ"; // Recipient's name
$replyto="support@vattunongnghiep58.com"; // Reply email ID
$replyname="Cửa Hàng Vật Tư Nông Nghiệp 58"; // Reply's name
$ccto="support@vattunongnghiep58.com"; // CC email ID
$ccname="Cửa Hàng Vật Tư Nông Nghiệp 58"; // CC's name
$mail->From = $from;
$mail->FromName = 'Vật Tư Nông Nghiệp 58'; // Name to indicate where the email came from when the recepient received
$mail->AddAddress($to,$name);
$mail->AddCC($ccto,$ccname);
$mail->AddReplyTo($replyto,$replyname);
$mail->WordWrap = 50; // set word wrap
$mail->IsHTML(true); // send as HTML
$mail->Subject = "Đặt hàng tại website vattunongnghiep58.com";
$mail->Body = "<b>Mail nay duoc gửi bằng SMTP Gmail dùng phpmailer class. - <a href='http://vattunongnghiep58.com/'>vattunongnghiep58.com</a></b>"; //HTML Body
$mail->AltBody = "Mail nay duoc gửi từ getatz.com"; //Text Body


if(!$mail->Send()){
    echo "<h1>Loi khi goi mail: " . $mail->ErrorInfo . '</h1>';
}else{
    echo "<h1>Send mail thanh cong</h1>";
}
?>
