<?php

require __DIR__.'/vendor/autoload.php';
use Classes\Control\CFrontController;
use Updater\Updater;

require('StartSmarty.php');

#Updater::getInstance()->run();      per il momento sospeso , devo finire a scrivere un metodo
$fc = new CFrontController();
$fc->run($_SERVER['REQUEST_URI']);
