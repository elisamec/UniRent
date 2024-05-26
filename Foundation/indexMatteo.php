<?php 

    #PARTE MATTEO

require_once('../Entity/EStudent.php');
require_once('FStudent.php');

$id=23;
$studente=FStudent::getInstance()->load($id);

if($studente!=false)
{
    echo $studente->__toString();
}
else
{
    echo 'Qualcosa non và!';
}