<?php 
class module_footer_controller extends atz{

    // Select Cat List
    public function get_articles(){
        $articles = $this->select('article',array('Article_Show'=>1),array('Article_Priority'=>'DESC','Article_ID'=>'ASC','Article_Title_vi'=>'ASC'));
        return $articles;
    }
}
?>