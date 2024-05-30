<?php 

    #PARTE MATTEO

require_once('../Entity/EStudent.php');
require_once('FStudent.php');

$student=new EStudent('Fratmo','pippo','Lorenzo','Maloni',null,'lorenzo.maloni.02@gmail.com',3,2021,new DateTime('11/9/2002'),'M',0,0);
$FS=FStudent::getInstance();
$risultato=$FS->store($student);

if($risultato!=false)
{
    echo 'Studente nel database';
}
else
{
    echo 'Qualcosa non và!';
}

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