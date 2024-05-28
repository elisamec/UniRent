<?php
require_once('TErrorDuplicate.php');
class TError {
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

    public static function handleDuplicateError(PDOException $e): ?string {
        // Check the error code and message to determine the type of duplicate error
        if ($e->getCode() == 23000) { // 23000 is the SQLSTATE code for integrity constraint violation
            if (strpos($e->getMessage(), 'owner_username_unique') !== false) { // Assuming 'users_username_unique' is the name of the unique index on the username column
                return TErrorDuplicate::USERNAME->value;
            } elseif (strpos($e->getMessage(), 'owner_email_unique') !== false) { // Assuming 'users_email_unique' is the name of the unique index on the email column
                return TErrorDuplicate::EMAIL->value;
            } elseif (strpos($e->getMessage(), 'owner_phonenumber_unique') !== false) { // Assuming 'users_username_unique' is the name of the unique index on the username column
                return TErrorDuplicate::PHONENUMBER->value;
            } elseif (strpos($e->getMessage(), 'owner_iban_unique') !== false) { // Assuming 'users_email_unique' is the name of the unique index on the email column
                return TErrorDuplicate::IBAN->value;
            }
        }
        return null;
    }
}