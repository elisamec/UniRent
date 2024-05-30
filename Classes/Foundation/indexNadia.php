<?php 

    #PARTE NADIA

    require __DIR__ . '/../../vendor/autoload.php';

    require_once ('FAccommodation.php');
    require_once ('../Entity/EAccommodation.php');
    require_once ('FVisit.php');
    require_once ('../Entity/EVisit.php');
    require_once ('FPhoto.php');
    require_once ('../Entity/EPhoto.php');
    use CommerceGuys\Addressing\Address;

    $FP=FPhoto::getInstance();
    $FA=FAccommodation::getInstance();

    $risultato = $FP->loadAvatar(1);

    //$risultato = new EPhoto(1, 'foto', 'other', null, null);
    print $risultato;







    
    