<?php

namespace UniRent;
require __DIR__ . '/vendor/autoload.php';

use Classes\Control\CFrontController;


// inclusione per configurazione e creazione oggetto smarty
require('StartSmarty.php');

//$smarty = StartSmarty::configuration();

//$smarty->display('home.tpl');

$fc = new CFrontController();
$fc->run($_SERVER['REQUEST_URI']);