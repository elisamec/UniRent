<?php 

require_once ('FCreditCard.php');
require_once ('../Entity/ECreditCard.php');

$number =1;
$FCC=FCreditCard::getInstance();
$risultato=$FCC->load($number);
$stringa=$risultato->__toString();
print $stringa;