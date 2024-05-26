<?php 

    #PARTE NADIA

    require_once ('FVisit.php');
    require_once ('../Entity/EVisit.php');  

    $FV=FVisit::getInstance();

    if(1)
    {
        echo 'Esiste la visita';
    }
    else
    {
        echo 'Non esiste la visita ';
    }