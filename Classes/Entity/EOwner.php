<?php
namespace Classes\Entity;
require __DIR__ . '../../../vendor/autoload.php';
use Classes\Entity\EPhoto;
use Classes\Foundation\FPersistentManager;
use Classes\Tools\TStatusUser;

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
    private TStatusUser $status;
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

    /**
     * Retrieves the number of ads for the owner.
     *
     * @return int The number of ads for the owner.
     */
    public function getNumberOfAds():int {
        $PM = FPersistentManager::getInstance();
        $number=$PM->loadAccommodationsByOwner($this->id);
        return count($number);
    }

    /**
     * Retrieves the average rating of the owner.
     *
     * @return float The average rating of the owner.
     */
    public function getAverageRating() {
        $PM = FPersistentManager::getInstance();
        return $PM->getOwnerRating($this->id);
    }

    
    /**
     * Retrieves the name of the owner.
     *
     * @return string The name of the owner.
     */
    public function getName():string {
        return $this->name;
    }

    /**
     * Retrieves the surname of the owner.
     *
     * @return string The surname of the owner.
     */
    public function getSurname():string {
        return $this->surname;
    }

    /**
     * Retrieves the photo of the owner.
     *
     * @return EPhoto|null The photo of the owner.
     */
    public function getPhoto():?EPhoto {
        return $this->photo;
    }

    /**
     * Retrieves the photo of the owner.
     *
     * @return string|null The photo of the owner.
     */
    public function getShowPhoto():?string {
        return $this->photo->getPhoto();
    }

    /**
     * Retrieves the email of the owner.
     *
     * @return string The email of the owner.
     */
    public function getMail():string {
        return $this->email;
    }

    /**
     * Retrieves the phone number of the owner.
     *
     * @return string The phone number of the owner.
     */
    public function getPhoneNumber():string {
        return $this->phoneNumber;
    }

    /**
     * Retrieves the IBAN of the owner.
     *
     * @return string The IBAN of the owner.
     */
    public function getIBAN():string {
        return $this->iban;
    }

    /**
     * Retrieves the status of the owner.
     *
     * @return TStatusUser The status of the owner.
     */
    public function getStatus():TStatusUser {
        return $this->status;
    }

    /**
     * Retrieves the status of the owner.
     *
     * @return string The status of the owner.
     */
    public function getStatusString():string {
        return $this->status->value;
    }

    /**
     * Retrieves the status of the owner.
     *
     * @return string The status of the owner.
     */
    public function setId(int $id):void {
        if ($this->id===null) {
            $this->id=$id;
        }
    }


    /**
     * Set the username for the owner.
     *
     * @param string $username The username to set.
     * @return void
     */
    public function setUsername(string $username):void {
        $this->username=$username;
    }
    
    /**
     * Set the password for the owner.
     *
     * @param string $password The password to set.
     * @return void
     */
    public function setPassword(string $password):void {
        $this->password=EOwner::isPasswordHashed($password) ? $password : password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Set the name for the owner.
     *
     * @param string $name The name to set.
     * @return void
     */
    public function setName(string $name):void {
        $this->name=ucfirst($name);
    }

    /**
     * Set the surname for the owner.
     *
     * @param string $surname The surname to set.
     * @return void
     */
    public function setSurname(string $surname):void {
        $this->surname=ucfirst($surname);
    }

    /**
     * Set the photo for the owner.
     *
     * @param EPhoto|null $photo The photo to set.
     * @return void
     */
    public function setPhoto(?EPhoto $photo):void {
        $this->photo=$photo;
    }
    
    /**
     * Uploads a photo for the owner.
     *
     * @param EPhoto $photo The photo to be uploaded.
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
     * Set the email for the owner.
     *
     * @param string $email The email to set.
     * @return void
     */
    public function setMail(string $mail):void {
        $this->email=$mail;
    }

    /**
     * Set the phone number for the owner.
     *
     * @param string $phonenumber The phone number to set.
     * @return void
     */
    public function setPhoneNumber(string $phonenumber):void {
        $this->phoneNumber=$phonenumber;
    }

    /**
     * Set the IBAN for the owner.
     *
     * @param string $iban The IBAN to set.
     * @return void
     */
    public function setIBAN(string $iban):void {
        $this->iban=$iban;
    }

    /**
     * Set the status for the owner.
     *
     * @param TStatusUser|string $status The status to set.
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
     * Returns a string representation of the EOwner object.
     *
     * @return string The string representation of the EOwner object.
     */
    public function __toString():string
    {
        return 'ID: '. $this->id. ', Username: '. $this->username. ', Password: '. $this->password. ', Name: '. $this->name. ', Surname: '. $this->surname. ', E-Mail: '.$this->email.', Phone Number: '. $this->phoneNumber. ', IBAN: '.$this->iban . ', Status: '.$this->status->value;
    }
}