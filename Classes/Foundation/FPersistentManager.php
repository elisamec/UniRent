<?php

require_once ('FAccommodation.php');
require_once ('../Entity/EAccommodation.php');
class FPersistentManager {
    private static $instance;
    private function __construct(){}

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
     * @param String $EClass Refers to the Entity class of the object
     * @param int $id Refers to the id of the object
     * @return object
     */
    public static function load(String $EClass, int $id): object{
        
        $FClass = str_replace("E", "F", $EClass);

        $F = $FClass::getInstance();
        $result = $F->load($id);

        return $result;
    }

    /**
     * store an object in the database
     * 
     * @param object $obj Refers to the object to be stored
     * @return bool
     */
    public static function store(object $obj): bool{
        $EClass = get_class($obj); 
        $FClass = str_replace("E", "F", $EClass);

        $F = $FClass::getInstance();
        $result = $F->store($obj);

        return $result;
        
    }

    //update(E($class)), aggiorna l'oggetto nel db, @return bool;
    //delete(E($class)), cancella l'ogetto nel DB, @return bool;
    //getWaitingReservations(int $idProprietario), chiama il metodo relativo alla ricerca delle reservation in attesa di conferma relative a un proprietario, @return array<Reservation>
    //getAcceptedReservations(int $idProprietario), chiama il metodo relativo alla ricerca delle reservation accettate e in attesa di pagamento relative a un proprietario, @return array<Reservation>
    //

}