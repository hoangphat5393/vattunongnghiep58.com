<?php require_once('lib/atz.php');?>
<?php 
class index_controller extends atz{

	public function __construct() {
		parent::__construct();
	}

	// Lấy thông tin chuyên mục
	public function get_cats(){
		$cats = $this->select('cat',array('Cat_Status'=>1, 'Cat_Hot'=>1, 'Cat_Type'=>'product'), array('Cat_Order'=>'ASC', 'Cat_Name_vi'=>'ASC'));
		return $cats;
	}

	public function get_specific_cats($id){
		$cats = $this->select('cat',array('Cat_Status'=>1, 'Cat_ID'=>$id), array('Cat_Order'=>'ASC', 'Cat_Name_vi'=>'ASC'));
		return $cats;
	}

	public function get_products($cat_id){
		$products = $this->select('product',array('Product_Cat' => $cat_id));
		return $products;
	}

	public function get_product_hot(){
		$products = $this->select('product',array('Product_Show' => 1, 'Product_Hot' => 1),
											array('Product_Priority'=>'DESC', 'Product_Name_vi'=>'ASC'),
											'Product_ID,Product_Name_vi,Product_Thumbnail,Product_Price');
		return $products;
	}

	public function get_posts($cat_id){
		$posts = $this->select('post',array('Post_Cat' => $cat_id));
		return $posts;
	}
	
	public function get_cat_post($id){
		$rs = array();
		$cat = $this->select('cat',array('Cat_Status'=>1, 'Cat_ID'=>$id), array('Cat_Order'=>'ASC', 'Cat_Name_vi'=>'ASC'));

		if(!empty($cat)){
			$rs = $cat[0];
			$rs['post'] = '';
			$posts = $this->select('post',array('Post_Cat' => $rs['Cat_ID']));

			if(!empty($posts)){
				$rs['post'] = $posts;
			}
		}
		return $rs;
	}
	
}
?>