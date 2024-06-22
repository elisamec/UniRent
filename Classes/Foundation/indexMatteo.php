<?php 

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

//Multiple recipients
$to='matteo.maloni.00@gmail.com';
//Subject
$subject='oggetto della email';
//Message
$message='
<html>
<head>
 <title>Esempio email in formato html</title>
</head>
<body>
<p>Ciao Matteo!!!!</p>
</body>
</html>
';
//To send HTML mail, the Content-type header must be set
$headers[]='MIME-Version:1.0';
$headers[]='Content-type:text/html; charset=iso-8859-1';
$headers[]='From:matteo.maloni.00@gmail.com';
$headers[]='Cc:matteo.maloni.00@gmail.com';
$headers[]='matteo.maloni.00@gmail.com';
// Mail it
mail($to,$subject,$message,implode("\r\n", $headers));
