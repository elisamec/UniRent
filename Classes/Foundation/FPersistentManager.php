<?php

require_once ('FAccommodation.php');
require_once ('../Entity/EAccommodation.php');
class FPersistentManager {
    private static $instance;
    private function __construct()
    {
        
    }
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new FPersistentManager();
        }
        return self::$instance;
    }
    //findAccommodation(DateTime fromDate, string place), chiede query a FAccommodation, @return array

    /**
     * return an object specifying the id 
     * @param String $Eclass Refers to the Entity class of the object
     * @param int $id Refers to the id of the object
     * @return object
     */
    public static function load(String $Eclass, int $id): object{
        
        $foundClass = str_replace("E", "F", $Eclass);

        $F = $foundClass::getInstance();

        $result = $F->load($id);

        return $result;
    }


    //store(E($class)), salva l'oggetto nel DB, @return bool;
    //update(E($class)), aggiorna l'oggetto nel db, @return bool;
    //delete(E($class)), cancella l'ogetto nel DB, @return bool;
    //getWaitingReservations(int $idProprietario), chiama il metodo relativo alla ricerca delle reservation in attesa di conferma relative a un proprietario, @return array<Reservation>
    //getAcceptedReservations(int $idProprietario), chiama il metodo relativo alla ricerca delle reservation accettate e in attesa di pagamento relative a un proprietario, @return array<Reservation>
    //

}