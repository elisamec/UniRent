<?php 

    #PARTE NADIA

    require_once ('FVisit.php');
    require_once ('../Entity/EVisit.php');  

    $FV=FVisit::getInstance();

    $id = 4;

    //EXIST
    /*if($FV->exist($id)){
        echo 'Esiste la visita';
    }else{
        echo 'Non esiste la visita ';
    }*/

    //LOAD
    /*$risultato=$FV->load($id);
    $stringa=$risultato->__toString();
    print $stringa;*/

    // STORE 
    //$date = new DateTime("2002-09-15");
    //$studente= new EStudent(2, "Pippo", "pass", "Pippo", "Pluto", 1, "pippo@pluto.it", 3, 2032, $date, false, false, false);

    $visit = new EVisit(10, "2024-05-26", 1, 2);
    $esito=$FV->store($visit);
    if($esito==true)
    {
        echo 'Visita inserita nel DataBase! ';
    }
    elseif($esito==false)
    {
        echo 'Non caricato sul db! ';
    }
    else
    {
        echo 'Qualcosa non và! ';
    }
    
    