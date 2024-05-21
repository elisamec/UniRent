<?php 
require 'FCreditCard.php';
require '../Entity/ECreditCard.php';

$number =1;
$FCC=FCreditCard::getInstance();
$risultato=$FCC->load($number);
$stringa=$risultato->__toString();
print $stringa;