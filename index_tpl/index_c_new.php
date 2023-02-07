<?php require_once('lib/atz.php');?>
<?php 
class index_controller extends atz{

	public function __construct() {
		parent::__construct();
	}

	// Lấy thông tin chuyên mục
	public function get_cats(){
		$cats = $this->select('cat',array('Cat_Show'=>1, 'Cat_Hot'=>1, 'Cat_Type'=>'product'), array('Cat_Order'=>'ASC', 'Cat_Name_vi'=>'ASC'));
		return $cats;
	}

	public function get_specific_cats($id){
		$cats = $this->select('cat',array('Cat_Show'=>1, 'Cat_ID'=>$id), array('Cat_Order'=>'ASC', 'Cat_Name_vi'=>'ASC'));
		return $cats;
	}

	public function get_products($cat_id){
		$products = $this->select('product',array('Product_Cat' => $cat_id));
		return $products;
	}

	public function get_hostings_hot(){
		$hostings = $this->select('hosting',array('Hosting_Show' => 1, 'Hosting_Hot' => 1),
											array('Hosting_Order'=>'ASC'));
		return $hostings;
	}

	// Lấy thông tin tin tức liên quan
	public function get_posts(){
		$posts = array();
		
		$sql = "SELECT  `Post_ID`, `Post_Title_vi`, `Post_Description_vi`, `Post_Thumbnail`, `Post_Created`
                FROM    `post` 
                WHERE   `Post_Show`=1
                LIMIT	5";
		$posts = $this->raw_query($sql);

		return $posts;
	}
	
	public function get_cat_post($id){
		$rs = array();
		$cat = $this->select('cat',array('Cat_Show'=>1, 'Cat_ID'=>$id), array('Cat_Order'=>'ASC', 'Cat_Name_vi'=>'ASC'));

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