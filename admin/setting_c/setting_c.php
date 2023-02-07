<?php ob_start(); require_once('../lib/atz.php');?>
<?php 
class setting_controller extends atz{

	public function __construct() { 
		parent::__construct();

		$this->check_login();
		
		$this->post = array(
			'Setting_Title'=>'',
			'Setting_Slogan'=>'',
			'Setting_Description'=>'',
			'Setting_Keywords'=>'',
			'Setting_Company'=>'',
			'Setting_CompanyCode'=>1,
			'Setting_Phone'=>1,
			'Setting_Email'=>'',
			'Setting_Address'=>'',
			'Setting_Map'=>'',
		);
	}

	public function get_current_data($lang){

		$current_data = $this->select('setting',array('Setting_Lang'=>'vi'));
					
		if(!empty($current_data)){
			return $current_data[0];	
		}
		header('location:'.$this->site_url['admin'].'setting.php');
	}

	public function update_setting(){
	
		$post = $this->post;
		
		$errors = array();

		// Sửa - Lấy dữ liệu cũ 
		$current_data = $this->get_current_data('vi');
		
			
		if($current_data){
			foreach ($current_data as $k => $v) {
				if(isset($current_data[$k])){
					$current_data[$k] =$v;
				}
			}
			$this->post = $current_data;
		}

		// Thêm, Cập nhật dũ liệu
		if(!empty($_REQUEST) && isset($_REQUEST['submit'])){

			foreach ($_REQUEST as $k => $v) {
				if(isset($post[$k])){
					$post[$k] =$v;
				}
			}	
			
			$this->post = $post;
				
			if(!$post['Setting_Title']){
				$errors['Setting_Title'] = 'Chưa nhập tên';
			}
			if(!$post['Setting_Description']){
				$errors['Setting_Description'] = 'Chưa nhập mô tả';
			}

			// Tiến hành insert, update
			if(empty($errors)){

				// if (!isset($_REQUEST['edit'])) {

				// 	// Insert
				// 	// $post['Setting_Created'] = time();

				// 	$rs = $this->insert('setting', $post);

				// }else{

				// 	// Update
				// 	// $post['Setting_Updated'] = time();
				// 	$rs = $this->update('setting', $post, array('Setting_Lang' => 'vi'));
				// }
				
				// echo '<pre>';
				// print_r($post);
				// die;
					
				$rs = $this->update('setting', $post, array('Setting_Lang' => 'vi'));

				if(!empty($rs)){
					header("location:setting.php");
				}

			}else{
				$rs = array('errors' => $errors);
				return $rs;	
			}
		}
	}

	
}
?>