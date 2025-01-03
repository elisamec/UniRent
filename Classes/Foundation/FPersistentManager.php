<?php
namespace Classes\Foundation;
require __DIR__ .'/../../vendor/autoload.php';
use Classes\Entity\EReservation;
use Classes\Foundation;
use Classes\Entity;
use Classes\Entity\ECreditCard;
use Classes\Entity\EOwner;
use Classes\Entity\EPhoto;
use Classes\Entity\EReport;
use Classes\Entity\EStudent;
use Classes\Tools\TType;
use Classes\Utilities\UAccessUniversityFile;
use Classes\Foundation\FUpdater;
use Classes\View\VError;
use Classes\Foundation\FReport;
use Classes\Tools\TStatusSupport;
use Classes\Utilities\USort;
use DateTime;


/**
 * Class FPersistentManager
 *
 * This class is responsible for managing the persistence of objects.
 * It provides methods for storing and retrieving objects from a data source.
 */
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
    public function load(String $EClass, int $id): ?object{
        
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
    public function store(object $obj): bool{
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
    public function update(object $obj): bool{

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
    public function delete(String $EClass, int $id): bool{

        $FClass = str_replace("E", "Classes\Foundation\F", $EClass);

        $F = $FClass::getInstance();
        $result = $F->delete($id);

        return $result;
        
    }


    /**
     * Retrieves an array of waiting reservations for a given proprietor.
     *
     * @param int $idProprietario The ID of the proprietor.
     * @return array An array of waiting reservations.
     */
    public  function getWaitingReservations(int $idProprietario):array
    {
        $FR= Foundation\FReservation::getInstance();
        $result=$FR->getWaitingReservations($idProprietario);
        return $result;
    }
    
    /**
     * Method verifyStudentEmail
     * 
     * This method verify if the email is a student email
     * @param string $email
     *
     * 
     */
    public function verifyStudentEmail(string $email)
    {
        $AUF=UAccessUniversityFile::getInstance();
        
        $email = explode(".", str_replace("@", ".", $email));
        $domain = array_slice($email, -2);
        $mail_domain = ".".$domain[0] . "." . $domain[1];
        if(in_array($mail_domain,$AUF->getUniversityEmailList()))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Verifies the user's email.
     *
     * @param string $email The email to be verified.
     * @return bool Returns true if the email is verified, false otherwise.
     */
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
    
    /**
     * Retrieves a student by their username.
     *
     * @param string $user The username of the student.
     * @return EStudent|null The student object if found, null otherwise.
     */
    public function getStudentByUsername($user) : ?EStudent
    {
        $FS=FStudent::getInstance();
        $student=$FS->getStudentByUsername($user);
        return $student;
    }

    /**
     * Retrieves the owner by their username.
     *
     * @param string $user The username of the owner.
     * @return EOwner|null The owner object if found, null otherwise.
     */
    public function getOwnerByUsername($user) : ?EOwner
    {
        $FO=FOwner::getInstance();
        $owner=$FO->getOwnerByUsername($user);
        return $owner;
    }

    /**
     * Deletes a student from the database by their username.
     *
     * @param string $user The username of the student to be deleted.
     * @return bool Returns true if the student was successfully deleted, false otherwise.
     */
    public function deleteStudentByUsername($user):bool
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
    public function getUsernameById(int $id, TType $type):?string
    {
        if ($type==TType::STUDENT)
        {
            $FS=FStudent::getInstance();
            $username=$FS->getUsernameById($id);
            return $username;
        }
        else
        {
            $FO=FOwner::getInstance();
            $username=$FO->getUsernameById($id);
            return $username;
        }
    }

    /**
     * Retrieves the student photo by the given ID.
     *
     * @param int $id The ID of the student.
     * @return EPhoto|null The student photo object, or null if not found.
     */
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
     * Method getStudentPhotoId
     * 
     * This method take the student's photo id 
     * @param $user student's username
     *
     * @return int|bool
     */
    public function getStudentPhotoId($user):?int
    {
        $FS=FStudent::getInstance();
        $photoId=$FS->getPhotoIdByUsername($user);
        return $photoId;
    }

    /**
     * Method getStudentPhotoId
     * 
     * This method take the student's photo id 
     * @param $user student's username
     *
     * @return int|bool
     */
    public function getOwnerPhotoId($user):?int
    {
        $FO=FOwner::getInstance();
        $photoId=$FO->getPhotoIdByUsername($user);
        return $photoId;
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
     * Method getUsernameByOwnerId
     *
     * this method return the owner's username from the db using the given id
     * @param int $id [owner's id]
     *
     * @return ?string
     */
    public function getUsernameByOwnerId(int $id):?string
    {
        $FO=FOwner::getInstance();
        $owner=$FO->getUsernameByOwnerId($id);
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

    /**
     * Stores the avatar photo.
     *
     * @param EPhoto $photo The photo object to be stored.
     * @return bool Returns true if the avatar photo is successfully stored, false otherwise.
     */
    public function storeAvatar(EPhoto $photo) :bool {
        $FP=FPhoto::getInstance();
        $result=$FP->storeAvatar($photo);
        return $result;
    }

    /**
     * Loads data by recipient.
     *
     * @param int $id The ID of the recipient.
     * @param mixed $type The type of the recipient.
     * @return array The loaded data.
     */
    public function loadByRecipient(int $id , $type):array
    {
        $FR=FReview::getInstance();
        $review=$FR->loadByRecipient($id,$type);
        return $review;
    }
    
    /**
     * Method loadStudentCards
     *
     * this method retrive an array of ECreditCard of the student with given id from DB
     * @param int $id [student's ID]
     *
     * @return array
     */
    public function loadStudentCards(int $id):array
    {
        $FC=FCreditCard::getInstance();
        $cards=$FC->loadStudentCards($id);
        return $cards;
    }
    
    /**
     * Method existsTheCard
     *
     * this method return if the card is already in DB
     * @param string $number [card number to verify]
     *
     * @return bool
     */
    public function existsTheCard(string $number):bool
    {
        $FC=FCreditCard::getInstance();
        $result=$FC->exist($number);
        return $result;
    }

    /**
     * Deletes a credit card from the persistent storage.
     *
     * @param string $number The credit card number to delete.
     * @return bool Returns true if the credit card was successfully deleted, false otherwise.
     */
    public function deleteCreditCard(string $number):bool
    {
        $FC=FCreditCard::getInstance();
        $result=$FC->delete($number);
        return $result;
    }

    /**
     * Checks if a given card number is the main card for a student.
     *
     * @param int $studentId The ID of the student.
     * @param string $number The card number to check.
     * @return bool Returns true if the card number is the main card for the student, false otherwise.
     */
    public function isMainCard(int $studentId, string $number):bool
    {
        $FC=FCreditCard::getInstance();
        $result=$FC->isMain($studentId,$number);
        return $result;
    }
    
    /**
     * Method loadCreditCard
     *
     * this method load by the DB a ECreditCard object
     * @param string $number [credit card's number]
     *
     * @return ECreditCard
     */
    public function loadCreditCard(string $number):ECreditCard
    {
        $FC=FCreditCard::getInstance();
        $result=$FC->load($number);
        return $result;
    }
    
    /**
     * Method getStudentMainCard
     *
     * @param int $studentId 
     *
     * @return ECreditCard
     */
    public function getStudentMainCard(int $studentId):?ECreditCard
    {
        $FC=FCreditCard::getInstance();
        $result=$FC->getMainCard($studentId);
        return $result;
    }
    
    /**
     * Method findAccommodations
     *
     * @param string $city 
     * @param string $date 
     *
     * @return array
     */
    public function findAccommodationsUser($city,$date,$rateA,$rateO,$minPrice,$maxPrice,$year):array
    {
        $FA=FAccommodation::getInstance();
        $result=$FA->findAccommodationsUser($city,$date,$rateA,$rateO,$minPrice,$maxPrice,$year);
        return $result;
    }

    /**
     * Method findAccommodationsStudent
     * 
     * this method call the omonim method in FAccommodation to find the accommodations for the student
     * @param  $city
     * @param  $date
     * @param  $rateA
     * @param  $rateO
     * @param  $minPrice
     * @param  $maxPrice
     * @param  $student
     * @param  $year
     * @return array
     */
    public function findAccommodationsStudent($city,$date,$rateA,$rateO,$minPrice,$maxPrice,$student,$year):array
    {
        $FA=FAccommodation::getInstance();
        $result=$FA->findAccommodationsStudent($city,$date,$rateA,$rateO,$minPrice,$maxPrice,$student,$year);
        return $result;
    }


    /**
     * Retrieves the last accommodations for a user.
     *
     * @return array An array containing the last accommodations for a user.
     */
    public function lastAccommodationsUser():array
    {
        $FA=FAccommodation::getInstance();
        $result=$FA->lastAccommodationsUser();
        return $result;
    }

    /**
     * Retrieves the last accommodations of a student.
     *
     * @param EStudent $student The student object.
     * @return array An array of last accommodations.
     */
    public function lastAccommodationsStudent(EStudent $student):array
    {
        $FA=FAccommodation::getInstance();
        $result=$FA->lastAccommodationsStudent($student);
        return $result;
    }

    /**
     * Loads reviews by author.
     *
     * @param int $idAuthor The ID of the author.
     * @param TType $type The type of reviews to load.
     * @return array The array of loaded reviews.
     */
    public function loadReviewsByAuthor(int $idAuthor, TType $type): array {
        $FReview = FReview::getInstance();
        $reviews = $FReview->loadByAuthor($idAuthor, $type);
        return $reviews;
    }

    /**
     * Loads accommodations by owner.
     *
     * @param int $idOwner The ID of the owner.
     * @return array|null An array of accommodations or null if no accommodations found.
     */
    public function loadAccommodationsByOwner(int $idOwner): ?array {
        $FA = FAccommodation::getInstance();
        $accommodations = $FA->loadByOwner($idOwner);
        return $accommodations;
    }

    /**
     * Retrieves the owner rating for a given ID.
     *
     * @param int $id The ID of the owner.
     * @return int The owner rating.
     */
    public function getOwnerRating($id):int
    {
        $result=FOwner::findOwnerRating($id);
        return $result;
    }

    /**
     * Retrieves the rating of an accommodation.
     *
     * @param int $id The ID of the accommodation.
     * @return int The rating of the accommodation.
     */
    public function getAccommodationRating(int $id):int
    {
        $FA=FAccommodation::getInstance();
        $result=$FA->findAccommodationRating($id);
        return $result;
    }

    /**
     * Retrieves the rating of a student.
     *
     * @param int $id The ID of the student.
     * @return int The rating of the student.
     */
    public function getStudentRating(int $id):int
    {
        $FS=FStudent::getInstance();
        $result=$FS->findStudentRating($id);
        return $result;
    }
    
    /**
     * Method getTenants
     *
     * this method is used to get the tenants of the owner 
     * is used in home page by the owner
     * @param string $type [explicite description]
     * @param int $idOwner [explicite description]
     *
     * @return array
     */
    public function getTenants(string $type, int $idOwner):array
    {
        $FO=FOwner::getInstance();
        if ($type=='current')
        {
            $type='onGoing';
        } else if ($type=='past')
        {
            $type='finished';
        }
        $result=$FO->getTenans($type,$idOwner);
        return $result;
    }


    /**
     * Retrieves the user type for a given ID.
     *
     * @param int $id The ID of the user.
     * @return TType The user type.
     */
    public function getUserType($id):TType
    {
        $FO=FOwner::getInstance();
        $result=$FO->exist($id);
        if ($result) {
            return TType::OWNER;
        }
        else
        {
            return TType::STUDENT;
        }
    }
    
    /**
     * Method getFilterTenants
     *
     * this method is used to get the tenants filtered by the parameters given
     * used in 'MyTenants' page by the owner
     * @param string $type [explicite description]
     * @param ?int $accommodation_name [explicite description]
     * @param ?string $t_username [explicite description]
     * @param ?int $t_age [explicite description]
     * @param int $rateT [explicite description]
     * @param ?string $date [explicite description]
     * @param ?bool $men [explicite description]
     * @param ?bool $women [explicite description]
     * @param int $idOwner [explicite description]
     * @param $year $year [explicite description]
     *
     * @return array
     */
    public function getFilterTenants(string $type, ?int $accommodation_name, ?string $t_username, ?int $t_age, int $rateT, ?string $date, ?bool $men, ?bool $women, int $idOwner,?int $year):array
    {
        $FO=FOwner::getInstance();
        if(!is_null($accommodation_name))
        {
            $accommodation_name=FPersistentManager::getInstance()->load('EAccommodation',$accommodation_name)->getTitle();
        }
        if(is_null($year))
        {
            $result=$FO->getFilterTenants($type,$accommodation_name,$t_username,$t_age,$rateT,$date,$men,$women,$idOwner,null);
        }
        else 
        {
            $result=$FO->getFilterTenants($type,$accommodation_name,$t_username,$t_age,$rateT,$date,$men,$women,$idOwner,(int)$year);
        }
        return $result;
    }


    /**
     * Loads visits by week.
     *
     * @return array An array containing the loaded visits.
     */
    public function loadVisitsByWeek():array
    {
        $FV=FVisit::getInstance();
        $result=$FV->loadByWeek();
        return $result;
    }

    /**
     * Method reserve
     * 
     * this method is used to reserve an accommodation
     * @param int $idAccommodation
     * @param int $year
     * @param int $date
     * @param int $year_2
     * @param int $date_2
     * @param int $student_id
     * @return ?bool
     */
    public function reserve(int $idAccommodation, int $year, int $date, int $year_2, int $date_2, int $student_id):?bool
    {
        $now = new DateTime('now');
        $start_accommodation=self::load('EAccommodation',$idAccommodation)->getStart();

        if(((int)($start_accommodation->format('Y')))==$year)#se l' anno è quello corrente
        {
            if($date>=((int)($now->format('m'))))# il mese in cui vuoi prenotare non è già passato (siamo a ottobre ma vuoi prenotare per settembre)
            {
                $result_places=$this->areThereFreePlaces($idAccommodation,$year);#controlla se ci sono posti disponibili
                if($result_places)# se ci sono prova a reggistrare la prenotazione
                {
                    $from = new DateTime();
                    $to = new DateTime();
                    $from=$from->setDate($year,$date,1);
                    $to=$to->setDate($year_2,$date_2,1);
                    $reservation = new EReservation($from,$to,$idAccommodation,$student_id);
                    $result=$this->store($reservation);
                    if($result)#se tutto va a buon fine
                    {
                        return true;#tutto ok
                    }
                    else#altrimenti
                    {
                        $viewError= new VError();
                        $viewError->error(500);#problema del server
                    }
                }
                else# posti esauriti per quest'anno
                {
                    return false;
                }      
            }
            else # il mese per cui vuoi prenotare è già passato quest'anno
            {
                return false;
            }
        }
        else #l' anno non è quello corrente
        {
            $result_places=$this->areThereFreePlaces($idAccommodation,$year);
            if($result_places)# se ci sono posti liberi per quell' anno
            {
                $from = new DateTime();
                $to = new DateTime();
                $from=$from->setDate($year,$date,1);
                $to=$to->setDate($year_2,$date_2,1);
                $reservation = new EReservation($from,$to,$idAccommodation,$student_id);
                $result=$this->store($reservation);
                if($result)# se riesci a registrare la prenotazione
                {
                    return true; #tutto ok
                }
                else
                {
                    return null;
                }
            }
            else #non ci sono posti liberi per l' anno selezionato
            {
                return false;
            }
        }
    }
    
    /**
     * Method areThereFreePlaces
     *
     * this method check if there are free places in the accommodation asking to DB
     * @param int $idAccommodation [accommodation's id]
     * @param int $year [year of the reservation]
     *
     * @return bool
     */
    private function areThereFreePlaces(int $idAccommodation, int $year):bool
    {
        $FA=FAccommodation::getInstance();
        $result=$FA->areThereFreePlaces($idAccommodation,$year);
        return $result;
    }
    public function loadVisitSchedule(int $id, TType $type):array
    {
       if ($type==TType::STUDENT)
       {
           $FV=FVisit::getInstance();
           $result=$FV->loadVisitScheduleStudent($id);
           return $result;
       }
       else
       {   
            $PM=self::getInstance();
            $accommodations=$PM->loadAccommodationsByOwner($id);
            $FV=FVisit::getInstance();
            $result=$FV->loadVisitScheduleOwner($accommodations);
            return $result;
       }
    }


    /**
     * Loads reservations by student.
     *
     * @param int $id The ID of the student.
     * @param string $kind The kind of reservations to load.
     * @return array The array of reservations.
     */
    public function loadReservationsByStudent(int $id, string $kind):array
    {
        $FR=FReservation::getInstance();
        $result=$FR->loadReservationsByStudent($id,$kind);
        return $result;
    }


    /**
     * Retrieves the title of an accommodation by its ID.
     *
     * @param int $id The ID of the accommodation.
     * @return string The title of the accommodation.
     */
    public function getTitleAccommodationById(int $id):string
    {
        $FA=FAccommodation::getInstance();
        $result=$FA->getTitleById($id);
        return $result;
    }
    
    /**
     * Updates the database.
     * 
     * @return void
     */
    public function updateDataBase() : void
    {
        $FU=FUpdater::getInstance();
        $FU->updateDB();
    }


    /**
     * Retrieves contracts by student.
     *
     * @param int $id The ID of the student.
     * @param int|null $idAccommodation The ID of the accommodation (optional).
     * @param string|null $kind The kind of contract (optional).
     * @return array|bool An array of contracts or false if no contracts found.
     */
    public function getContractsByStudent(int $id, ?int $idAccommodation=null, ?string $kind=null):array|bool
    {
        $FC=FContract::getInstance();
        $result=$FC->getContractsByStudent($id, $idAccommodation,$kind);
        return $result;
    }

    /**
     * Retrieves contracts by owner.
     *
     * @param int $id The owner's ID.
     * @param string $kind The kind of contracts to retrieve.
     * @return array|bool An array of contracts if found, otherwise false.
     */
    public function getContractsByOwner(int $id, string $kind):array|bool
    {
        $FC=FContract::getInstance();
        $result=$FC->getContractsByOwner($id,$kind);
        return $result;
    }
    
    /**
     * Method getOnGoingContractsByAccommodationId
     * 
     * this method call the method in FContract to accive the contract's onGoing for the accommodation with given ID
     *
     * @param int $id [accommodation ID]
     *
     * @return array
     */
    public function getOnGoingContractsByAccommodationId(int $id):array
    {
        $FC=FContract::getInstance();
        $result=$FC->getOnGoingContractsByAccommodationId($id);
        return $result;
    }
        
    /**
     * Method remainingReviewStudentToStudent
     *
     * this method call the omonim method in FReview to get the number of remaining reservation that a student can make about another student
     * @param int $id1 [student id 1, the one of the student who makes the student's review]
     * @param int $id2 [student id 2]
     *
     * @return int
     */
    public function remainingReviewStudentToStudent(int $id1, int $id2):int
    {
        $FR=FReview::getInstance();
        $result=$FR->remainingReviewStudentToStudent($id1,$id2);
        return $result;
    }
    
    /**
     * Method remainingReviewStudentToOwner
     *
     * this method call the omonim method in FReview to get the number of remaining reservation that a student can make about an owner
     * @param int $id1 [student id, the one who makes the review]
     * @param int $id2 [owner id]
     *
     * @return int
     */
    public function remainingReviewStudentToOwner(int $id1, int $id2):int
    {
        $FR=FReview::getInstance();
        $result=$FR->remainingReviewStudentToOwner($id1,$id2);
        return $result;
    }


    /**
     * Calculates the remaining review owner to student.
     *
     * @param int $id1 The ID of the owner.
     * @param int $id2 The ID of the student.
     * @return int The remaining review owner to student.
     */
    public function remainingReviewOwnerToStudent(int $id1, int $id2):int
    {
        $FR=FReview::getInstance();
        $result=$FR->remainingReviewOwnerToStudent($id1,$id2);
        return $result;
    }

    /**
     * Calculates the remaining number of reviews for a student to an accommodation.
     *
     * @param int $id1 The ID of the student.
     * @param int $id2 The ID of the accommodation.
     * @return int The remaining number of reviews for the student to the accommodation.
     */
    public function remainingReviewStudentToAccommodation(int $id1, int $id2):int
    {
        $FR=FReview::getInstance();
        $result=$FR->remainingReviewStudentToAccommodation($id1,$id2);
        return $result;
    }
    
    /**
     * Method getStatistics
     *
     * call the omonim method in Foundation to achive the statistics for the administrator
     * @return array
     */
    public function getStatistics():array
    {
        $result = FConnection::getInstance()->getStatistics();
        return $result;
    }
    
    /**
     * Method getBannedList
     * 
     * this method return the list of banned students and owners
     * @return array
     */
    public function getBannedList():array
    {
        $result=array();
        $FS=FStudent::getInstance();
        $FO=FOwner::getInstance();
        $result_student=$FS->getBannedStudents();
        $result=[];
        foreach ($result_student as $student)
        {
            $report= $this->getLastBanReport($student->getUsername());
            if($report!==false)
            {
                $result[]= ['User'=> $student, 'Type' =>'Student', 'Report'=> $report];
            }
        }
        $result_owner=$FO->getBannedOwners();
        foreach ($result_owner as $owner)
        {
            $report = $this->getLastBanReport($owner->getUsername());
            if($report !== false)
            {
                $result[]= ['User'=> $owner,'Type' =>'Owner', 'Report'=>$report];
            }
        }
        $result = USort::sortArray($result, 'report');
        
        return $result;
    }    
    /**
     * Method getLastBanReport
     *
     * this method return the last ban report of the student/owner
     * @param string $username [student/owner username]
     *
     * @return EReport|bool
     */
    public function getLastBanReport(string $username):EReport |bool {
        $FRe=FReport::getInstance();
        $FS=FStudent::getInstance();
        $FO=FOwner::getInstance();
        if ($FS->getStudentByUsername($username))
        {
            $subject=$FS->getStudentByUsername($username);
            $type=TType::STUDENT;
        }
        else
        {
            $subject=$FO->getOwnerByUsername($username);
            $type=TType::OWNER;
        }
        $result=$FRe->getLastBanReportBySubject($subject->getId(), $type);
        return $result;
    }
    
    /**
     * Method getSupportReply
     * 
     * this method return all the owner's/student's support replies 
     *
     * @param int $id [student/owner ID]
     * @param string $type [student/owner]
     *
     * @return array
     */
    public function getSupportReply(int $id, string $type):array
    {
        if($type=='Owner')
        {
            $FO=FOwner::getInstance();
            $result=$FO->getSupportReply($id);
            return $result;
        }
        elseif($type=='Student')
        {
            $FS=FStudent::getInstance();
            $result=$FS->getSupportReply($id);
            return $result;
        }
        else
        {
            return array();
        }
    }
    
    /**
     * Method getRequestAndReport
     * 
     * return an associative array with Reports and Support Requests to the administrator
     *
     * @return array
     */
    public function getRequestAndReport():array
    {
        $FReq=FSupportRequest::getInstance();
        $FRep=FReport::getInstance();
        $result['Request']=$FReq->getAllRequest();
        $result['Report']=$FRep->getAllReport();
        return $result;
    }

    /**
     * Supports a reply for a specific ID.
     *
     * @param int $id The ID of the reply.
     * @param string $ans The reply message.
     * @return bool Returns true if the reply is supported successfully, false otherwise.
     */
    public function supportReply(int $id, string $ans ):bool
    {
        $FSP=FSupportRequest::getInstance();
        $reply=$FSP->load($id);
        $reply->setSupportReply($ans);
        $reply->setStatus(TStatusSupport::RESOLVED);
        $result=$FSP->update($reply);
        if($result)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    /**
     * Reads a support reply from the persistent manager.
     *
     * @param int $id The ID of the support reply to read.
     * @return bool Returns true if the support reply was successfully read, false otherwise.
     */
    public function readSupportReply(int $id):bool
    {
        $FSP=FSupportRequest::getInstance();
        $reply=$FSP->load($id);
        $reply->setStatusRead(true);
        $result=$FSP->update($reply);
        if($result)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Method getMate
     *
     * this method call the omonim method in FStudent to get the mate of the student by contract
     * @param int $contractID [contract ID]
     * @return array
     */
    public function getMate(int $contractID):array
    {
        $FStudent=FStudent::getInstance();
        $result=$FStudent->getMate($contractID);
        return $result;
    }
}

