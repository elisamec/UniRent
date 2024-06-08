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

    $a = new Address();
    $a = $a->withSortingCode(25)->withAddressLine1('Via Anna, 1')->withPostalCode('55555')->withLocality('Milano');
    $st = new DateTime('2021-06-01');

    $ph1 = new EPhoto(null, "foto1", "accommodation", null, null);
    $ph2 = new EPhoto(null, "fo1", "accommodation", null, null);
    $photo = [$ph1, $ph2];

    $visit = ['moday' => ["10:30", "11:20"], 'thursday' => ["20:40"]];

    $acc = new EAccommodation(21, $photo, "Casa", $a, 100, $st, "casetta bellissima v2", 100, $visit, 30, false, true, true, false, 2);

    $risultato = $FA->update($acc);

    //$id = $acc->getIdAccommodation(); 

    $risultato = $FA->load(21);

    print($risultato);



    







    
    