<?php 
 class EOwner 
 {
    private $id;
    private $username;
    private $name;
    private $password;
    private $picture;
    private $phoneNumber;
    private $email;
    private $iban;

    public function __construct(int $id , string $username, string $name, string $password, int $pictureID, int $phone , string $email, string $iban )
    {
        $this->id=$id;
        $this->username=$username;
        $this->name=$name;
        $this->password=$password;
        $this->picture=$pictureID;
        $this->phoneNumber=$phone;
        $this->email=$email;
        $this->iban=$iban;
    }

    //Metodi GET
    public function getID():int
    {
        return $this->id;
    }
    public function getUserName():string
    {
        return $this->username;
    }
    public function getName():string
    {
        return $this->name;
    }
    public function getPassword():string
    {
        return $this->password;
    }
    public function getPhoto():int
    {
        return $this->picture;
    }
    public function getPhoneNumber():int
    {
        return $this->phoneNumber;
    }
    public function getEmail():string
    {
        return $this->email;
    }
    public function getIBAN():string
    {
        return $this->iban;
    }
// Metodi SET
    public function setID(int $id):void
    {
        $this->id=$id;
    }
    public function setUserName(string $username):void
    {
        $this->username=$username;
    }
    public function setName(string $name):void
    {
        $this->name=$name;
    }
    public function setPassword(string $password):void
    {
        $this->password=$password;
    }
    public function setPhoto(int $photoID):void
    {
        $this->picture=$photoID;
    }
    public function setPhoneNumber(int $phone):void
    {
        $this->phoneNumber=$phone;
    }
    public function setEmail(string $email):void
    {
        $this->email=$email;
    }
    public function setIBAN(string $iban):void
    {
        $this->iban=$iban;
    }
    

    public function __toString():string
    {
        return 'ID:'.$this->id.' NAME:'.$this->name.' USERNAME:'.$this->username.' PASSWORD:'.$this->password.' PICTURE:'.$this->picture.' PHONE:'.$this->phoneNumber.' EMAIL:'.$this->email.' IBAN:'.$this->iban;
    }

 }