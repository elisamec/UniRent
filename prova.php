<?php
namespace App;
require __DIR__ . '/../../vendor/autoload.php';
use CommerceGuys\Addressing\Address;

$addres=new Address('Italy');
echo $addres->getCountryCode();


