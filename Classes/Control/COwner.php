<?php
require __DIR__ . '/vendor/autoload.php';
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
class COwner {
    private static $instance=null;

    private function __construct()
    {}
    public static function getInstance() {
        if(is_null(self::$instance))
        {
            self::$instance = new COwner();
        }
        return self::$instance;
    }
    public static function registration():void {
        
    }
    private static function ValidateMail(string $mail):bool {
        $validator = new EmailValidator();
        return $validator->isValid($mail, new RFCValidation());
    }
    private static function ValidateIban(string $iban):bool {
        return verify_iban($iban);
    }
}