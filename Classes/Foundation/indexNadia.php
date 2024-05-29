<?php 

    #PARTE NADIA

    require __DIR__ . '/../../Tools/composer/vendor/autoload.php';

    require_once ('FAccommodation.php');
    require_once ('../Entity/EAccommodation.php');
    use CommerceGuys\Addressing\Address;
    use CommerceGuys\Addressing\Formatter\DefaultFormatter;
    use CommerceGuys\Addressing\AddressFormat\AddressFormatRepository;
    use CommerceGuys\Addressing\Country\CountryRepository;
    use CommerceGuys\Addressing\Subdivision\SubdivisionRepository;
 


    $address = new Address();
    $address = $address
        ->withCountryCode('IT')
        ->withAdministrativeArea('AQ')
        ->withLocality('Mountain View')
        ->withAddressLine1('1098 Alta Ave');

    //$start = new DateTime('2024-09-15');
    //$acc = new EAccommodation(1, [], "titolo", $address, 34, $start, null, 32, [], false, true, false, false, 1);

    /* Output:
    1098 Alta Ave
    MOUNTAIN VIEW, CA 94043
    ÉTATS-UNIS - UNITED STATES
    */

    // Creazione dei repository necessari
    $addressFormatRepository = new AddressFormatRepository();
    $countryRepository = new CountryRepository();
    $subdivisionRepository = new SubdivisionRepository();
    $formatter = new DefaultFormatter($addressFormatRepository, $countryRepository, $subdivisionRepository);

    // Formattazione dell'indirizzo senza HTML
    $formattedAddress = $formatter->format($address, ['html' => false]);

    // Restituisce una stringa che rappresenta l'accommodation con il suo indirizzo
    echo $formatter->format($address);



    
    