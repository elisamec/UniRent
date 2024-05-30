<?php 

    #PARTE MATTEO
/*
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
*/

require_once('../Entity/EAdministrator.php');
require_once('FAdministrator.php');

$id=2;
$FA=FAdministrator::getInstance();
$amministratore=$FA->load($id);
if($amministratore==false)
{
    echo 'Questo amministratore non esiste! ';
}
else
{
    echo $amministratore->__toString();
}