<?php require_once('../lib/atz.php');?>
<?php 
class login_controller extends atz{

	public function __construct() { 
		// parent::__construct();

		$this->post = array(
			'User_Name'=>'',
			'User_Gender'=>'',
			'User_Birthday'=>'',
			'User_Password' =>'',
			'User_Role' =>'',
			'User_Email'=>'',
			'User_Phone'=>''
		);

		if(isset($_SESSION['user'])){
			header("location:index.php");
		}
	}

	public function login(){

		$post = $this->post;

		foreach ($_REQUEST as $k => $v) {
			if(isset($post[$k])){
				$post[$k] =$v;
			}
		}

		if(!empty($_REQUEST) && isset($_REQUEST['submit'])){
			
			if(!$post['User_Email']){
				$error['User_Email'] = 'Chưa nhập Email';
			}
			if(!$post['User_Password']){
				$error['User_Password'] = 'Chưa nhập mật khẩu';
			}
			
			if(empty($error)){

				$user = $this->select('user', array('User_Email' => $post['User_Email'], 'User_Password'=> md5($post['User_Password'])));

				if(!empty($user)){
					$_SESSION['user'] = $user[0];
					header("location:index.php");
				}else{
					$error['main'] = 'Tên đăng nhập hoặc mật khẩu sai';
					$rs = array('error' => $error);
					return $rs;
				}

			}else{
				$rs = array('error' => $error);
				return $rs;	
			}
		}
	}
	
}
?>