<?php
require(__DIR__ . '/../Smarty/Smarty.class.php');


class StartSmarty{
    /**
     * configuration
     * 
     * This method is used to configure the smarty object
     * @return Smarty
     */
    static function configuration(){
        $smarty=new Smarty();
        $smarty->template_dir= __DIR__ . '/../Smarty/templates/';
        $smarty->compile_dir= __DIR__ . '/../Smarty/templates_c/';
        $smarty->config_dir= __DIR__ . '/../Smarty/configs/';
        $smarty->cache_dir= __DIR__ . '/../Smarty/cache/';
        return $smarty;
    }
}