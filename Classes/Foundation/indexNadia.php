<?php 

    #PARTE NADIA

    require __DIR__ . '/../../vendor/autoload.php';

    require_once ('FAccommodation.php');
    require_once ('../Entity/EAccommodation.php');
    use CommerceGuys\Addressing\Address;

    $address = new Address();
    $address = $address
        ->withCountryCode('IT')
        ->withAdministrativeArea('AQ')
        ->withLocality('Mountain View')
        ->withAddressLine1('1098 Alta Ave');

    $start = new DateTime('2024-09-15');
    $acc = new EAccommodation(1, [], "titolo", $address, 34, $start, null, 32, [], false, true, false, false, 1);

    print $acc;


    // Restituisce una stringa che rappresenta l'accommodation con il suo indirizzo
    //echo $formatter->format($address);



    
    