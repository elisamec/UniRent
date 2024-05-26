<?php 

    #PARTE NADIA

    require_once ('FVisit.php');
    require_once ('../Entity/EVisit.php');  

    $FV=FVisit::getInstance();

    $id = 4;

    /*if($FV->exist($id)){
        echo 'Esiste la visita';
    }else{
        echo 'Non esiste la visita ';
    }*/

    $risultato=$FV->load($id);
    $stringa=$risultato->__toString();
    print $stringa;
    