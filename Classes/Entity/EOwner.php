<?php
namespace Classes\Entity;
require __DIR__ . '../../../vendor/autoload.php';
use Classes\Entity\EPhoto;
use Classes\Foundation\FPersistentManager;


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
    private string $status;
    private static $entity = EOwner::class;
    /**
     * Undocumented function
     *
     * @return string
     */
    public function getEntity():string {
        return $this->entity;
    }
    /**
     * Undocumented function
     *
     * @param integer|null $id
     * @param string $username
     * @param string $password
     * @param string $name
     * @param string $surname
     * @param EPhoto|null $photo
     * @param string $email
     * @param integer $phonenumber
     * @param string $iban
     * @param string $status
     */
    public function __construct(?int $id, string $username, string $password, string $name, string $surname, ?EPhoto $photo, string $email, int $phonenumber, string $iban, string $status='active') {
        $this->id=$id;
        $this->username=$username;
        $this->password=EOwner::isPasswordHashed($password) ? $password : password_hash($password, PASSWORD_DEFAULT);
        //to verify if password is right in controller: password_verify()
        $this->name=ucfirst($name);
        $this->surname=ucfirst($surname);
        $this->photo = $photo;
        $this->email=$email; 
        $this->phoneNumber=$phonenumber;
        $this->iban = $iban;
        $this->status=$status;
    }
    private static function isPasswordHashed($password) {
        // The cost parameter of bcrypt is stored in the first 7 characters of the hash
        $isHashed = substr($password, 0, 7) == '$2y$10$';
        return $isHashed;
    }
    
    /**
     * Method formatPhoneNumber
     * 
     * this method return the phone number as an int
     * @param string $phone [explicite description]
     *
     * @return int
     */
    public static function formatPhoneNumber(string $phone):int
    {     
        $result_39=strncmp($phone,'+39',3);
        $result_0039=strncmp($phone,'0039',4);
        
        if($result_39===0)   #se inizia con +39
        {
            $phone=substr($phone,3);
            $phone=str_replace(' ','',$phone);
        }
        else
        {
            if($result_0039===0) #se inizia con 0039
            {
                $phone=substr($phone,4);
                $phone=str_replace(' ','',$phone);
            }
            else # altrimenti è senza prefisso
            {
                $phone=str_replace(' ','',$phone);
            }    
        }
        return (int)$phone;
    }
    
    /**
     * Undocumented function
     *
     * @return integer
     */
    public function getId():int {
        return $this->id;
    }
    /**
     * Undocumented function
     *
     * @return string
     */
    public function getUsername():string {
        return $this->username;
    }
    /**
     * Undocumented function
     *
     * @return string
     */
    public function getPassword():string {
        return $this->password;
    }
    public function getNumberOfAds():int {
        $PM = FPersistentManager::getInstance();
        $number=$PM->loadAccommodationsByOwner($this->id);
        return count($number);
    }
    public function getAverageRating() {
        $PM = FPersistentManager::getInstance();
        return $PM->getOwnerRating($this->id);
    }
    /**
     * Undocumented function
     *
     * @return string
     */
    public function getName():string {
        return $this->name;
    }
    public function getSurname():string {
        return $this->surname;
    }
    public function getPhoto():?EPhoto {
        return $this->photo;
    }
    public function getShowPhoto():?string {
        return $this->photo->getPhoto();
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

    public function getStatus():string {
        return $this->status;
    }

    public function setId(int $id):void {
        if ($this->id===null) {
            $this->id=$id;
        }
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

    public function setPhoto(?EPhoto $photo):void {
        $this->photo=$photo;
    }
    public function uploadPhoto(EPhoto $photo):void {
        if($this->photo===null) {
            $this->photo=$photo;
        } else {
            $this->photo->setPhoto($photo->getPhoto());
        }
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

    public function setStatus(string $status):void {
        $this->status=$status;
    }

    public function __toString():string
    {
        return 'ID: '. $this->id. ', Username: '. $this->username. ', Password: '. $this->password. ', Name: '. $this->name. ', Surname: '. $this->surname. ', E-Mail: '.$this->email.', Phone Number: '. $this->phoneNumber. ', IBAN: '.$this->iban . ', Status: '.$this->status;
    }
}