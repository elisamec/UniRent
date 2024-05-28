<?php

require_once ('EPhoto.php');


class EOwner
{
    private ?int $id;
    private string $username;
    private string $password;
    private string $name;
    private string $surname;
    private ?EPhoto $photo;
    private string $email;
    private string $phoneNumber;
    private string $iban;
    private static $entity = EOwner::class;
    
    public function getEntity():string {
        return $this->entity;
    }

    public function __construct(?int $id, string $username, string $password, string $name, string $surname, ?EPhoto $photo, string $email, int $phonenumber, string $iban) {
        $this->id=$id;
        $this->username=$username;
        $this->password=password_get_info($password)['algoName'] !== PASSWORD_DEFAULT ? password_hash($password, PASSWORD_DEFAULT) : $password;
        //to verify if password is right in controller: password_verify()
        $this->name=ucfirst($name);
        $this->surname=ucfirst($surname);
        $this->photo = $photo;
        $this->email=$email; 
        $this->phoneNumber=$phonenumber;
        $this->iban = $iban;
    }
    
    public function getId():int {
        return $this->id;
    }
    public function getUsername():string {
        return $this->username;
    }
    
    public function getPassword():string {
        return $this->password;
    }
    public function getName():string {
        return $this->name;
    }
    public function getSurname():string {
        return $this->surname;
    }
    public function getPhoto():?EPhoto {
        return $this->photo;
    }
    public function getMail():string {
        return $this->email;
    }
    public function getPhoneNumber():string {
        return $this->phoneNumber;
    }
    public function getIBAN():string {
        return $this->iban;
    }

    public function setId(int $id):void {
        $this->id = $id;
    }
    public function setUsername(string $username):void {
        $this->username=$username;
    }
    
    public function setPassword(string $password):void {
        $this->password=password_hash($password, PASSWORD_DEFAULT);
    }
    public function setName(string $name):void {
        $this->name=ucfirst($name);
    }
    public function setSurname(string $surname):void {
        $this->surname=ucfirst($surname);
    }
    public function uploadPhoto(EPhoto $photo):void {
        $this->photo=$photo;
    }
    public function setMail(string $mail):void {
        $this->email=$mail;
    }
    public function setPhoneNumber(string $phonenumber):void {
        $this->phoneNumber=$phonenumber;
    }
    public function setIBAN(string $iban):void {
        $this->iban=$iban;
    }
    
}