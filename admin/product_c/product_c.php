<?php ob_start();
require_once('../lib/atz.php'); ?>
<?php
class product_controller extends atz
{

	public function __construct()
	{
		parent::__construct();

		$this->check_login();

		$this->post = array(
			'Product_Name_vi' => '',
			'Product_Thumbnail' => '',
			'Product_Imgs' => '',
			'Product_Description_vi' => '',
			'Product_Content_vi' => '',
			'Product_Price' => 0,
			'Product_PriceType' => '',
			'Product_PriceUnit' => 'VNĐ',
			'Product_Discount' => 0,
			'Product_DiscountUnit' => 'VNĐ',
			'Product_SalePrice' => 0,
			'Product_Keywords_vi' => '',
			'Product_Priority' => 1,
			'Product_Hot' => 0,
			'Product_Show' => 1,
			'Product_Cat' => '',
		);
	}

	// Ảnh nhỏ (500x706)
	// public $img_width = 500;
	// public $img_height = 706;

	// Ảnh vừa (1000x1411)
	public $img_width = 1000;
	public $img_height = 1411;

	// Ảnh lớn (1365x1926)
	// public $img_width = 1365;
	// public $img_height = 1926;

	public function get_product()
	{
		$products = $this->select('product', '', array('Product_Priority' => 'DESC', 'Product_ID' => 'ASC', 'Product_Name_vi' => 'ASC'));
		return $products;
	}

	public function get_cat()
	{
		$cats = $this->select('cat', array('Cat_Type' => 'Product'));
		return $cats;
	}

	public function get_current_data($id)
	{

		$current_data = $this->select('product', array('Product_ID' => $id));

		if (!empty($current_data)) {
			return $current_data[0];
		}
		header('location:' . $this->site_url['admin'] . 'product_list.php');
	}

	public function add_product()
	{

		$post = $this->post;

		$errors = array();

		// Sửa - Lấy dữ liệu cũ 
		if (isset($_GET['edit'])) {

			$current_data = $this->get_current_data($_GET['edit']);

			if ($current_data) {
				foreach ($current_data as $k => $v) {
					if (isset($current_data[$k])) {
						$current_data[$k] = $v;
					}
				}

				// Tách danh sách ảnh
				$current_data['Product_Imgs'] = explode(PHP_EOL, $current_data['Product_Imgs']);
				if (empty($current_data['Product_Imgs'][0])) { // Không có ảnh thì danh sách = rỗng
					$current_data['Product_Imgs'] = array();
				}
				$this->post = $current_data;
			}
		}


		// Thêm, Cập nhật dũ liệu
		if (!empty($_REQUEST) && isset($_REQUEST['submit'])) {

			foreach ($_REQUEST as $k => $v) {
				if (isset($post[$k])) {
					$post[$k] = $v;
				}
			}

			$this->post = $post;

			// echo '<pre>';
			// print_r($post);
			// die;

			if (!$post['Product_Name_vi']) {
				$errors['Product_Name_vi'] = 'Chưa nhập tên';
			}
			if (empty($post['Product_Imgs'])) {
				$errors['Product_Imgs'] = 'Chưa đăng ảnh đại diện';
			}
			if (!$post['Product_Cat']) {
				$errors['Product_Cat'] = 'Chưa chọn chuyên mục';
			}
			if (!$post['Product_Price']) {
				$errors['Product_Price'] = 'Chưa nhập giá';
			} elseif (!is_numeric($post['Product_Price']) || $post['Product_Price'] < 0) {
				$errors['Product_Price'] = 'Giá phải nhập số và >= 0';
			}
			// Kiểm tra giảm giá
			if (!is_numeric($post['Product_Discount']) || $post['Product_Discount'] < 0) {
				$errors['Product_Discount'] = 'Giảm giá phải nhập số và >= 0';
			}
			if (!is_numeric($post['Product_SalePrice']) || $post['Product_SalePrice'] < 0) {
				$errors['Product_SalePrice'] = 'Giảm bán phải nhập số và >= 0';
			}
			if (!$post['Product_Content_vi']) {
				$errors['Product_Content_vi'] = 'Chưa nhập nội dung';
			}
			if ($post['Product_Priority'] == '') {
				$errors['Product_Priority'] = 'Chưa nhập độ ưu tiên';
			} elseif (!is_numeric($post['Product_Priority'])) {
				$errors['Product_Priority'] = 'Chỉ được nhập số';
			}


			// Thư mục ảnh
			$dir = '../upload/product/';

			if (!is_dir($dir)) {
				mkdir($dir);
			}

			// echo '<pre>';
			// print_r($post);
			// die;

			// Tiến hành insert, update
			if (empty($errors)) {

				// Upload ảnh đại diện (thumbnail)
				if (isset($_FILES['Product_Thumbnail']) && $_FILES['Product_Thumbnail']['tmp_name']) {

					$file = $_FILES['Product_Thumbnail'];

					$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

					$dir_thumb = $dir . 'thumbnail/';
					if (!is_dir($dir_thumb)) {
						mkdir($dir_thumb);
					}
				}

				$temp_images = str_replace('admin/product_c', '', __DIR__);

				$temp_images = '';
				// Upload danh sách ảnh
				$temp_images = array();
				foreach ($post['Product_Imgs'] as $src) {
					if (filter_var($src, FILTER_VALIDATE_URL)) {

						// Đuôi ảnh
						$ext = $this->image->file_type($src);

						// Tên ảnh
						$name = date('YmdHis') . '-' . rand(100000, 999999) . '.' . $ext;

						// Path ảnh
						$img_path = $dir . $name;
						// /var/www/html/vattunongnghiep58.com/admin/product_c
						// http://localhost:90/vattunongnghiep58.com/upload/tmp/1675755807_883571.jpg

						// /var/www/html/vattunongnghiep58.com/upload/tmp/1675755807_883571.jpg

						echo '<pre>';
						print_r(__DIR__);
						exit;
						echo '<pre>';
						print_r(file_get_contents('/var/www/html/vattunongnghiep58.com/upload/tmp/1675755807_883571.jpg'));
						exit;

						// Di chuyển ảnh từ thư mục temp vào thư mục ảnh gốc
						file_put_contents($img_path, file_get_contents($src));

						// Thêm ảnh vào dữ liệu cột chứa danh sách ảnh
						$temp_images[] = $name;
					} else {
						$temp_images[] = $src;
					}
				}


				// Xóa bỏ ảnh mà người dùng đã xóa
				if (isset($current_data)) {
					$diff = array_diff($current_data['Product_Imgs'], $temp_images);

					foreach ($diff as $name) {
						if (file_exists($dir . $name)) {
							unlink($dir . $name);
						}
					}
				}


				// Cập nhật danh sách ảnh mới
				$post['Product_Imgs'] = implode(PHP_EOL, $temp_images);


				// Upload ảnh trong editor
				$new_imgs = array();
				preg_match_all('/(?<!_)<img[^>]*src=([\'"])?(.*?)\\1/', $post['Product_Content_vi'], $temp);
				if (isset($temp[2]) && is_array($temp[2])) {
					$new_imgs = $temp[2];
				}

				// duyệt ảnh trong editor
				foreach ($new_imgs as $src) {
					// Đuôi ảnh
					$ext = pathinfo($src, PATHINFO_EXTENSION);

					// Tên ảnh
					$name = date('YmdHis') . '-' . rand(100000, 999999) . '.' . $ext;

					// Path ảnh
					$img_path = $dir . $name;

					// Di chuyển ảnh từ thư mục temp vào thư mục ảnh gốc
					file_put_contents($img_path, file_get_contents($src));

					// Đổi link ảnh trong editor
					$post['Product_Content_vi'] = str_replace($src, $this->site_url['main'] . substr($img_path, 3), $post['Product_Content_vi']);
				}

				if (!isset($_REQUEST['edit'])) {

					$thumb_name = time() . '_' . rand(100000, 999999) . '.' . $ext;

					// Tiến hành upload ảnh đại diện
					if (move_uploaded_file($file['tmp_name'], $dir_thumb . $thumb_name)) {
						$post['Product_Thumbnail'] = $dir_thumb . $thumb_name;
					}

					// Insert
					$post['Product_Created'] = time();

					$rs = $this->insert('product', $post);
				} else {

					$old_thumb = $current_data['Product_Thumbnail'];

					// Xóa ảnh (thumbnail) trong trường hợp sửa
					if ($old_thumb != $post['Product_Thumbnail']) {

						$name = time() . '_' . rand(100000, 999999) . '.' . $ext;

						if (file_exists($old_thumb)) {
							unlink($old_thumb);
						}
					}

					// Xoá ảnh cũ trong editor trường hợp sửa
					// Danh sách ảnh cũ
					$old_imgs = array();
					preg_match_all('/(?<!_)<img[^>]*src=([\'"])?(.*?)\\1/', $current_data['Product_Content_vi'], $temp);
					if (isset($temp[2]) && is_array($temp[2])) {
						$old_imgs = $temp[2];
					}

					// Danh sách ảnh đã bị xoá khỏi editor
					$diff = array_diff($old_imgs, $new_imgs);

					// Tiến hành xoá ảnh rác
					foreach ($diff as $img) {

						$path = str_replace($this->site_url['main'], $this->site_url['root'], $img);

						if (file_exists($path)) {
							unlink($path);
						}
					}

					// Update
					$post['Product_Updated'] = time();
					$rs = $this->update('product', $post, array('Product_ID' => $_REQUEST['edit']));
				}

				if (!empty($rs)) {
					header("location:product_list.php");
				}
			} else {
				$rs = array('errors' => $errors);
				return $rs;
			}
		}
	}


	// Upload ảnh đại diện
	public function ajax_upload_thumbnail()
	{

		if (isset($_POST['image_src'])) {

			$dir = '../upload/product/';

			if (!is_dir($dir)) {
				mkdir($dir);
			}

			$dir_thumb = $dir . 'thumbnail/';
			if (!is_dir($dir_thumb)) {
				mkdir($dir_thumb);
			}

			$file_path = $_POST['image_src'];

			// Đuôi ảnh
			$ext = $this->image->file_type($file_path);

			// Tên ảnh
			$name = date('YmdHis') . '-' . rand(100000, 999999) . '.' . $ext;

			// Path ảnh
			$img_path = $dir_thumb . $name;

			// Di chuyển ảnh từ thư mục temp vào thư mục ảnh gốc
			file_put_contents($img_path, file_get_contents($file_path));

			echo $dir_thumb . $name;
			exit;
		}
	}

	// Upload danh sách ảnh
	public function ajax_upload_image()
	{

		if (isset($_FILES['ajax_images_upload'])) {

			// Mảng dữ liệu trả về
			$result = array(
				'status' => 0, 'message' => '', 'file_name' => array()
			);

			$dir = '../upload/tmp/';

			if (!is_dir($dir)) {
				mkdir($dir);
			}

			$file = $_FILES['ajax_images_upload'];

			foreach ($file['error'] as $k => $e) {
				if ($e) { // Có lỗi
					$result['message'] = 'Ảnh không hợp lệ hoặc đăng ảnh thất bại'; // Tạo thông báo lỗi
					header("Access-Control-Allow-Origin: *");
					header('Content-Type: application/json');
					echo json_encode($result);
					exit;
				} else { // Không có lỗi
					// Lấy đuôi ảnh

					$filename = $file['name'][$k];
					$ext = $this->image->file_type($filename);

					// Chỉ cho upload ảnh
					if ($this->image->check_file($filename) != 1) {
						$result['message'] = 'Chỉ được upload JPG, PNG, BMP hoặc GIF';
						header("Access-Control-Allow-Origin: *");
						header('Content-Type: application/json');
						echo json_encode($result);
						exit;
					} else {
						$name = time() . '_' . rand(100000, 999999) . '.' . $ext;

						// upload file
						if ($this->image->upload($file['tmp_name'][$k], $dir, $name, 285, 200) == 1) {
							$result['file_name'][] = $name;
						}
					}

					// Tiến hành upload
					if ($result['message'] == '') {
						$result['status'] = 1;
						$result['message'] = 'Đăng ảnh thành công';
					}
				}
			}

			// Trả về kiểu json
			header("Access-Control-Allow-Origin: *");
			header('Content-Type: application/json');
			echo json_encode($result);
			exit;
		}
	}


	// Upload ảnh trong editor
	public function ajax_upload_image_editor()
	{

		if (isset($_FILES['summernote'])) {

			$dir = '../upload/tmp/';

			if (!is_dir($dir)) {
				mkdir($dir);
			}

			$file = $_FILES['summernote'];

			$filename = $file['name'];
			$ext = $this->image->file_type($file['name']);

			// Chỉ cho upload ảnh
			if ($this->image->check_file($filename) != 1) {
				echo 'error';
			} else {
				$name = time() . '_' . rand(100000, 999999) . '.' . $ext;

				// upload file
				if ($this->image->upload($file['tmp_name'], $dir, $name) == 1) {
					echo $dir . $name;
					exit;
				}
			}
		}
	}

	// Xóa bài viết
	public function remove_product()
	{
		if (isset($_GET['delete'])) {

			$dir = '../upload/product/';

			// Dữ liệu cần xóa
			$current_data = $this->get_current_data($_GET['delete']);


			// Xóa ảnh đại diện (thumbnail)
			$old_thumb = $current_data['Product_Thumbnail'];

			// Tiến hành xoá ảnh đại diện nếu có	
			if (file_exists($old_thumb)) {
				unlink($old_thumb);
			}


			// Tiến hành xoá danh sách ảnh nếu có	
			$old_list_img = $current_data['Product_Imgs'];
			if ($old_list_img) {
				$old_list_img = explode(PHP_EOL, $old_list_img);

				foreach ($old_list_img as $v) {
					if (file_exists($dir . $v)) {
						unlink($dir . $v);
					}
				}
			}


			// Danh sách ảnh cũ trong editor
			$old_imgs = array();
			preg_match_all('/(?<!_)<img[^>]*src=([\'"])?(.*?)\\1/', $current_data['Product_Content_vi'], $temp);
			if (isset($temp[2]) && is_array($temp[2])) {
				$old_imgs = $temp[2];
			}

			// Tiến hành xoá ảnh trong editor nếu có
			if (!empty($old_imgs)) {
				foreach ($old_imgs as $img) {
					$path = str_replace($this->site_url['main'], $this->site_url['root'], $img);
					if (file_exists($path)) {
						unlink($path);
					}
				}
			}

			// Xóa dữ liệu trong database
			$rs = $this->delete('product', array('Product_ID' => $_GET['delete']));
			if ($rs == 1) {
				header("location:product_list.php");
			}
		}
	}
}
?>