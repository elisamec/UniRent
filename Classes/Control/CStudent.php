<?php

namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Entity\EAccommodation;
use Classes\Entity\ECreditCard;
use Classes\Entity\EPhoto;
use Classes\Foundation\FPersistentManager;
use Classes\Entity\EStudent;
use Classes\Tools\TType;
use Classes\Utilities\USession;
use Classes\Utilities\USuperGlobalAccess;
use Classes\View\VStudent; 
use Classes\Tools\TStatusUser;
use Classes\View\VError;
use Classes\Entity\EReview;
use Classes\Utilities\UFormat;
use DateTime;


/**
 * Student controller class
 *
 * This class is responsible for handling student-related requests
 * 
 * @package Classes\Control
 *
 */
class CStudent{
  
    /**
     * Method home
     *
     * @param ?string $modalSuccess [explicite description]
     *
     * @return void
     */
    public static function home(?string $modalSuccess=null){
        self::checkIfStudent();
        $view = new VStudent();
        $PM=FPersistentManager::getInstance();
        $session=USession::getInstance();
        $user = $session->getSessionElement('username');
        $student_id = $PM->getStudentIdByUsername($user);
        $student = $PM->load('EStudent', $student_id);
        $accommodations = $PM->lastAccommodationsStudent($student);
        $view->home($accommodations, $modalSuccess);
    }    
    /**
     * Method contact
     * 
     * this method shows the contact page
     * @param ?string $modalSuccess [explicite description]
     *
     * @return void
     */
    public static function contact(?string $modalSuccess=null){
        $session = USession::getInstance();
        $type = $session->getSessionElement('userType');
        if ($type === null) {
            header('Location:/UniRent/User/contact');
        } else if ($type ==='Owner') {
            header('Location:/UniRent/Owner/contact');
        }
        $view = new VStudent();
        $view->contact($modalSuccess);
    }    
    /**
     * Method search
     * 
     * this method is used by students to search an accommodation
     * 
     */
    public static function search()
    {
        self::checkIfStudent();
        $view = new VStudent();
        $session=USession::getInstance();
        $PM=FPersistentManager::getInstance();
        $aor=USuperGlobalAccess::getAllPost(['city','date','rateA','rateO','min-price','max-price','university','year']);

        $student_username=$session->getSessionElement('username');
        $student=$PM->getStudentByUsername($student_username);
        
        $aor['rateA'] = $aor['rateA'] ?? 0;
        $aor['rateO'] = $aor['rateO'] ?? 0;
        $aor['min-price'] = $aor['min-price'] ?? 0;
        $aor['max-price'] = $aor['max-price'] ?? 1000;
        $session->setSessionElement('selectedAccommYear', (int)$aor['year']);
        $PM=FPersistentManager::getInstance();
        $searchResult=$PM->findAccommodationsStudent($aor['city'],$aor['date'],$aor['rateA'],$aor['rateO'],$aor['min-price'],$aor['max-price'],$student,(int)$aor['year']);
        $view->findAccommodation($aor['city'],$aor['university'],$searchResult,$aor['date'], $aor['rateO'], $aor['rateA'], $aor['min-price'], $aor['max-price'],(int)$aor['year']);
    } 
    /**
     * Method about
     *
     * used to show the about us page
     * @return void
     */
    public static function about(){
        $session = USession::getInstance();
        $type = $session->getSessionElement('userType');
        if ($type === null) {
            header('Location:/UniRent/User/about');
        } else if ($type ==='Owner') {
            header('Location:/UniRent/Owner/about');
        }
        $view = new VStudent();
        $view->about();
    }    
    /**
     * Method guidelines
     *
     * this method is used to show the guidelines page
     * @return void
     */
    public static function guidelines(){
        $session = USession::getInstance();
        $type = $session->getSessionElement('userType');
        if ($type === null) {
            header('Location:/UniRent/User/guidelines');
        } else if ($type ==='Owner') {
            header('Location:/UniRent/Owner/guidelines');
        }
        $view = new VStudent();
        $view->guidelines();
    }
    /**
     * Method profile
     * This method shows the student's profile
     * 
     * @return void
     */
    public static function profile(?string $modalSuccess=null): void{
        self::checkIfStudent();
        $view = new VStudent();
        $session=USession::getInstance();

        $user_id = $session->getSessionElement('id');
        $PM=FPersistentManager::getInstance();
        $student=$PM->load('EStudent',$user_id);
        $ph = null;

        if(is_null($student)){

            $viewError=new VError();
            $viewError->error(403);
            exit();

        } else { 

            $ph = $student->getPhoto();
            if(!is_null($ph)) {

                $ph=$ph->getPhoto();
                $base64 = base64_encode($ph);
                $ph = "data:" . 'image/jpeg' . ";base64," . $base64;
            }
            $view->profile($student, $ph, $modalSuccess);
        }
    }  
    /**
     * Method editProfile
     *
     * this method is used to show the student's edit profile page
     * @param ?string $modalSuccess [explicite description]
     *
     * @return void
     */
    public static function editProfile(?string $modalSuccess=null){
        self::checkIfStudent();
        $view = new VStudent();
        $PM = FPersistentManager::getInstance();
        $student = $PM->getStudentByUsername(USession::getInstance()->getSessionElement('username'));
        if(is_null($student->getPhoto()))
        {
            $photo = file_get_contents(__DIR__.'/../../Smarty/images/ImageIcon.png');
            $controllo = false;
        } else {
            $photo = $student->getPhoto()->getPhoto();
            $controllo = true;
        }


        if(is_null($student)) {
            $viewError=new VError();
            $viewError->error(500);
            exit();
        }else{
            if($controllo)
            {
                $base64 = base64_encode($photo);
                $photo = "data:" . 'image/jpeg' . ";base64," . $base64;
            }
            else
            {
                $photo = "data:" . 'image/jpeg' . ";base64," . $photo;
            }
            $view->editProfile($student, $photo, false, false, false, false, $modalSuccess);
        }   
    }
    /**
     * Method deleteProfile
     *
     * this method is used to delete student's profile
     * @return void
     */
    public static function deleteProfile()
    {   self::checkIfStudent();
        $PM=FPersistentManager::getInstance();
        $user=USession::getInstance()->getSessionElement('username');
        $result=$PM->deleteStudentByUsername($user);
        if($result)
        {
            $session=USession::getInstance();
            $session->unsetSession();
            $session->destroySession();
            setcookie('PHPSESSID','',time()-2592000);
            header('Location:/UniRent/User/home/success');
        }
        else
        {
            header('Location:/UniRent/'.USuperGlobalAccess::getCookie('current_page').'/error');
        }
    }   
    /**
     * Method studentRegistration
     *
     * this method is used by the student to complete the registration
     * @return void
     */
    public static function studentRegistration()
    {   
        $PM=FPersistentManager::getInstance();
        $session=USession::getInstance();
        $picture = $session->getSessionElement('picture');
        if ($picture['img']===null) {

            $photo = null;

        } else {
            
            $photo = new EPhoto(null, $picture['img'], 'other', null);
        }   
        $afp=USuperGlobalAccess::getAllPost(['courseDuration','immatricolationYear','birthDate','sex','animals','smoker']);
        $birthDate= new DateTime(USuperGlobalAccess::getPost('birthDate'));
        $smok=filter_var($afp['smoker'],FILTER_VALIDATE_BOOLEAN);
        $anim=filter_var($afp['animals'], FILTER_VALIDATE_BOOLEAN);
        $student=new EStudent($session->getSessionElement('username'),
                                $session->getSessionElement('password'),
                                $session->getSessionElement('name'),
                                $session->getSessionElement('surname'),
                                $photo,
                                $session->getSessionElement('email'),
                                $afp['courseDuration'],
                                $afp['immatricolationYear'],
                                $birthDate,
                                $afp['sex'],
                                $smok,
                                $anim);
        $result = $PM->store($student);
        $student->setID(FPersistentManager::getInstance()->getStudentIdByUsername($student->getUsername()));
        if ($result){
            $session->setSessionElement('id', $student->getId());
            $session->setSessionElement('courseDuration', $afp['courseDuration']);
            $session->setSessionElement('immatricolationYear', $afp['immatricolationYear']);
            $session->setSessionElement('birthDate', $birthDate);
            $session->setSessionElement('sex', $afp['sex']);
            $session->setSessionElement('smoker', $smok);
            $session->setSessionElement('animal', $anim);
            header('Location:/UniRent/Student/home');
        }
        else{
            $viewError=new VError();
            $viewError->error(500);
            exit();
        }
    }
    
    /**
     * Method accommodation
     * 
     * @param int $idAccommodation
     * @param string $successVisit
     * @param string $successReserve
     *  
     * @return void
     */
    public static function accommodation(int $idAccommodation, string $successVisit='null', string $successReserve='null') {
        self::checkIfStudent();
        $view = new VStudent();
        $PM = FPersistentManager::getInstance();
        $accomm = $PM->load('EAccommodation', $idAccommodation);
        $student = $PM->load('EStudent', USession::getInstance()->getSessionElement('id'));
        self::checkPermission($accomm, $student);
        $disabled=!$accomm->getStatus();
        $photos_acc=$accomm->getPhoto();
        $photo_acc_64=EPhoto::toBase64($photos_acc);
        $accomm->setPhoto($photo_acc_64);
        $picture=array();
        foreach($accomm->getPhoto() as $p)
        {
            if(!is_null($p)){$picture[]=$p->getPhoto();}
        }
        $owner = $PM->load('EOwner', $accomm->getIdOwner());
        UFormat::photoFormatUser($owner);
        $reviews = $PM->loadByRecipient($accomm->getIdAccommodation(), TType::ACCOMMODATION);
        $reviewsData = [];
        foreach ($reviews as $review) {
            $author=$PM->load('EStudent', $review->getIdAuthor());
            $reviewsData[] = UFormat::reviewsFormatUser($author, $review);
        }
        $data=$accomm->getStart()->format('m');
        $period=$data=='09' ? 'september':'october';
        [$visits, $studBooked, $dayOfBooking, $timeOfBooking]=self::visitTimeSlots($accomm);
        $visitDuration=$accomm->getVisitDuration();
        $num_places=$accomm->getPlaces();
        $tenantsArray= $PM->getTenants('current',$accomm->getIdOwner());
        $tenants=array();
        foreach ($tenantsArray as $idAccommodation => $students) {
            $accommodationTitle = FPersistentManager::getInstance()->getTitleAccommodationById($idAccommodation);
            $tenants=UFormat::getFilterTenantsFormatArray($students, $idAccommodation, $accommodationTitle, 'Student');
        }
        $session=USession::getInstance();
        $periodNumber= $period==='september' ? 8 : 9;
        $year=$session->getSessionElement('SAY');
        if($year==null){
            $year=date('m')>$periodNumber? date('Y')+1 : date('Y');
        }
        $leavebleReviews=$PM->remainingReviewStudentToAccommodation($session->getSessionElement('id'), $accomm->getIdAccommodation());
        $view->accommodation($accomm, $owner, $reviewsData, $period, $picture, $visits, $visitDuration, $tenants, $num_places, $studBooked, $dayOfBooking, $timeOfBooking, $disabled, $successReserve, $successVisit, $leavebleReviews, $year);
    }
    /**
     * Method visitTimeSlots
     *
     * this method is used to delete the time slots occupied from the visit timeslots
     * @param EAccommodation $accomm
     *
     * @return array
     */
    private static function visitTimeSlots(EAccommodation $accomm) {
        $PM=FPersistentManager::getInstance();
        $visits=$accomm->getVisit();
        $booked=$PM->loadVisitsByWeek();
        $studBooked=false;
        $dayOfBooking='';
        $timeOfBooking='';
        foreach ($visits as $day=>$time) {
            foreach ($time as $key=>$t) {
                foreach ($booked as $b) {
                    if ($b->getIdStudent()===USession::getInstance()->getSessionElement('id') && $b->getIdAccommodation()==$accomm->getIdAccommodation())
                    {
                        $studBooked=true;
                        $dayOfBooking=$b->getDate()->format('d-m-Y');
                        $dayOfBooking=$b->getDayOfweek().' '.$dayOfBooking;
                        $timeOfBooking=$b->getDate()->format('H:i');
                    }
                    if($b->getDayOfWeek()==$day && $b->getDate()->format('H:i')==$t)
                    {
                        unset($visits[$day][$key]);
                    }
                }
            }
        }
        return [$visits, $studBooked, $dayOfBooking, $timeOfBooking];
    }
    /**
     * 
     * checkPermission
     * 
     * this method is used to check if the student has the permission to see the accommodation
     * @param \Classes\Entity\EAccommodation $accomm
     * @param \Classes\Entity\EStudent $student
     * @return void
     */
    private static function checkPermission(EAccommodation $accomm, EStudent $student):void {
        if ($accomm->getWoman() && $accomm->getMan()) {
            if (strtoupper($student->getSex())==='F' && $accomm->getWoman() === false) {
                $viewError=new VError();
                $viewError->error(403);
                exit();
            } else if (strtoupper($student->getSex())==='M' && $accomm->getMan() === false) {
                $viewError=new VError();
                $viewError->error(403);
                exit();
            }
        }
        if ($student->getSmoker() && $accomm->getSmokers() === false) {
            $viewError=new VError();
            $viewError->error(403);
            exit();
        } else if ($student->getAnimals() && $accomm->getPets() === false) {
            $viewError=new VError();
            $viewError->error(403);
            exit();
        }
    }
    /**
     * Method modifyStudentProfile
     *
     * this method is used to modify the student profile
     * @return void
     */
    public static function modifyStudentProfile(){
        $session=USession::getInstance();
        $view = new VStudent();
        $error = 0;
        //read the data from the form
        $afp=USuperGlobalAccess::getAllPost(['username','name','surname','oldPassword','newPassword','email','sex','courseDuration','immatricolationYear','birthDate','smoker','animals']);
        $picture = USuperGlobalAccess::getPhoto('img');
        $birthDate= new DateTime($afp['birthDate']);
        $smoker=$session->booleanSolver($afp['smoker']);
        $animals=$session->booleanSolver($afp['animals']);
        $oldUsername=$session->getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        $studentID=$PM->getStudentIdByUsername($oldUsername);
        $oldStudent = $PM->load('EStudent', $studentID);
        $oldEmail = $oldStudent->getUniversityMail();
        $oldPhoto=$oldStudent->getPhoto(); //It's a EPhoto object
        if(!is_null($oldPhoto)){
            $photoError = $oldPhoto->getPhoto();
            $photoError = "data:" . 'image/jpeg' . ";base64," . base64_encode($photoError);
        } else $photoError = null;
        //if the new email is not already in use and it's a student's email or you haven't changed it
        if((($PM->verifyUserEmail($afp['email'])==false)&&($PM->verifyStudentEmail($afp['email'])))||($oldEmail===$afp['email'])) {
            //if the new username is not already in use or you haven't changed it
            if(($PM->verifyUserUsername($afp['username'])==false)||($oldUsername===$afp['username'])) { #se il nuovo username non è già in uso o non l'hai modificato
                $photo = CStudent::changePhoto($oldPhoto, $picture);  
                $passChange = CStudent::changePassword($afp['oldPassword'], $afp['newPassword'], $oldStudent, $photoError);
                $password = $passChange[0];
                $error = $passChange[1];
                $student=new EStudent($afp['username'],$password,$afp['name'],$afp['surname'],$photo,$afp['email'],$afp['courseDuration'],$afp['immatricolationYear'],$birthDate,$afp['sex'],$smoker,$animals);
                $student->setID($studentID);
                $result=$PM->update($student);
                if($result && !$error){
                    
                    $session->setSessionElement('username',$afp['username']);
                    header('Location:/UniRent/Student/profile/success');
                } elseif (!$result) {
                    header('Location:/UniRent/Student/profile/error'); 
                } 
            }
            else
            {
                //Username error
                $view->editProfile($oldStudent, $photoError, false, true, false, false, null);
            }
        }
        else
        {
            //Email error
            $view->editProfile($oldStudent, $photoError, false, false, false, true, null);
        }  
    }
    /**
     * Method changePassword
     *
     * this method is used to change the student's password
     * @param string $oldPassword old password insered by user
     * @param string $newPassword new password insered by user
     * @param EStudent $oldStudent 
     * @param ?string $photoError
     *
     * @return array
     */
    private static function changePassword($formOldPassword, $newPassword, $oldStudent, $photoError):array{
        $view = new VStudent();
        $error = 0;
        $oldPassword = $oldStudent->getPassword();
        if($newPassword===''){
            //If i don't have any new password, i'll use the old one
            $password=$oldPassword;
        } else {
            //If the old password is correct
            if(password_verify($formOldPassword, $oldPassword)){
                //If the new password is not valid
                if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&()])[A-Za-z\d@$!%*?&()]{8,}$/' , $newPassword)){
                    $error = 1;
                    $password=$oldPassword;
                    $view->editProfile($oldStudent, $photoError, true, false, false, false, null);
                } else $password=$newPassword;
            //If the old password (typed by user) is incorrect
            } else {
                $error = 1;
                $view->editProfile($oldStudent, $photoError, false, false, false, true, null);
                $password=$oldPassword;
            }
        }
        return [$password, $error];
    }
    /**
     * Method changePhoto
     *
     * this method is used to change the student's photo
     * @param ?EPhoto $oldPhoto 
     * @param ?array $picture 
     * @param EStudent $oldStudent 
     *
     * @return EPhoto
     */
    private static function changePhoto(?EPhoto $oldPhoto, ?array $picture) : ?EPhoto{
        $PM=FPersistentManager::getInstance();

        if(!is_null($oldPhoto)){       

            $photoId=$oldPhoto->getId();
            is_null($picture) ? $photo = $oldPhoto
                              : $photo = new EPhoto($photoId, $picture['img'], 'other', null);

        } else {
            if(is_null($picture)) {

                $photo = null;
            } else {

                $photo = new EPhoto(null, $picture['img'], 'other', null);
                $risultato = $PM->storeAvatar($photo);

                if(!$risultato){
                    header("Location:/UniRent/Student/profile/error");
                }
            }
        }
        return $photo;
    }
    /**
     * Method deletePhoto
     * 
     * this method is used to delete the student's photo
     *
     * @return void
     */
    public static function deletePhoto(){
        self::checkIfStudent();
        $PM=FPersistentManager::getInstance();
        $session=USession::getInstance();
        $username=$session->getSessionElement('username');
        $studentID=$PM->getStudentIdByUsername($username);
        $photo = $PM -> load('EStudent', $studentID) -> getPhoto();

        if(is_null($photo)){
            $viewError=new VError();
            $viewError->error(500);
            exit();

        } else {
            $photoID = $photo->getId();
            $result = $PM->delete('EPhoto', $photoID);
        }
        if ($result > 0) {
            $photo = null;
            header('Location:/UniRent/Student/profile');
        }  else header('Location:/UniRent/Student/profile/error');
    }
    /**
     * Method publicProfile
     *
     * this method show the public profile of a student
     * @param string $username [explicite description]
     *
     * @return void
     */
    public static function publicProfile(string $username) {
        self::checkIfStudent();
        $PM=FPersistentManager::getInstance();
        $user=$PM->verifyUserUsername($username);
        if ($user['type']==='Student') {
            self::publicProfileFromStudent($username);
        } else {
            COwner::publicProfileFromStudent($username);
        }
    }  

    /**
     * Method publicProfileFromStudent
     *
     * this method show the public profile of a student to another student
     * @param string $username [explicite description]
     * @param ?string $modalSuccess [explicite description]
     *
     * @return void
     */
    public static function publicProfileFromStudent(string $username, ?string $modalSuccess=null)
    {   self::checkIfStudent();
        $session=USession::getInstance();
        if ($session->getSessionElement('username') === $username) {
            $self = true;
        } else {
            $self = false;
        }
        $view = new VStudent();
        $PM=FPersistentManager::getInstance();
        $student=$PM->getStudentByUsername($username);
        if ($student->getStatus() === TStatusUser::BANNED) {
            $viewError=new VError();
            $viewError->error(403);
            exit();
        }
        $reviews = $PM->loadByRecipient($student->getId(), TType::STUDENT); //va fatto il metodo nel PM
        $reviewsData = [];
        UFormat::photoFormatUser($student);
        foreach ($reviews as $review) {
            $author = $PM->load( 'E' . $review->getAuthorType()->value, $review->getIdAuthor());
         
            $reviewsData[] = UFormat::reviewsFormatUser($author, $review);
        }
        $leavebleReviews=$PM->remainingReviewStudentToStudent($session->getSessionElement('id'), $student->getId());
        $view->publicProfileFromStudent($student, $reviewsData, $self, $leavebleReviews, $modalSuccess);
    }

    /**
     * Method publicProfileFromOwner
     *
     * this method show the public profile of a student to an owner
     * @param string $username 
     * @param ?string $modalSuccess 
     *
     * @return void
     */
    public static function publicProfileFromOwner(string $username, ?string $modalSuccess=null)
    {   COwner::checkIfOwner();
        $view = new VStudent();
        $PM=FPersistentManager::getInstance();
        $student=$PM->getStudentByUsername($username);
        if ($student->getStatus() === TStatusUser::BANNED) {
            $viewError=new VError();
            $viewError->error(403);
            exit();
        }
        $reviews = $PM->loadByRecipient($student->getId(), TType::STUDENT); //va fatto il metodo nel PM
        $reviewsData = [];
        UFormat::photoFormatUser($student);
        
        foreach ($reviews as $review) {
            $author = $PM->load( 'E' . $review->getAuthorType()->value, $review->getIdAuthor());
            
            $reviewsData[] = UFormat::reviewsFormatUser($author, $review);
        }
        $session=USession::getInstance();
        $leavebleReviews=$PM->remainingReviewOwnerToStudent($session->getSessionElement('id'), $student->getId());
        $view->publicProfileFromOwner($student, $reviewsData, $modalSuccess, $leavebleReviews);
    }   
    /**
     * Method paymentMethods
     * 
     * metod used to reach the credit cards to show, this method call the omonim method in the view to show the 
     * payment methods page 
     *
     * @param ?string $modalSuccess [explicite description]
     *
     * @return void
     */
    public static function paymentMethods(?string $modalSuccess=null)
    {  
        self::checkIfStudent();
        $view = new VStudent();
        $session=USession::getInstance();
        $username=$session->getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        $studentId=$PM->getStudentIdByUsername($username);
        $cards =$PM->loadStudentCards($studentId);
        $cardsData=UFormat::creditCardFormatArray($cards);
        $view->paymentMethods($cardsData, $modalSuccess);
    }
    /**
     * Method reserveAccommodation
     *
     * this method 
     * @param int $idAccommodation [explicite description]
     *
     * @return void
     */
    public static function reserveAccommodation(int $idAccommodation)
    {   self::checkIfStudent();
        // get student's id
        $session=USession::getInstance();
        $PM=FPersistentManager::getInstance();
        $student_username=$session->getSessionElement('username');
        $student_id=$PM->getStudentIdByUsername($student_username);

        //get post informations
        $year=(int)USuperGlobalAccess::getPost('year');
        $date=USuperGlobalAccess::getPost('date');
        $year_2=$year+1;
        $date_2=null;
        $date= $date=='September' or $date=='september'? 9 : 10;
        $date_2=$date-3;
        $result=$PM->reserve($idAccommodation,$year,$date,$year_2,$date_2,$student_id);
        if (!$result) {
            $viewError=new VError();
            $viewError->error(500);
        }
        $result ? header('Location:/UniRent/Student/accommodation/'.$idAccommodation.'/null/sent') : header('Location:/UniRent/Student/accommodation/'.$idAccommodation.'/null/full');
    }
    /**
     * Method checkIfStudent
     *
     * method used to verify if an user is a student
     * @return void
     */
    public static function checkIfStudent() {
        $session = USession::getInstance();
        if ($session->getSessionElement('userType') !== 'Student') {
            $view= new VError();
            $view->error(403);
            exit();
        }
    }
}