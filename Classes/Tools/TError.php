<?php
namespace Classes\Tools;
require __DIR__.'../../../vendor/autoload.php';
use Classes\Tools\ErrorDuplicate;
use PDOException;

class TError {
    /**Instance of the class */

    private static $instance=null;
    /**Constructor */
    private function __construct()
    {}
    /**This static method gives the istance of this singleton class
     * @return  
    */
    public static function getInstance()
    {
        if(is_null(self::$instance))
        {
            self::$instance = new TError();
        }
        return self::$instance;
    }

    /**
     * This method handles the duplicate error
     * @param PDOException $e
     * @return string|null
     */
    public static function handleDuplicateError(PDOException $e): ?string {
        // Check the error code and message to determine the type of duplicate error
        if ($e->getCode() == 23000) { // 23000 is the SQLSTATE code for integrity constraint violation
            if (strpos($e->getMessage(), 'for key \'username\'') !== false) { 
                return TErrorDuplicate::USERNAME->value;
            } elseif (strpos($e->getMessage(), 'for key \'email\'') !== false) { 
                return TErrorDuplicate::EMAIL->value;
            } elseif (strpos($e->getMessage(), 'for key \'phonenumber\'') !== false) {
                return TErrorDuplicate::PHONENUMBER->value;
            } elseif (strpos($e->getMessage(), 'for key \'iban\'') !== false) { 
                return TErrorDuplicate::IBAN->value;
            }
        }
        return null;
    }

    /**
     * This method handles the error when the user tries to modify a reservation that has a contract
     * @return bool
     */
    public static function modificationReservationHendler():bool
    {
        echo 'You can not modify the reservation becase a contract exists!';
        return false;
    }
    /**
     * This method handles the error when the user tries to delete a reservation that has a contract
     * @return bool
     */
    public static function deleteReservationHendler():bool
    {
        echo 'You can not delete the reservation becase a contract exists!';
        return false;
    }
    /**
     * This method handles the error when the user tries to modify a reservation that has been accepted
     * @return bool
     */
    public static function modificationAfterAccept():bool
    {
        echo 'You can not modify the reservation becase the owner has accepted the reservation!';
        return false;
    }
    /**
     * This method handles the error when the user tries to delete a reservation that has been accepted
     * @return bool
     */
    public static function errorGettingReservations():array
    {
        echo 'Someting went wrong in getting reservations!';
        return array();
    }

}