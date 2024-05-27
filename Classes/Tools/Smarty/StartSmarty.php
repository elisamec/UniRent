<?php
require __DIR__ . '/vendor/autoload.php';
use Smarty\Smarty;

class StartSmarty extends Smarty{
    static function configuration(){
        $smarty=new Smarty();
        $smarty->template_dir='Smarty/templates/';
        $smarty->compile_dir='Smarty/templates_c/';
        $smarty->config_dir='Smarty/configs/';
        $smarty->cache_dir='Smarty/cache/';
        return $smarty;
    }
}