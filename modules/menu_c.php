<?php 
class module_menu_controller extends atz{

    // Select Cat List
    public function get_cats(){
        
        // $parent_cats = $this->select('cat',array('Cat_Parent'=>0, 'Cat_Status'=>1), array('Cat_Order'=>'ASC', 'Cat_Name_vi'=>'ASC'));

        $sql = "SELECT  * 
                FROM    `cat` 
                where   `Cat_Parent`=0 AND `Cat_Status`=1 AND FIND_IN_SET('main_menu',`Cat_Show`)
                ORDER BY `Cat_Order` ASC, `Cat_Name_vi` ASC";

        $parent_cats = $this->raw_query($sql);
            
        if(!empty($parent_cats)){
            foreach ($parent_cats as $k => $v) {

                $sql = "SELECT  * 
                        FROM    `cat` 
                        where   `Cat_Parent`=".$v['Cat_ID']." AND `Cat_Status`=1 AND FIND_IN_SET('main_menu',`Cat_Show`)
                        ORDER BY `Cat_Order` ASC, `Cat_Name_vi` ASC";
                        
                $parent_cats[$k]['Cat_Child'] = array();
                $parent_cats[$k]['Cat_Child'] = $this->raw_query($sql);
                
                if(!empty($parent_cats[$k]['Cat_Child'])){
                    foreach ($parent_cats[$k]['Cat_Child'] as $k1 => $v1) {

                        $sql = "SELECT  * 
                                FROM    `cat` 
                                where   `Cat_Parent`=".$v1['Cat_ID']." AND `Cat_Status`=1 AND FIND_IN_SET('main_menu',`Cat_Show`)
                                ORDER BY `Cat_Order` ASC, `Cat_Name_vi` ASC";

                        $parent_cats[$k]['Cat_Child'][$k1]['Cat_Child'] = array();
                        $parent_cats[$k]['Cat_Child'][$k1]['Cat_Child'] = $this->raw_query($sql);
                    }
                }
            }
        }
            
        return $parent_cats;
    }

    public function get_side_cats(){
        
        $parent_cats = $this->select('cat',array('Cat_Parent'=>0, 'Cat_Status'=>1));

        if(!empty($parent_cats)){
            foreach ($parent_cats as $k => $v) {
                $parent_cats[$k]['children'] = array();
                $parent_cats[$k]['children'] = $this->select('cat',array('Cat_Parent'=>$v['Cat_ID'], 'Cat_Status'=>1));
            }
        }
        return $parent_cats;
    }
    
}
?>