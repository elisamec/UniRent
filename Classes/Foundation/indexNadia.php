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

    $visit = new EVisit(0, "2024-09-15", 1, 2);
    $esito=$FV->store($visit);
    if($esito==true)
    {
        echo 'Visita salvata ';
        print $visit->getIdVisit();
    }
    elseif($esito==false)
    {
        echo 'Non caricato sul db! ';
    }
    else
    {
        echo 'Qualcosa non và! ';
    }
    
    