<?php

use Classes\Foundation\FPersistentManager;

require __DIR__ . '/../../vendor/autoload.php';

$PM=FPersistentManager::getInstance();
$result=$PM->getStatistics();
print_r($result);