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

    $ph1 = new EPhoto(13, 'o', 'accommodation', 2, null);

    $ris = $FP->delete(6);

    ($ris) ? print "Foto caricata con id:" : print "Errore nel caricamento della foto";

    







    
    