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


/*
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
*/
/*
$FA=FAdministrator::getInstance();
$admin=$FA->load(1);
if($admin==false)
{
    echo 'NON ESISTE!';
}
else
{
    echo $admin->__toString();
    $result=$FA->delete($admin->getID());
    if($result==true)
    {
        echo 'Tutto ok !';
    }
    else
    {
        echo 'Qualcosa non và';
    }
}
*/

# PROVA FReservation
/*
require_once('FReservation.php');
require_once('../Entity/EReservation.php');

$FR=FReservation::getInstance();
$result=$FR->getWaitingReservations(2);
print_r($result);

*/
/*

if($result===true)
{
    echo 'Reservation effettuata!';
}
else
{
    echo 'Qualcosa no và!';
}


/*
# PROVA EXIST FCONTRACT

require_once('FContract.php');

$FC= FContract::getInstance();
$id=3;
$result=$FC->exist($id);

if($result)
{
    echo 'Il contratto esiste!';
}
else
{
    echo 'Il contratto non esiste!';
}

*/

# prova uso di address
/*
require __DIR__ . '/../../vendor/autoload.php';
use CommerceGuys\Addressing\Address;
$address=new Address('Italy');
echo $address->getCountryCode();
*/
require_once('FPersistentManager.php');

require_once('../Entity/EStudent.php');

$PM= FPersistentManager::getInstance();
$student=FPersistentManager::load(FStudent::class,3);
echo $student->__toString();