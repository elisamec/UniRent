<?php 
require __DIR__.'../../../vendor/autoload.php';

use Classes\Entity\EStudent;
use Classes\Foundation\FPersistentManager;
use Classes\Foundation\FStudent;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Classes\Foundation\FOwner;
# PARTE MATTEO cancellato materiale precedente 22/06/2024
/*
$path='UniIta.json';
$json=file_get_contents($path);
$myarray=json_decode($json,true);

print_r($myarray['records'][0][12]);
$università=$myarray['records'];
$indirizzi_web=array();
foreach($università as $key=>$value)
{
    $domain = substr(strrchr($università[$key][12], "www"), 1);
    $domain ='@student'.$domain;
    if($domain!='@student')
    {
        $indirizzi_web[]=$domain;
    }   
}
print_r($indirizzi_web);
*/

/* PARTE MAIL
$email = new PHPMailer(true);
$email->isSMTP();
$email->Host = '127.0.0.1'; // Indirizzo del server SMTP di Mercury
$email->SMTPAuth = false; // Di solito, Mercury non richiede autenticazione
$email->Port = 25; // Porta standard SMTP

#$email->setFrom('matteo.maloni.00@gmail.com', 'Matteo Maloni');
#$email->addReplyTo('matteo.maloni.00@gmail.com', 'Matteo Maloni');
$email->addAddress('matteo.maloni.00@gmail.com', 'Matteo Maloni');
$email->Subject = 'PHPMailer mail() test';
$email->Body = '<b>Ciao Matteo!</b>';
$email->isHTML(true);

try {
    $email->send();
    echo 'Message sent!';
} catch (Exception $e) {
    echo 'Mailer Error: ' . $email->ErrorInfo;
}

*/
/*
$student= new EStudent('jojo','pippo','Giovanni','Filone',null,'giovanni.filone@student.univaq.it',3,2023,new DateTime('now'),'M',true,true);
$PM=FPersistentManager::getInstance();
$result=$PM::store($student);
if($result)
{
    print ' nessun problema!';
}
else
{
    echo ' qualcosa non và!';
}
    */

    /*
$PM= FPersistentManager::getInstance();
$oldEmail='lorenzo.maloni@gmail.com';
$newEmail='lorenzo.maloni@student.unito.it';
if($oldEmail===$newEmail)
{
    print 'La mail non è cambiata';
}
else
{
    print 'le mail sono diverse';
}
if($PM->verifyUserEmail($newEmail)==false)
{
    print ' la mail nuova non è in uso';
}
else
{
    print ' la mail nuova è già in uso';
}
if($PM->verifyStudentEmail($newEmail))
{
    print ' La nuova mail è una mail universitaria';
}
else
{
    print ' La nuva mail non è una mail universitaria';
}

//Se la nuova mail è uguale a quella vecchia allora stampa vero
if($newEmail===$oldEmail)
{
    print '  true';
}
else
{
    print '  false';
}

if((($PM->verifyUserEmail($newEmail)==false)&&($PM->verifyStudentEmail($newEmail)))||($oldEmail===$newEmail))
{
    print '       OPERAZIONI IN CORSO!';
}

*/
/*
$password='pippo';
$password_h=password_hash($password,PASSWORD_DEFAULT);
print $password_h;
if(password_verify($password,$password_h))
{
    print '   Tutto ok!';
}*/

$phone=' 331 123 000';


$result_39=strncmp($phone,'+39',3);
$result_0039=strncmp($phone,'0039',4);
if($result_39===0)   #se inizia con +39
{
    $phone=substr($phone,3);
    $phone=str_replace(' ','',$phone);
}
else
{
    if($result_0039===0) #se inizia con 0039
    {
        $phone=substr($phone,4);
        $phone=str_replace(' ','',$phone);
    }
    else # altrimenti è senza prefisso
    {
        $phone=str_replace(' ','',$phone);
    }
}
print $phone;