<?php 

require_once ('FCreditCard.php');
require_once ('../Entity/ECreditCard.php');



$FCC=FCreditCard::getInstance();

/* LOAD
#$number =1;
$risultato=$FCC->load($number);
$stringa=$risultato->__toString();
print $stringa;
*/

/* STORE 

$carta= new ECreditCard(14,'Simone','Cialini','BUBUSETTETE',246,10);
$esito=$FCC->store($carta);
if($esito==true)
{
    echo 'Carta di credito inserita nel DataBase! ';
}
elseif($esito==false)
{
    echo 'Non caricato sul db! ';
}
else
{
    echo 'Qualcosa non và! ';
}
*/

/* DELETE 

$esito=$FCC->delete(14);
if($esito==true)
{
    echo 'Carta di credito disinserita! ';
}
elseif($esito==false)
{
    echo 'Operazione non riuscita! ';
}
else
{
    echo 'C\'è qualche problema!'; 
}
*/