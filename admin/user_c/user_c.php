<?php ob_start();require_once('../lib/atz.php');?>
<?php 
class user_controller extends atz{

	public function __construct() { 
		parent::__construct();

		$this->check_login();

		// $this->user_type = array('Landing Page', 'Bán hàng', 'Bất động sản', 'Thương mại điện tử');
		$this->user_gender = array('male'=>'Nam', 'female'=>'Nữ', 'other'=>'Khác');

		$this->user_permission = array(1=>'Super Admin',2=>'Admin',3=>'Client');

		$this->post = array(
			'User_NickName'=>'',
			'User_FirstName'=>'',
			'User_LastName'=>'',
			'User_Gender'=>'',
			'User_Birthday'=>date('Y-m-d'),
			'User_Email'=>'',
			'User_Mobile'=>'',
			'User_Avatar'=>'',
			'User_Country'=>1,
			'User_Address'=>0,
			'User_Zipcode'=>1,
			// 'User_Password'=>'',
			'User_Registered'=>date('Y-m-d'),
			'User_RootAdmin'=>3
		);
	}
	
	public $avatar_width = 100;
	public $avatar_height = 100;

	public function get_user(){
		// $users = $this->select('user',array(''),array('User_ID'=>'ASC','User_NickName'=>'ASC'));

		$query = 'SELECT  *
				FROM    `user` 
				WHERE   `User_RootAdmin` >= '.$_SESSION['user']['User_RootAdmin'].'
				ORDER BY `User_ID` ASC, `User_NickName` ASC';
		$users = $this->raw_query($query);
		return $users;
	}

	public function get_current_data($id){

		$current_data = $this->select('user',array('User_ID'=>$id));
					
		if(!empty($current_data)){
			return $current_data[0];	
		}
		header('location:'.$this->site_url['admin'].'user_list.php');
	}


	public function add_user(){

		$post = $this->post;

		$errors = array();

		// Sửa - Lấy dữ liệu cũ 
		if(isset($_GET['edit'])){
			$current_data = $this->get_current_data($_GET['edit']);
			if($current_data){
				foreach ($current_data as $k => $v) {
					if(isset($current_data[$k])){
						$current_data[$k] =$v;
					}
				}
				$this->post = $current_data;
			}
		}
			
			
		// Thêm, Cập nhật dũ liệu
		if(!empty($_REQUEST) && isset($_REQUEST['submit'])){

			foreach ($_REQUEST as $k => $v) {
				if(isset($post[$k])){
					$post[$k] =$v;
				}
			}

			$this->post = $post;
				
			if(!$post['User_NickName']){
				$errors['User_NickName'] = 'Chưa nhập nickname';
			}
			if(!$post['User_FirstName']){
				$errors['User_FirstName'] = 'Chưa nhập tên';
			}
			if(!$post['User_LastName']){
				$errors['User_LastName'] = 'Chưa nhập họ';
			}
			if(!$post['User_Avatar'] && !$_FILES['User_Avatar']['tmp_name']){
				$errors['User_Avatar'] = 'Chưa chọn ảnh đại diện';	
			}else{
				if($_FILES['User_Avatar']['name']){

					// Kiểm tra file
					$check_file = $this->image->check_file($_FILES['User_Avatar']['name']);

					if ($check_file!=1) {
					    $errors['User_Avatar'] = $check_file;
					}	
				}	
			}
			if(!$post['User_Gender']){
				$errors['User_Gender'] = 'Chưa chọn giới tính';
			}
			// if(!$post['User_Birthday']){
			// 	$errors['User_Birthday'] = 'Chưa nhập sinh nhật';
			// }
			if($post['User_Mobile']==''){
				$errors['User_Mobile'] = 'Chưa nhập số điện thoại';
			}elseif(!is_numeric($post['User_Mobile'])){
				$errors['User_Mobile'] = 'Chỉ được nhập số';
			}
			if($post['User_Email']!='' && !filter_var($post['User_Email'],FILTER_VALIDATE_EMAIL)){
				$errors['User_Email'] = 'Email không đúng';
			}
			if(!$post['User_Country']){
				$errors['User_Country'] = 'Chưa chọn quốc tịch';
			}
			

			// Thứ mục ảnh
			$dir = '../upload/user/';

			if(!is_dir($dir)){
				mkdir($dir);
	        }
			
			// echo '<pre>';
			// print_r($errors);
			// exit;
			// Tiến hành insert, update
			if(empty($errors)){

				$dir_thumb = $dir.'avatar/'; 
				if(!is_dir($dir_thumb)){
					mkdir($dir_thumb);
		        }

				// Upload ảnh đại diện (thumbnail)
				if(isset($_FILES['User_Avatar']) && $_FILES['User_Avatar']['tmp_name']){
					$file = $_FILES['User_Avatar'];

					$ext = $this->image->file_type($file['name']);
		        } 
				
				if (!isset($_REQUEST['edit'])) {

					// Tiến hành upload ảnh đại diện
					$thumb_name = time().'_'.rand(100000, 999999).'.'.$ext;

					if($this->image->upload($file['tmp_name'],$dir_thumb,$thumb_name,$this->avatar_width,$this->avatar_height)==1){
						$post['User_Avatar'] = $dir_thumb.$thumb_name;
					}
					
					// Insert
					$post['User_Registered'] = time();
					
					$rs = $this->insert('user', $post);

				}else{

					// Xóa ảnh (thumbnail) trong trường hợp sửa
					$old_thumb = $current_data['User_Avatar'];
					
					if(isset($_FILES['User_Avatar']) && !empty($_FILES['User_Avatar']['tmp_name'])){
						
						$thumb_name = time().'_'.rand(100000, 999999).'.'.$ext;
							
						// Tiến hành upload ảnh đại diện
						if($this->image->upload($file['tmp_name'],$dir_thumb,$thumb_name,$this->avatar_width,$this->avatar_height)==1){
							$post['User_Avatar'] = $dir_thumb.$thumb_name;

							if(file_exists($old_thumb)){
								unlink($old_thumb);	
							}
						}
					}

					// Update
					unset($post['User_Registered']);
					$rs = $this->update('user', $post, array('User_ID' => $_REQUEST['edit']));
				}
				
				if(!empty($rs)){
					header("location:user_list.php");
				}

			}else{
				$rs = array('errors' => $errors);
				return $rs;	
			}
		}
	}


	// Upload danh sách ảnh
	public function ajax_upload_thumbnail(){

		if(isset($_POST['image_src'])){

			$dir = '../upload/user/';

			if(!is_dir($dir)){
				mkdir($dir);
	        }

	        $dir_thumb = $dir.'avatar/'; 
			if(!is_dir($dir_thumb)){
				mkdir($dir_thumb);
	        }
	        
			$file_path = $_POST['image_src'];

			// Đuôi ảnh
			$ext = $this->image->file_type($file_path);

			// Tên ảnh
			$name = date('YmdHis').'-'.rand(100000, 999999).'.'.$ext;

			// Path ảnh
			$img_path = $dir_thumb.$name;

			if(strpos($_SERVER['HTTP_HOST'],'localhost')!== false){
				$file_path = str_replace($this->user_port,$this->local_port,$file_path);
			}
			
			// Di chuyển ảnh từ thư mục temp vào thư mục ảnh gốc
			file_put_contents($img_path, file_get_contents($file_path));
			
			echo $dir_thumb.$name;exit;
		}
	}

	// Xóa user
	public function remove_user(){
		if(isset($_GET['delete'])){

			$dir = '../upload/user/';

			// Dữ liệu cần xóa
			$current_data = $this->get_current_data($_GET['delete']);

			// Xóa ảnh đại diện (thumbnail)
			$old_thumb = $current_data['User_Avatar'];
			
			// Tiến hành xoá ảnh đại diện nếu có	
			if(file_exists($old_thumb)){		
				unlink($old_thumb);
			}
			
			// Xóa dữ liệu trong database
			$rs = $this->delete('user',array('User_ID' => $_GET['delete']));
			if($rs==1){
				header("location:user_list.php");
			}
		}
	}
	
}
?>