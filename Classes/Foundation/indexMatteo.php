<?php 

    #PARTE MATTEO
/*
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

/*
require_once('../Entity/EStudent.php');
require_once('FStudent2.php');

$FS=FStudent2::getInstance();
$stu=$FS->load(2);
#echo $stu->__toString();
$stu->setName('MIKE');

$result=$FS->update($stu);
if($result)
{
    echo 'Tutto ok!';
}
else
{
    echo 'Qualcosa non funziona!';
}
*/