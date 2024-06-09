<?php
namespace Classes\Foundation;
require __DIR__ .'/../../vendor/autoload.php';
use Classes\Foundation;
use Classes\Entity;

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

    /**
     * return an object specifying the id 
     * @param string $EClass Refers to the Entity class of the object
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
        $FClass = str_replace("Classes\Entity\E", "Classes\Foundation\F", $EClass);

        $F = $FClass::getInstance();
        $result = $F->store($obj);

        return $result;
        
    }

    /**
     * update an object in the database
     * 
     * @param object $obj Refers to the object to be stored
     * @return bool
     */
    public static function update(object $obj): bool{
        $EClass = get_class($obj); 
        $FClass = str_replace("E", "F", $EClass);

        $F = $FClass::getInstance();
        $result = $F->update($obj);

        return $result;
        
    }

    /**
     * delete an object in the database
     * 
     * @param String $EClass Refers to the Entity class of the object
     * @param int $id Refers to the object to be stored
     * @return bool
     */
    public static function delete(String $EClass, int $id): bool{

        $FClass = str_replace("E", "F", $EClass);

        $F = $FClass::getInstance();
        $result = $F->delete($id);

        return $result;
        
    }

    //findAccommodation(DateTime fromDate, string place), chiede query a FAccommodation, @return array
    //getWaitingReservations(int $idProprietario), chiama il metodo relativo alla ricerca delle reservation in attesa di conferma relative a un proprietario, @return array<Reservation>
    //getAcceptedReservations(int $idProprietario), chiama il metodo relativo alla ricerca delle reservation accettate e in attesa di pagamento relative a un proprietario, @return array<Reservation>
    //

}