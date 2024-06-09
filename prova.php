<?php

require __DIR__ . '/vendor/autoload.php';
use Classes\Entity\EAdministrator;



$admin= new EAdministrator('Binotto','Luca','lucabinotto@gmail.com');
echo $admin->getEmail();

