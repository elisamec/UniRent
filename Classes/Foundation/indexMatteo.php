<?php 
require __DIR__.'../../../vendor/autoload.php';

use Classes\Entity\EStudent;
use Classes\Foundation\FPersistentManager;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
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
$PM=FPersistentManager::getInstance();
$user='Fratmo';
$Student=$PM->getSBU($user);
print $Student->__toString();
