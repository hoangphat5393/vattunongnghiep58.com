<?php
    // ob_start(); session_start(); // First of file
    
    require_once('routes.php');

    require_once('lib/atz.php');
    
    $atz = new atz();

    define('_module_default', 'index_tpl/index');

    // define('_module_admin', 'admin/index/index');

    // CHECK MODULE
    if(!empty($_GET['module'])){
        $module = $_GET['module'];
    }else{
        $module = _module_default;
    }
    
    // REPLACE ROUTER
    $url = '';
    if (!empty($routes)) {
        foreach($routes as $key => $value){
            if(preg_match('#^'.$key.'$#', $module)){
                $url = preg_replace('#^'.$key.'$#', $value, $module);
                break;
            }
        }
    }


    // CHECK URL
    if(!empty($url)){
        $module = $url;
    }

    $module_arr = array_filter(explode('/',$module));
        
    $url = '';
    
    if(!empty($module_arr)){

        foreach ($module_arr as $key => $item) {
            // $url = $url.'/'.$item;

            $url = $item; // Set url
        
            if(file_exists($url.'_tpl/'.$url.'.php')){
                // for($i=0; $i < $key; $i++){
                //     unset($module_arr[$i]);
                // }
                    
                for($i=0; $i <= $key; $i++){
                    unset($module_arr[$i]);
                }
                break;    
            }
        }
    }
    
    // GET PARAM
    $param = array_values($module_arr);
    

    // SET MODULE
    $module = $url;

    $load_file = $module.'_tpl/'.$module.'.php';

    // Load file default
    if(!file_exists($load_file)){           
        if(is_dir($module)){  
            $module = _module_default;
        }
    }
    
    // LOAD MODULE
    if(file_exists($load_file)){
        require_once $load_file;
        // if($module=='index'){
        //     require_once _module_default;
        // }else{
        //     require_once $load_file;
        // }
    }else{
        require_once '404.php';
    }
?>
