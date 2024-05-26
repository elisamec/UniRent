<?php 


#PARTE MATTEO
/*
    METTI QUI IL TUO CODICE
*/

#PARTE ELISABETTA
/*
    METTI QUI IL TUO CODICE
*/

#PARTE NADIA
/*
    METTI QUI IL TUO CODICE
*/


#CODICE DA SMISTARE NELLE RELATIVE PARTI:/*


#require_once ('FCreditCard.php');
#require_once ('../Entity/ECreditCard.php');
require_once('FStudent.php');
require_once('../Entity/EStudent.php');


#$FCC=FCreditCard::getInstance();

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

/*$FS=FStudent::getInstance();
$risultato_exist=$FS->exist(5);
if($risultato_exist)
{
    echo 'Si lo studente è nel DataBase!';
}
else
{
    echo 'No non c\' è! ';
}

*/

#FINE CODICE DA SMISTARE