<?php

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/config.php';
require __DIR__.'/StartSmarty.php';

use Classes\Control\CFrontController;
use Updater\Updater;
use Installation\Installation;

Installation::install();
Updater::getInstance()->run();      #per il momento sospeso , devo finire a scrivere un metodo
$fc = new CFrontController();
$fc->run($_SERVER['REQUEST_URI']);
