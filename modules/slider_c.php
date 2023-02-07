<?php 
class module_slide_controller extends atz{

    // Select Cat List
    public function get_slides(){
        $slides = $this->select('slide',array('Slide_Show'=>1), array('Slide_Order'=>'ASC', 'Slide_Title_vi'=>'ASC'));
        return $slides;
    }
}
?>