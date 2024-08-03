<?php
namespace Classes\Foundation;
require __DIR__ .'/../../vendor/autoload.php';
use Classes\Entity\EReservation;
use Classes\Foundation;
use Classes\Entity;
use Classes\Entity\ECreditCard;
use Classes\Entity\EOwner;
use Classes\Entity\EPhoto;
use Classes\Entity\EStudent;
use Classes\Tools\TType;
use Classes\Utilities\UAccessUniversityFile;
use Classes\Foundation\FUpdater;
use Classes\View\VError;
use Classes\Foundation\FReport;
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

        print "Sto nel PM";
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

    //public function findAccommodation(DateTime $date,DateTime $fromDate, string $place), chiede query a FAccommodation, @return array


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
     * @return bool
     */
    public function verifyStudentEmail(string $email):bool
    {
        $AUF=UAccessUniversityFile::getInstance();
        
        $email = explode(".", str_replace("@", ".", $email));
        $domain = array_slice($email, -2);
        $mail_domain = $domain[0] . "." . $domain[1];

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

    public function storeAvatar(EPhoto $photo) :bool {
        $FP=FPhoto::getInstance();
        $result=$FP->storeAvatar($photo);
        return $result;
    }

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

    public function deleteCreditCard(string $number):bool
    {
        $FC=FCreditCard::getInstance();
        $result=$FC->delete($number);
        return $result;
    }

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
     * @param int $studentId [explicite description]
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
     * @param string $city [explicite description]
     * @param string $date [explicite description]
     *
     * @return array
     */
    public function findAccommodationsUser($city,$date,$rateA,$rateO,$minPrice,$maxPrice):array
    {
        $FA=FAccommodation::getInstance();
        $result=$FA->findAccommodationsUser($city,$date,$rateA,$rateO,$minPrice,$maxPrice);
        return $result;
    }

    public function findAccommodationsStudent($city,$date,$rateA,$rateO,$minPrice,$maxPrice,$student)
    {
        $FA=FAccommodation::getInstance();
        $result=$FA->findAccommodationsStudent($city,$date,$rateA,$rateO,$minPrice,$maxPrice,$student);
        return $result;
    }

    public function lastAccommodationsUser():array
    {
        $FA=FAccommodation::getInstance();
        $result=$FA->lastAccommodationsUser();
        return $result;
    }

    public function lastAccommodationsStudent(EStudent $student):array
    {
        $FA=FAccommodation::getInstance();
        $result=$FA->lastAccommodationsStudent($student);
        return $result;
    }

    public function loadReviewsByAuthor(int $idAuthor, TType $type): array {
        $FReview = FReview::getInstance();
        $reviews = $FReview->loadByAuthor($idAuthor, $type);
        return $reviews;
    }
    public function loadAccommodationsByOwner(int $idOwner): ?array {
        $FA = FAccommodation::getInstance();
        $accommodations = $FA->loadByOwner($idOwner);
        return $accommodations;
    }

    public function getOwnerRating($id):int
    {
        $result=FOwner::findOwnerRating($id);
        return $result;
    }

    public function getAccommodationRating($id):int
    {
        $FA=FAccommodation::getInstance();
        $result=$FA->findAccommodationRating($id);
        return $result;
    }

    public function getStudentRating($id):int
    {
        $FS=FStudent::getInstance();
        $result=$FS->findStudentRating($id);
        return $result;
    }

    public function getTenants(string $type, int $idOwner):array
    {
        $FO=FOwner::getInstance();
        if ($type=='current')
        {
            $type='onGoing';
        } else if ($type=='past')
        {
            $type='finshed';
        }
        $result=$FO->getTenans($type,$idOwner);
        return $result;
    }
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

    public function getFilterTenants(string $type, ?string $accommodation_name, string $t_username, int $t_age, int $rateT, ?string $date, ?bool $men, ?bool $women, int $idOwner):array
    {
        $FO=FOwner::getInstance();
        $result=$FO->getFilterTenants($type,$accommodation_name,$t_username,$t_age,$rateT,$date,$men,$women,$idOwner);
        return $result;
    }
    public function loadVisitsByWeek():array
    {
        $FV=FVisit::getInstance();
        $result=$FV->loadByWeek();
        return $result;
    }

    public function reserve(int $idAccommodation, int $year, int $date, int $year_2, int $date_2, int $student_id)
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
                    $viewError= new VError();
                    $viewError->error(500); #altrimenti ci sono problemi con il server
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
    public function loadReservationsByStudent(int $id, string $kind):array
    {
        $FR=FReservation::getInstance();
        $result=$FR->loadReservationsByStudent($id,$kind);
        return $result;
    }
    public function getTitleAccommodationById(int $id):string
    {
        $FA=FAccommodation::getInstance();
        $result=$FA->getTitleById($id);
        return $result;
    }
    
    public function updateDataBase()
    {
        $FU=FUpdater::getInstance();
        $FU->updateDB();
    }
    public function getContractsByStudent(int $id, ?int $idAccommodation=null, ?string $kind=null):array|bool
    {
        $FC=FContract::getInstance();
        $result=$FC->getContractsByStudent($id, $idAccommodation,$kind);
        return $result;
    }
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

    public function getBannedList():array
    {
        $result=array();
        $FS=FStudent::getInstance();
        $FO=FOwner::getInstance();
        $result_student=$FS->getBannedStudents();
        $result_owner=$FO->getBannedOwners();
        $result['students']=$result_student;
        $result['owners']=$result_owner;
        return $result;
    }
    public function getBanReason(string $username):string {
        $FRe=FReport::getInstance();
        $FS=FStudent::getInstance();
        $student=$FS->getStudentByUsername($username);
        $result=$FRe->getLastBanReportByStudent($student->getId())->getDescription();
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
        if($type=='owner')
        {
            $FO=FOwner::getInstance();
            $result=$FO->getSupportReply($id);
            return $result;
        }
        elseif($type=='student')
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
}

