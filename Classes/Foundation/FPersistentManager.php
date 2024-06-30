<?php
namespace Classes\Foundation;
require __DIR__ .'/../../vendor/autoload.php';
use Classes\Foundation;
use Classes\Entity;
use Classes\Entity\EPhoto;
use Classes\Entity\EStudent;
use Classes\Utilities\UAccessUniversityFile;
use DateTime;

class FPersistentManager {
    private static $instance;
    private function __construct(){}

    public static function getInstance(){
        if (!self::$instance) {
            self::$instance = new FPersistentManager();
        }
        return self::$instance;
    }

    /**
     * return an object specifying the id 
     * @param string $EClass Refers to the Entity class of the object
     * @param int $id Refers to the id of the object
     * @return ?object
     */
    public static function load(String $EClass, int $id): ?object{
        
        $FClass = str_replace("E", "Classes\Foundation\F", $EClass);

        $F = $FClass::getInstance();
        $result = $F->load($id);

        return $result;
    }

    /**
     * store an object in the database
     * 
     * @param object $obj Refers to the object to be stored
     * @return bool
     */
    public static function store(object $obj): bool{
        $EClass = get_class($obj); 
        $FClass = str_replace("Classes\Entity\E", "Classes\Foundation\F", $EClass);

        $F = $FClass::getInstance();
        $result = $F->store($obj);

        return $result;
        
    }

    /**
     * update an object in the database
     * 
     * @param object $obj Refers to the object to be stored
     * @return bool
     */
    public static function update(object $obj): bool{
        $EClass = get_class($obj); 
        $FClass = str_replace("Classes\Entity\E", "Classes\Foundation\F", $EClass);

        $F = $FClass::getInstance();
        $result = $F->update($obj);

        return $result;
        
    }

    /**
     * delete an object in the database
     * 
     * @param string $EClass Refers to the Entity class of the object
     * @param int $id Refers to the object to be stored
     * @return bool
     */
    public static function delete(String $EClass, int $id): bool{

        $FClass = str_replace("E", "Classes\Foundation\F", $EClass);

        $F = $FClass::getInstance();
        $result = $F->delete($id);

        return $result;
        
    }

    //public function findAccommodation(DateTime $date,DateTime $fromDate, string $place), chiede query a FAccommodation, @return array


    public function getWaitingReservations(int $idProprietario):array
    {
        $FR= Foundation\FReservation::getInstance();
        $result=$FR->getWaitingReservations($idProprietario);
        return $result;
    }
    public function getAcceptedReservations(int $idProprietario):array
    {
        $FR= Foundation\FReservation::getInstance();
        $result=$FR->getAcceptedReservations($idProprietario);
        return $result;
    }
    
    /**
     * Method verifyStudentEmail
     * 
     * This method verify if the email is a student email
     * @param string $email
     *
     * @return bool
     */
    public function verifyStudentEmail(string $email):bool
    {
        $AUF=UAccessUniversityFile::getInstance();
        $mail_domain=substr(strrchr($email,"@student"),8);
        if(in_array($mail_domain,$AUF->getUniversityEmailList()))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function verifyUserEmail(string $email):bool
    {
        $FO=Foundation\FOwner::getInstance();
        $FS=Foundation\FStudent::getInstance();
        $resultS=$FS->verifyEmail($email);
        $resultO=$FO->verifyEmail($email);
        if($resultS==true || $resultO==true)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
        
    /**
     * Method verifyUserUsername
     *
     * this method return the type of the user and his id in the db
     * @param string $username [user's username]
     *
     * @return array|bool
     */
    public function verifyUserUsername(string $username):array|bool
    {
        $result=array();
        $FO=Foundation\FOwner::getInstance();
        $FS=Foundation\FStudent::getInstance();
        $resultS=$FS->verifyUsername($username);
        $resultO=$FO->verifyUsername($username);
        if($resultS!=false)
        {
            $result['type']='Student';
            $result['id']=$resultS;
            return $result;
        }
        elseif($resultO!=false)
        {
            $result['type']='Owner';
            $result['id']=$resultO;
            return $result;
        }
        else
        {
            return false;
        }
    }
    
    public function getStudentByUsername($user)
    {
        $FS=FStudent::getInstance();
        $student=$FS->getStudentByUsername($user);
        return $student;
    }

    public function getOwnerByUsername($user)
    {
        $FO=FOwner::getInstance();
        $owner=$FO->getOwnerByUsername($user);
        return $owner;
    }

    public function d($user):bool
    {
        $FS=FStudent::getInstance();
        $result=$FS->deleteStudentByUsername($user);
        return $result;
    }
    
    /**
     * Method getStudentIdByUsername
     *
     * This method return the id of a student in a database by username given
     * 
     * @param $user $user [student's username]
     *
     * @return int
     */    
    public function getStudentIdByUsername($user):int|bool
    {
        $FS=FStudent::getInstance();
        $id=$FS->getIdByUsername($user);
        return $id;
    }

    public function getStudentPhotoById(int $id):?EPhoto
    {
        $FP=FPhoto::getInstance();
        $photo=$FP->loadAvatar($id);
        return $photo;
    }
    
    /**
     * Method getStudentEmailByUsername
     * 
     * This method take the student's email and passes it to the control method modifyProfile of CStudent class
     * @param $user $user [student's username]
     *
     * @return string|bool
     */
    public function getStudentEmailByUsername($user):string|bool
    {
        $FS=FStudent::getInstance();
        $email=$FS->getEmailByUsername($user);
        return $email;
    }
    
    /**
     * Method deleteOwner
     * 
     * this method call the omonim function in FOwner and return the result to COwner class
     * @param $user $user [owner'username]
     *
     * @return void
     */
    public function deleteOwner($user):bool
    {
        $FO=FOwner::getInstance();
        $result=$FO->deleteOwnerByUsername($user);
        return $result;
    }
    
    /**
     * Method getOwnerIdByUsername
     *
     * this method return the owner id from the db using the given username
     * @param $user $user [owner's username]
     *
     * @return ?int
     */
    public function getOwnerIdByUsername($user):?int
    {
        $FO=FOwner::getInstance();
        $owner=$FO->getOwnerIdByUsername($user);
        return $owner;
    }

        
    /**
     * Method verifyPhoneNumber
     *
     * this method call the FOwner class to verify if the phone number given is already in use and return the answare to COwner class
     * @param $phone $phone [owner's phone number]
     *
     * @return bool
     */
    public function verifyPhoneNumber($phone):bool
    {
        $FO=FOwner::getInstance();
        $result=$FO->verifyPhoneNumber($phone);
        return $result;
    }

        
    /**
     * Method verifyIBAN
     * 
     * this method call the FOwner class to verify if the IBAN given is already in use and return the answare to COwner class
     * @param $iban $iban [owner's new iban]
     *
     * @return bool
     */
    public function verifyIBAN($iban):bool
    {
        $FO=FOwner::getInstance();
        $result=$FO->verifyIBAN($iban);
        return $result;
    }

}

