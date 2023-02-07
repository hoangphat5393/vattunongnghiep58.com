<?php require_once('lib/atz.php');?>
<?php 
class post_controller extends atz{

	public function __construct() {
		parent::__construct();

		// Get param
		$this->page = $this->get_params(1);
		$this->param = $this->get_params(2);

		$ex = explode('.',$this->param);
		$ex1 = explode('-',$ex[0]);
		$this->id = end($ex1);	
	}

	// Lấy thông tin chuyên mục
	public function get_cat($id){
		
		$cat = array();
		if(!empty($id)){
			$cat = $this->select('cat',array('Cat_ID'=>$id), array('Cat_Order'=>'ASC', 'Cat_Name_vi'=>'ASC'));

			// Kiểm tra có chuyên mục con hay không
			if(!empty($cat)){
				$cat = $cat[0];
			}
		}
		return $cat;
	}

	// Lấy danh sách chuyên mục
	public function get_cats($cat_id){
		
		$cat_list = $cat_id;
		// Kiểm tra có chuyên mục con hay ko
		if(!empty($cat_id)){

			$cat_child = $this->select('cat',array('Cat_Parent'=>$cat_id));

			if (!empty($cat_child)) {
				
				foreach ($cat_child as $v) {
					$cat_list .= ','.$v['Cat_ID']; 
				}
			}
		}
		return $cat_list;
	}

	// Lấy danh sách chuyên mục liên quan
	public function get_relate_cats($cat_id){
		
		$cat = array();
		if(!empty($id)){
			$cat = $this->select('cat',array('Cat_ID'=>$id), array('Cat_Order'=>'ASC', 'Cat_Name_vi'=>'ASC'));

			// Kiểm tra có chuyên mục con hay không
			if(!empty($cat)){
				$cat = $cat[0];
			}
		}
		return $cat;
	}

	// Lấy danh sách bài viết
	public function get_posts($cat_list){
		
		$data = array();
		if(isset($_GET['id']) && !empty($_GET['id'])){
			$data = $this->select_in('post',array('Post_Cat'=>$cat_list),array('Post_Priority'=>'DESC'));
		}
		return $data;
	}

	// Lấy thông tin tin tức
	public function get_post($id){
		
		$post = array();
		if(isset($id) && !empty($id)){
			$post = $this->select('post',array('Post_ID'=>$id, 'Post_Show'=>1));
			
			if(!empty($post)){
				$post = $post[0];
			}
		}	
		return $post;
	}

	// Lấy thông tin tin tức liên quan
	public function get_relative_posts($id){
		$posts = array();
		$sql = "SELECT  * 
                FROM    `post` 
                where   `Post_ID`!=$id AND `Post_Show`=1
                ORDER BY `Post_Priority` DESC, `Post_Title_vi` ASC";
        $posts = $this->raw_query($sql);

		return $posts;
	}

	public function update_view($id, $view){

		if(isset($id) && $id){
			// Update
			$post = array();
			$post['Post_View_vi'] = $view+1;
			$post['Post_Updated'] = time();
			$this->update('post', $post, array('Post_ID' => $id));
		}
	}

}
?>