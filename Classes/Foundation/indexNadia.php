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
    use Classes\Entity\EOwner;
    use Classes\Foundation\FOwner;
    use Classes\Entity\EPhoto;
    use Classes\Entity\EStudent;
    use Classes\Control\CAdmin;
    use DateTime;
   

    /*$FP=FPersistentManager::getInstance();
    $FA=FAccommodation::getInstance();
    $FV=FVisit::getInstance();

    $a = new Address();
    $a = $a->withSortingCode(25)->withAddressLine1('Via Anna, 1')->withPostalCode('55555')->withLocality('Milano');
    $st = new DateTime('2021-06-01');

    $ph1 = new EPhoto(null, "foto1", "accommodation", null, null);
    $ph2 = new EPhoto(null, "fo1", "accommodation", null, null);
    $photo = [$ph1, $ph2];

    $visit = ['moday' => ["10:30", "11:20"], 'thursday' => ["20:40"]];

    $acc = new EAccommodation(14, $photo, "Casa", $a, 100, $st, "casetta bellissima v2", 3, 100, $visit, 30, false, true, true, false, true, 2);
*/
    //$risultato = $FA->update($acc);

    //$id = $acc->getIdAccommodation(); 

    //$visit = new EVisit(6, new DateTime("2024-01-01 15:30"), 1, 21);

    //$risultato = $FP->update($visit);

    //$risultato = $FP->store($visit);

    //$risultato = $FP->delete("EVisit", 12);
    //$risultato = $FP->load("EAccommodation", 21);

    //$times = ["11:30", "14:00"];

    //$student = new EStudent("nadia1", "Nadia123)", "Nadia", "Muzyka", null, "nadiam@student.univaq.it", 3, 2021, new DateTime("1999-06-01"), "F", false, false);

    /*$owner = new EOwner(null, "nadia1", "Nadia123)", "Nadia", "Muzyka", null, "muzykanadia0@gmail.com", "3333333333", "IT60X0542811101000000123456");
    $risultato = $FP->store($owner);

    $id = $owner->getId();

    $risultato = $FP->load("EOwner", $id);

    print($risultato);*/

    //$risultato = $FP->delete("EAccommodation", 18);

    /*$risultato = $FV -> loadFutreById(5, "accommodation");

    print_r ($risultato);*/

    $a = new CAdmin();

    //$a -> verifyEmail("nadia@student.univaq.com", "Univaq", "L'Aquila");



    







    
    