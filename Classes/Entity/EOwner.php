<?php
namespace Classes\Entity;
require __DIR__ . '../../../vendor/autoload.php';
use Classes\Entity\EPhoto;
use Classes\Foundation\FPersistentManager;
use Classes\Tools\TStatusUser;

class EOwner
{
    /**
     * 
     * @var ?int $id The unique identifier of the owner.
     * @var string $username The username of the owner.
     * @var string $password The password of the owner.
     * @var string $name The name of the owner.
     * @var string $surname The surname of the owner.
     * @var ?EPhoto $photo The photo of the owner.
     * @var string $email The email of the owner.
     * @var string $phoneNumber The phone number of the owner.
     * @var string $iban The IBAN of the owner.
     * @var TStatusUser $status The status of the owner.
     * @var string $entity The entity of the owner.
     */
    private ?int $id;
    private string $username;
    private string $password;
    private string $name;
    private string $surname;
    private ?EPhoto $photo;
    private string $email;
    private string $phoneNumber;
    private string $iban;
    private TStatusUser $status;
    private static $entity = EOwner::class;
    /**
     * this method is used to get the entity
     *
     * @return string
     */
    public function getEntity():string {
        return $this->entity;
    }
    /**
     * this method is used to create a new owner
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
     * @param TStatusUser $status
     */
    public function __construct(?int $id, string $username, string $password, string $name, string $surname, ?EPhoto $photo, string $email, int $phonenumber, string $iban, TStatusUser|string $statusStr='active') {
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
        if (is_string($statusStr)) {
            $this->status=TStatusUser::tryFrom($statusStr);
        } else {
            $this->status=$statusStr;
        }
    }
    /**
     * Method isPasswordHashed
     * 
     * this method return true if the password is hashed
     * @param string $password
     *
     * @return bool
     */
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
     * Method getId
     * 
     * This method is used to get the id of the owner
     *
     * @return integer
     */
    public function getId():int {
        return $this->id;
    }
    /**
     * Method getUsername
     * 
     * This method is used to get the username of the owner
     * 
     * @return string
     */
    public function getUsername():string {
        return $this->username;
    }
    /**
     * method getPassword
     * 
     * This method is used to get the password of the owner
     *
     * @return string
     */
    public function getPassword():string {
        return $this->password;
    }
    /**
     * Method getNumberOfAds
     * 
     * This method is used to get the number of ads of the owner
     *
     * @return integer
     */
    public function getNumberOfAds():int {
        $PM = FPersistentManager::getInstance();
        $number=$PM->loadAccommodationsByOwner($this->id);
        return count($number);
    }
    /**
     * Method getAverageRating
     * 
     * This method is used to get the average rating of the owner
     *
     * @return float
     */
    public function getAverageRating() {
        $PM = FPersistentManager::getInstance();
        return $PM->getOwnerRating($this->id);
    }
    /**
     * Method getName
     * 
     * This method is used to get the name of the owner
     *
     * @return string
     */
    public function getName():string {
        return $this->name;
    }
    /**
     * Method getSurname
     * 
     * This method is used to get the surname of the owner
     *
     * @return string
     */
    public function getSurname():string {
        return $this->surname;
    }
    /**
     * Method getPhoto
     * 
     * This method is used to get the photo of the owner
     *
     * @return EPhoto|null
     */
    public function getPhoto():?EPhoto {
        return $this->photo;
    }
    /**
     * Method getShowPhoto
     * 
     * This method is used to get the photo of the owner in the correct way
     *
     * @return string|null
     */
    public function getShowPhoto():?string {
        return $this->photo->getPhoto();
    }
    /**
     * Method getMail
     * 
     * This method is used to get the mail of the owner
     *
     * @return string
     */
    public function getMail():string {
        return $this->email;
    }
    /**
     * Method getPhoneNumber
     * 
     * This method is used to get the phone number of the owner
     *
     * @return string
     */
    public function getPhoneNumber():string {
        return $this->phoneNumber;
    }
    /**
     * Method getIBAN
     * 
     * This method is used to get the IBAN of the owner
     *
     * @return string
     */
    public function getIBAN():string {
        return $this->iban;
    }

    /**
     * Method getStatus
     * 
     * This method is used to get the status of the owner
     *
     * @return TStatusUser
     */
    public function getStatus():TStatusUser {
        return $this->status;
    }
    /**
     * Method getStatusString
     * 
     * This method is used to get the status of the owner in the correct way
     *
     * @return string
     */
    public function getStatusString():string {
        return $this->status->value;
    }

    /**
     * Method setId
     * 
     * This method is used to set the id of the owner
     *
     * @param integer $id
     *
     * @return void
     */
    public function setId(int $id):void {
        if ($this->id===null) {
            $this->id=$id;
        }
    }
    /**
     * Method setUsername
     * 
     * This method is used to set the username of the owner
     *
     * @param string $username
     *
     * @return void
     */
    public function setUsername(string $username):void {
        $this->username=$username;
    }
    /**
     * Method setPassword
     * 
     * This method is used to set the password of the owner
     *
     * @param string $password
     *
     * @return void
     */
    public function setPassword(string $password):void {
        $this->password=password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Method setName
     * 
     * This method is used to set the name of the owner
     *
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name):void {
        $this->name=ucfirst($name);
    }
    /**
     * Method setSurname
     * 
     * This method is used to set the surname of the owner
     *
     * @param string $surname
     *
     * @return void
     */
    public function setSurname(string $surname):void {
        $this->surname=ucfirst($surname);
    }
    /**
     * Method setPhoto
     * 
     * This method is used to set the photo of the owner
     *
     * @param EPhoto|null $photo
     *
     * @return void
     */
    public function setPhoto(?EPhoto $photo):void {
        $this->photo=$photo;
    }
    /**
     * Method uploadPhoto
     * 
     * This method is used to upload the photo of the owner
     *
     * @param EPhoto $photo
     *
     * @return void
     */
    public function uploadPhoto(EPhoto $photo):void {
        if($this->photo===null) {
            $this->photo=$photo;
        } else {
            $this->photo->setPhoto($photo->getPhoto());
        }
    }
    /**
     * Method setMail
     * 
     * This method is used to set the mail of the owner
     *
     * @param string $mail
     *
     * @return void
     */
    public function setMail(string $mail):void {
        $this->email=$mail;
    }
    /**
     * Method setPhoneNumber
     * 
     * This method is used to set the phone number of the owner
     *
     * @param string $phonenumber
     *
     * @return void
     */
    public function setPhoneNumber(string $phonenumber):void {
        $this->phoneNumber=$phonenumber;
    }
    /**
     * Method setIBAN
     * 
     * This method is used to set the IBAN of the owner
     *
     * @param string $iban
     *
     * @return void
     */
    public function setIBAN(string $iban):void {
        $this->iban=$iban;
    }
    /**
     * Method setStatus
     * 
     * This method is used to set the status of the owner
     *
     * @param TStatusUser|string $status
     *
     * @return void
     */
    public function setStatus(TStatusUser | string $status):void {
        if (is_string($status)) {
            $this->status=TStatusUser::tryFrom($status);
        } else {
            $this->status=$status;
        }
    }
    /**
     * Method __toString
     * 
     * This method is used to get the owner as a string
     *
     * @return string
     */
    public function __toString():string
    {
        return 'ID: '. $this->id. ', Username: '. $this->username. ', Password: '. $this->password. ', Name: '. $this->name. ', Surname: '. $this->surname. ', E-Mail: '.$this->email.', Phone Number: '. $this->phoneNumber. ', IBAN: '.$this->iban . ', Status: '.$this->status;
    }
}