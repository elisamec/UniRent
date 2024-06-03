<?php 

    #PARTE MATTEO
/*

PROVA STUDENT

require_once('../Entity/EStudent.php');
require_once('FStudent.php');

$FS=FStudent::getInstance();
$student=$FS->load(3);
echo $student->getName().' '.$student->getSurname();
$student->setSurname('Maloni');



$risultato=$FS->update($student);

if($risultato!=false)
{
    echo 'Studente modificato nel db!';
}
else
{
    echo 'Qualcosa non và!';
}
*/


#PROVA ADMINISTRATOR

use Classes\Entity\EAdministrator;

require_once('../Entity/EAdministrator.php');
require_once('FAdministrator.php');
/*
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
*/

$FA=FAdministrator::getInstance();
$admin=$FA->load(1);
if($admin==false)
{
    echo 'NON ESISTE!';
}
else
{
    echo $admin->__toString();
    $admin->setEmail('asdrubale@gmail.com');
    $result=$FA->update($admin);
    if($result==true)
    {
        echo 'Tutto ok !';
    }
    else
    {
        echo 'Qualcosa non và';
    }
}

