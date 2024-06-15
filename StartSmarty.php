<?php
require(__DIR__ . '/libs/Smarty.class.php');

class StartSmarty{
    static function configuration(){
        $smarty=new Smarty();
        $smarty->template_dir= __DIR__ . '/libs/templates/';
        $smarty->compile_dir= __DIR__ . '/libs/templates_c/';
        $smarty->config_dir= __DIR__ . '/libs/configs/';
        $smarty->cache_dir= __DIR__ . '/libs/cache/';
        return $smarty;
    }
}