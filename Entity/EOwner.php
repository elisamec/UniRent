<<<<<<< HEAD
 <?php
 /**
  * EOwner
  *
  *This class depicts an owner
  */
 class EOwner 
 {    
    /**
     * id
     *
     * @var mixed
     */
    private $id;    
    /**
     * username
     *
     * @var mixed
     */
    private $username;    
    /**
     * name
     *
     * @var mixed
     */
    private $name;    
    /**
     * password
     *
     * @var mixed
     */
    private $password;    
    /**
     * picture
     *
     * @var mixed
     */
    private $picture;    
    /**
     * phoneNumber
     *
     * @var mixed
     */
    private $phoneNumber;    
    /**
     * email
     *
     * @var mixed
     */
    private $email;    
    /**
     * iban
     *
     * @var mixed
     */
    private $iban;
    
    /**
     * __construct
     *
     * @param  int $id
     * @param  string $username
     * @param  string $name
     * @param  string $password
     * @param  int $pictureID
     * @param  int $phone
     * @param  string $email
     * @param  string $iban
     * @return void
     */
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

    // GET  Methods  
    /**
     * getID
     *
     * @return int
     */
    public function getID():int
    {
        return $this->id;
    }    
    /**
     * getUserName
     *
     * @return string
     */
    public function getUserName():string
    {
        return $this->username;
    }    
    /**
     * getName
     *
     * @return string
     */
    public function getName():string
    {
        return $this->name;
    }    
    /**
     * getPassword
     *
     * @return string
     */
    public function getPassword():string
    {
        return $this->password;
    }    
    /**
     * getPhoto
     *
     * @return int
     */
    public function getPhoto():int
    {
        return $this->picture;
    }    
    /**
     * getPhoneNumber
     *
     * @return int
     */
    public function getPhoneNumber():int
    {
        return $this->phoneNumber;
    }    
    /**
     * getEmail
     *
     * @return string
     */
    public function getEmail():string
    {
        return $this->email;
    }    
    /**
     * getIBAN
     *
     * @return string
     */
    public function getIBAN():string
    {
        return $this->iban;
    }
// SET methods  
    /**
     * setID
     *
     * @param  int $id
     * @return void
     */
    public function setID(int $id):void
    {
        $this->id=$id;
    }    
    /**
     * setUserName
     *
     * @param  string $username
     * @return void
     */
    public function setUserName(string $username):void
    {
        $this->username=$username;
    }    
    /**
     * setName
     *
     * @param  string $name
     * @return void
     */
    public function setName(string $name):void
    {
        $this->name=$name;
    }    
    /**
     * setPassword
     *
     * @param  string $password
     * @return void
     */
    public function setPassword(string $password):void
    {
        $this->password=$password;
    }    
    /**
     * setPhoto
     *
     * @param  int $photoID
     * @return void
     */
    public function setPhoto(int $photoID):void
    {
        $this->picture=$photoID;
    }    
    /**
     * setPhoneNumber
     *
     * @param  int $phone
     * @return void
     */
    public function setPhoneNumber(int $phone):void
    {
        $this->phoneNumber=$phone;
    }    
    /**
     * setEmail
     *
     * @param  string $email
     * @return void
     */
    public function setEmail(string $email):void
    {
        $this->email=$email;
    }    
    /**
     * setIBAN
     *
     * @param  string $iban
     * @return void
     */
    public function setIBAN(string $iban):void
    {
        $this->iban=$iban;
    }
    
    
    /**
     * __toString
     *
     * @return string
     */
    public function __toString():string
    {
        return 'ID:'.$this->id.' NAME:'.$this->name.' USERNAME:'.$this->username.' PASSWORD:'.$this->password.' PICTURE:'.$this->picture.' PHONE:'.$this->phoneNumber.' EMAIL:'.$this->email.' IBAN:'.$this->iban;
    }

 }
=======
<?php

class EOwner
{
    private int $idOwner;
    private string $username;
    private string $password;
    private string $name;
    private string $surname;
    private EPhoto $photo;
    private string $email;
    private int $phoneNumber;
    private string $iban;

}
>>>>>>> 638382a3e175c81b9ad00ef9a87fde8a9439f933
