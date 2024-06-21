<?php

require __DIR__.'../vendor/autoload.php';
use Classes\Control\CFrontController;
require('StartSmarty.php');


$fc = new CFrontController();
$fc->run($_SERVER['REQUEST_URI']);