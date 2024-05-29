<?php 

    #PARTE NADIA

    require __DIR__ . '/../../vendor/autoload.php';

    require_once ('FAccommodation.php');
    require_once ('../Entity/EAccommodation.php');
    require_once ('FVisit.php');
    require_once ('../Entity/EVisit.php');
    require_once ('FPhoto.php');
    require_once ('../Entity/EPhoto.php');
    /*use CommerceGuys\Addressing\Address;

    $address = new Address();
    $address = $address
        ->withCountryCode('IT')
        ->withAdministrativeArea('AQ')
        ->withLocality('Mountain View')
        ->withAddressLine1('1098 Alta Ave');

    $start = new DateTime('2024-09-15');
    $acc = new EAccommodation(1, [], "titolo", $address, 34, $start, null, 32, [], false, true, false, false, 1);

    print $acc;*/


    $FP=FPhoto::getInstance();
    $FA=FAccommodation::getInstance();

    //Exist

    $risultato = $FA->load(2);
    //$address = $risultato->getAddressLine1() . ", " . $risultato->getLocality() . ", " . $risultato->getPostalCode();
    print $risultato;

    //print_r($risultato);
    //print $risultato[0]->getId();
    
    /*if($Risultato == [])
    {
        echo 'L\'alloggio è nel DataBase!';
    }
    else
    {
        echo 'L\'alloggio non è nel DataBase!';
    }*/





    
    