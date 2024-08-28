<?php

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/Configuration/StartSmarty.php';

use Classes\Control\CFrontController;
use Updater\Updater;
use Configuration\Installation;


Installation::install();
Updater::getInstance()->run();   
$fc = new CFrontController();
$fc->run($_SERVER['REQUEST_URI']);
