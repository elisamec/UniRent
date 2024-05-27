<?php

require_once ('EPhoto.php');
require __DIR__ . '/vendor/autoload.php';
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;



class EOwner
{
    private ?int $id;
    private string $username;
    private string $password;
    private string $name;
    private string $surname;
    private ?EPhoto $photo;
    private string $email;
    private int $phoneNumber;
    private string $iban;
    private static $entity = EOwner::class;

    public function __construct(?int $id, string $username, string $password, string $name, string $surname, ?EPhoto $photo, string $email, int $phonenumber, string $iban) {
        $this->id=$id;
        $this->username=$username;

    }
    private static function Validate(string $mail):bool {
        $validator = new EmailValidator();
        return $validator->isValid("example@example.com", new RFCValidation());
    }
}