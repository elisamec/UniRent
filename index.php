<?php

require __DIR__.'../vendor/autoload.php';
use Classes\Control\CFrontController;
require('StartSmarty.php');

$smarty = StartSmarty::configuration();

$smarty->display('home.tpl');


//$fc = new CFrontController();

//$fc->run("/UniRent/Test/test");
//$fc->run($_SERVER['REQUEST_URI']);