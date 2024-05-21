<?php 
require 'FOwner.php';
$id =1;
$FO=FOwner::getInstance();
$risultato=$FO->exist($id);
if($risultato==false)
{
    print 'Non Presente nel db';
}
else{
    print 'Problema';
}