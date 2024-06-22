<?php 
require __DIR__.'../../../vendor/autoload.php';

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

$email = new PHPMailer(true);
$email->isSMTP();
$email->setFrom('matteo.maloni.00@gmail.com','Matteo Maloni');
$email->addReplyTo('matteo.maloni.00@gmail.com', 'Matteo Maloni');
$email->addAddress('matteo.maloni.00@gamil.com','Matteo Maloni');
$email->Subject = 'PHPMailer mail() test';
#$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
$email->Body='<b>Ciao Matteo!</b>';
$email->isHTML(true);
if (!$email->send()) {
    echo 'Mailer Error: ' . $email->ErrorInfo;
} else {
    echo 'Message sent!';
}
