<?php 

    #PARTE NADIA

    namespace Classes\Foundation;

    require __DIR__ . '/../../vendor/autoload.php';

    use CommerceGuys\Addressing\Address;
    use Classes\Foundation\FAccommodation;
    use Classes\Foundation\FPersistentManager;
    use Classes\Entity\EAccommodation;
    use Classes\Foundation\FVisit;
    use Classes\Entity\EVisit;
    use Classes\Foundation\FPhoto;
    use Classes\Entity\EPhoto;
    use DateTime;
   

    $FP=FPersistentManager::getInstance();
    $FA=FAccommodation::getInstance();

    $a = new Address();
    $a = $a->withSortingCode(25)->withAddressLine1('Via Anna, 1')->withPostalCode('55555')->withLocality('Milano');
    $st = new DateTime('2021-06-01');

    $ph1 = new EPhoto(null, "foto1", "accommodation", null, null);
    $ph2 = new EPhoto(null, "fo1", "accommodation", null, null);
    $photo = [$ph1, $ph2];

    $visit = ['moday' => ["10:30", "11:20"], 'thursday' => ["20:40"]];

    $acc = new EAccommodation(null, $photo, "Casa", $a, 100, $st, "casetta bellissima v2", 100, $visit, 30, false, true, true, false, 2);

    //$risultato = $FA->update($acc);

    //$id = $acc->getIdAccommodation(); 

    $visit = new EVisit(6, new DateTime("2024-01-01 15:30"), 1, 21);

    //$risultato = $FP->update($visit);

    //$risultato = $FP->store($visit);

    //$risultato = $FP->delete("EVisit", 12);
    $risultato = $FP->load("EAccommodation", 21);

    print($risultato);



    







    
    