<?php

namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Entity\ECreditCard;
use Classes\Entity\EPhoto;
use Classes\Entity\EReservation;
use Classes\Foundation\FPersistentManager;
use Classes\Entity\EStudent;
use Classes\Tools\TType;
use Classes\Utilities\USession;
use Classes\Utilities\USuperGlobalAccess;
use Classes\View\VStudent; 
use Classes\Foundation\FCreditCard;
use Classes\Control;
use Classes\Tools\TStatusUser;
use Classes\View\VError;
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
     * Display the home page
     * 
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
    public static function contact(?string $modalSuccess=null){
        $session = USession::getInstance();
        $type = $session::getSessionElement('userType');
        if ($type === null) {
            header('Location:/UniRent/User/contact');
        } else if ($type ==='Owner') {
            header('Location:/UniRent/Owner/contact');
        }
        $view = new VStudent();
        $view->contact($modalSuccess);
    }
    public static function findAccommodation()
    {
        self::checkIfStudent();
        $view = new VStudent();
        $session=USession::getInstance();
        $PM=FPersistentManager::getInstance();
        $city=USuperGlobalAccess::getPost('city');
        $date=USuperGlobalAccess::getPost('date');
        $university=USuperGlobalAccess::getPost('university');
        $student_username=$session::getSessionElement('username');
        $student=$PM->getStudentByUsername($student_username);
    
        if (USuperGlobalAccess::getPost('rateA')!== null) {
            $rateA=USuperGlobalAccess::getPost('rateA');
        } else {
            $rateA=0;
        }
        if (USuperGlobalAccess::getPost('rateO')!== null) {
            $rateO=USuperGlobalAccess::getPost('rateO');
        } else {
            $rateO=0;
        }
        if (USuperGlobalAccess::getPost('min-price')!== null) {
            $minPrice=USuperGlobalAccess::getPost('min-price');
        } else {
            $minPrice=0;
        }
        if (USuperGlobalAccess::getPost('max-price')!== null) {
            $maxPrice=USuperGlobalAccess::getPost('max-price');
        } else {
            $maxPrice=1000;
        }
        $PM=FPersistentManager::getInstance();
        $searchResult=$PM->findAccommodationsStudent($city,$date,$rateA,$rateO,$minPrice,$maxPrice,$student);
        $view->findAccommodation($city,$university,$searchResult,$date, $rateO, $rateA, $minPrice, $maxPrice);
    }


    public static function about(){
        $session = USession::getInstance();
        $type = $session::getSessionElement('userType');
        if ($type === null) {
            header('Location:/UniRent/User/about');
        } else if ($type ==='Owner') {
            header('Location:/UniRent/Owner/about');
        }
        $view = new VStudent();
        $view->about();
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

            $session->setSessionElement('photo', $ph);
            http_response_code(500);

        } else {   

            $ph = $student->getPhoto();
            
            if(!is_null($ph)) {

                $ph=$ph->getPhoto();

                $session->setSessionElement('photo', $ph);

                $base64 = base64_encode($ph);
                $ph = "data:" . 'image/jpeg' . ";base64," . $base64;
            }

            $view->profile($student, $ph, $modalSuccess);
        }
    }
    
    public static function editProfile(?string $modalSuccess=null){
        self::checkIfStudent();
        $view = new VStudent();
        $PM = FPersistentManager::getInstance();
        $student = $PM->getStudentByUsername(USession::getInstance()::getSessionElement('username'));
        $photo = USession::getInstance()::getSessionElement('photo');

        if(is_null($student)) {
            $viewError=new VError();
            $viewError->error(500);
            exit();
        }else{
            $base64 = base64_encode($photo);
            $photo = "data:" . 'image/jpeg' . ";base64," . $base64;

            $view->editProfile($student, $photo, false, false, false, false, $modalSuccess);
        }
        
    }

    public static function deleteProfile()
    {   self::checkIfStudent();
        $PM=FPersistentManager::getInstance();
        $user=USession::getInstance()::getSessionElement('username');
        $result=$PM->d($user);
        if($result)
        {
            $session=USession::getInstance();
            $session::unsetSession();
            $session::destroySession();
            setcookie('PHPSESSID','',time()-3600);
            header('Location:/UniRent/User/home/success');
        }
        else
        {
            header('Location:/UniRent/'.$_COOKIE['current_page'].'/error');
        }
    }

    /**
     * Display the student registration page
     * 
     * 
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

        $duration = USuperGlobalAccess::getPost('courseDuration');
        $immatricolation = USuperGlobalAccess::getPost('immatricolationYear');
        $birthDate= new DateTime(USuperGlobalAccess::getPost('birthDate'));
        $sex = USuperGlobalAccess::getPost('sex');
        $animals=USuperGlobalAccess::getPost('animals');
        $smoker=USuperGlobalAccess::getPost('smoker');
        $smok=filter_var($smoker,FILTER_VALIDATE_BOOLEAN);
        $anim=filter_var($animals, FILTER_VALIDATE_BOOLEAN);
        
        
        $student=new EStudent($session->getSessionElement('username'),
                                $session->getSessionElement('password'),
                                $session->getSessionElement('name'),
                                $session->getSessionElement('surname'),
                                $photo,
                                $session->getSessionElement('email'),
                                $duration,
                                $immatricolation,
                                $birthDate,
                                $sex,
                                $smok,
                                $anim);

        $result = $PM->store($student);

        if ($result){
            $session->setSessionElement('id', $student->getId());
            $session->setSessionElement('courseDuration', $duration);
            $session->setSessionElement('immatricolationYear', $immatricolation);
            $session->setSessionElement('birthDate', $birthDate);
            $session->setSessionElement('sex', $sex);
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
    
    public static function accommodation(int $idAccommodation, string $successVisit='null', string $successReserve='null') {
        self::checkIfStudent();
        $view = new VStudent();
        $PM = FPersistentManager::getInstance();

        $accomm = $PM->load('EAccommodation', $idAccommodation);
        $student = $PM->load('EStudent', USession::getInstance()->getSessionElement('id'));
        if (strtoupper($student->getSex())==='F' && $accomm->getWoman() === false) {
            $viewError=new VError();
            $viewError->error(403);
            exit();
        } else if (strtoupper($student->getSex())==='M' && $accomm->getMan() === false) {
            $viewError=new VError();
            $viewError->error(403);
            exit();
        } else if ($student->getSmoker() && $accomm->getSmokers() === false) {
            $viewError=new VError();
            $viewError->error(403);
            exit();
        } else if ($student->getAnimals() && $accomm->getPets() === false) {
            $viewError=new VError();
            $viewError->error(403);
            exit();
        }
        $disabled=!$accomm->getStatus();
        $photos_acc=$accomm->getPhoto();
        $photo_acc_64=EPhoto::toBase64($photos_acc);
        $accomm->setPhoto($photo_acc_64);

        $picture=array();
        foreach($accomm->getPhoto() as $p)
        {
            if(is_null($p)){}
            else
            {
                $picture[]=$p->getPhoto();
            }
        }
        
        $owner = $PM->load('EOwner', $accomm->getIdOwner());
        $owner_photo=$owner->getPhoto();
        $ownerStatus = $owner->getStatus();
        if($ownerStatus === TStatusUser::BANNED){
            
            $path = __DIR__ . "/../../Smarty/images/BannedUser.png";
            $owner_photo = new EPhoto(null, file_get_contents($path), 'other', null);
            $owner_photo_64=EPhoto::toBase64(array($owner_photo));
            $owner->setPhoto($owner_photo_64[0]);
        }
        elseif(!is_null($owner_photo))
        {
            $owner_photo_64=EPhoto::toBase64(array($owner_photo));
            $owner->setPhoto($owner_photo_64[0]);
            #print_r($owner);
        }
        
        $reviews = $PM->loadByRecipient($accomm->getIdAccommodation(), TType::ACCOMMODATION);
        $reviewsData = [];
        
        foreach ($reviews as $review) {
            $author=$PM->load('EStudent', $review->getIdAuthor());
            if ($review->isBanned()) {
                continue;
            }
            $authorStatus = $author->getStatus();
            $profilePic = $author->getPhoto();
            if($authorStatus === TStatusUser::BANNED){
                $profilePic = "/UniRent/Smarty/images/BannedUser.png";
            }
            elseif ($profilePic === null) {
                $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
            }
            else
            {
                $profilePic=(EPhoto::toBase64(array($profilePic)))[0]->getPhoto();
            }
            if ($review->getDescription()===null) {
                $content='No additional details were provided by the author.';
            }
            else
            {
                $content=$review->getDescription();
            }
            $reviewsData[] = [
                'id' => $review->getId(),
                'title' => $review->getTitle(),
                'username' => $author->getUsername(),
                'userStatus' => $author->getStatus()->value,
                'stars' => $review->getValutation(),
                'content' => $content,
                'userPicture' => $profilePic,
            ];
        }
        $data=$accomm->getStart()->format('m');
        if($data=='09'){$period='september';}
        else{$period='october';}
        $visits=$accomm->getVisit();
        $booked=$PM->loadVisitsByWeek();
        $studBooked=false;
        $dayOfBooking='';
        $timeOfBooking='';
        foreach ($visits as $day=>$time) {
            foreach ($time as $key=>$t) {
                foreach ($booked as $b) {
                    if ($b->getIdStudent()===USession::getInstance()->getSessionElement('id') && $b->getIdAccommodation()==$idAccommodation)
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
        $visitDuration=$accomm->getVisitDuration();
        $num_places=$accomm->getPlaces();
        $tenantOwner= $PM->getTenants('current',$accomm->getIdOwner());
        if (!array_key_exists($idAccommodation, $tenantOwner)) {
            $tenants=[];
        }
        else
        {
            $tenants=[];
            foreach ($tenantOwner[$idAccommodation] as $i) {
                $status = $i[0]->getStatus();
                $profilePic = $i[0]->getPhoto();
                if($status === TStatusUser::BANNED){
                    $profilePic = "/UniRent/Smarty/images/BannedUser.png";
                }
                elseif ($profilePic === null) {
                    $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
                }
                else
                {
                    $profilePic=$profilePic->getPhoto();
                }
                $tenants[]= [
                    'username' => $i[0]->getUsername(),
                    'expiryDate' => $i[1],
                    'profilePic' => $profilePic,
                    'status' => $status
                ];
            }
        }
        $contracts=count($PM->getContractsByStudent(USession::getInstance()->getSessionElement('id'),$idAccommodation));
        $postedReviews=$PM->loadReviewsByAuthor(USession::getInstance()->getSessionElement('id'), TType::STUDENT);
        $countRev=0;
        foreach ($postedReviews as $review) {
            if ($review->getRecipientType()===TType::ACCOMMODATION) {
                if ($review->getIdRecipient()==$idAccommodation) {
                    $countRev++;
                }
            }
        }
        $leavebleReviews=$contracts-$countRev;
        if ($leavebleReviews<0) {
            $leavebleReviews=0;
        }
        $view->accommodation($accomm, $owner, $reviewsData, $period, $picture, $visits, $visitDuration, $tenants, $num_places, $studBooked, $dayOfBooking, $timeOfBooking, $disabled, $successReserve, $successVisit, $leavebleReviews);
    }


    //DA AGGIUSTARE PER LA SESSIONE
    public static function reviews(?string $modalSuccess=null) {
        self::checkIfStudent();
        $view = new VStudent();
        $session=USession::getInstance();
        $studentUsername=$session::getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        $studentId=$PM->getStudentIdByUsername($studentUsername);
        $reviews=$PM->loadByRecipient($studentId, TType::STUDENT);
        $reviewsData = [];
        
        foreach ($reviews as $review) {
            $author = $PM->load( 'E' . $review->getAuthorType()->value, $review->getIdAuthor());
            if ($review->isBanned()) {
                continue;
            }
            $profilePic = $author->getPhoto();
            if ($author->getStatus() === TStatusUser::BANNED) {
                $profilePic = "/UniRent/Smarty/images/BannedUser.png";
            } else if ($profilePic === null) {
                $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
            }
            else
            {
                $profilePic=(EPhoto::toBase64(array($profilePic)))[0]->getPhoto();
            }
            if ($review->getDescription()===null) {
                $content='No additional details were provided by the author.';
            }
            else
            {
                $content=$review->getDescription();
            }
            $reviewsData[] = [
                'id' => $review->getId(),
                'title' => $review->getTitle(),
                'username' => $author->getUsername(),
                'userStatus' => $author->getStatus()->value,
                'stars' => $review->getValutation(),
                'content' => $content,
                'userPicture' => $profilePic,
            ];
        }
        $view->reviews($reviewsData, $modalSuccess);
    }

    public static function modifyStudentProfile(){

        $session=USession::getInstance();
        $view = new VStudent();
        $error = 0;
        $photoError = "";

        //reed the data from the form
        $username=USuperGlobalAccess::getPost('username');
        $name=USuperGlobalAccess::getPost('name');
        $surname=USuperGlobalAccess::getPost('surname');
        $picture = USuperGlobalAccess::getPhoto('img');
        $oldPassword=USuperGlobalAccess::getPost('oldPassword');
        $newPassword=USuperGlobalAccess::getPost('newPassword');
        $email=USuperGlobalAccess::getPost('email');
        $sex=USuperGlobalAccess::getPost('sex');
        $courseDuration=USuperGlobalAccess::getPost('courseDuration');
        $immatricolationYear=USuperGlobalAccess::getPost('immatricolationYear');
        $birthDate= new DateTime(USuperGlobalAccess::getPost('birthDate'));
        $smoker=$session::booleanSolver(USuperGlobalAccess::getPost('smoker'));
        $animals=$session::booleanSolver(USuperGlobalAccess::getPost('animals'));

        $oldUsername=$session::getSessionElement('username');
        $PM=FPersistentManager::getInstance();

        $studentID=$PM->getStudentIdByUsername($oldUsername);

        $oldStudent = $PM->load('EStudent', $studentID);
        $oldEmail = $oldStudent->getUniversityMail();
        $oldPhoto=$oldStudent->getPhoto();

        if(!is_null($oldPhoto))
        {
            $base64 = base64_encode($oldPhoto->getPhoto());
            $photoError = "data:" . 'image/jpeg' . ";base64," . $base64;
            $oldPhoto=$photoError;
        }
        else
        {
            $oldPhoto=null;
        }
       
        //if the new email is not already in use and it's a student's email or you haven't changed it
        if((($PM->verifyUserEmail($email)==false)&&($PM->verifyStudentEmail($email)))||($oldEmail===$email)) { 
            
            //if the new username is not already in use or you haven't changed it
            if(($PM->verifyUserUsername($username)==false)||($oldUsername===$username)) { #se il nuovo username non è già in uso o non l'hai modificato
                
                $passChange = CStudent::changePassword($oldPassword, $newPassword, $oldStudent, $photoError);

                $password = $passChange[0];
                $error = $passChange[1];

                $photo = CStudent::changePhoto($oldPhoto, $picture, $oldStudent);      
                
                $student=new EStudent($username,$password,$name,$surname,$photo,$email,$courseDuration,$immatricolationYear,$birthDate,$sex,$smoker,$animals);
                $student->setID($studentID);

                $result=$PM->update($student);
                
                if($result && !$error){
                    
                    if(!is_null($photo))
                    {
                        $ph = $photo->getPhoto();
                    }
                    else
                    {
                        $ph=null;
                    }
                    $session->setSessionElement('username',$username);
                    //$password = $student->getPassword();
                    $session->setSessionElement('password',$password);
                    $session->setSessionElement('photo',$ph);
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

    private static function changePassword($oldPassword, $newPassword, $oldStudent, $photoError):array{

        $session=USession::getInstance();
        $view = new VStudent();
        $error = 0;

        if($newPassword===''){
            //If i don't have any new password, i'll use the old one
            $password=$session::getSessionElement('password');
        } else {
            
            if($oldPassword===$session::getSessionElement('password')){
                if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&()])[A-Za-z\d@$!%*?&()]{8,}$/' , $newPassword)){

                    $error = 1;
                    $password=$session::getSessionElement('password');
                    $view->editProfile($oldStudent, $photoError, true, false, false, false, null);

                } else $password=$newPassword;
                
            } else {
                $error = 1;
                $view->editProfile($oldStudent, $photoError, false, false, false, true, null);
                $password=$session::getSessionElement('password');
            }
        }

        return [$password, $error];
    }

    private static function changePhoto(?string $oldPhoto, ?array $picture, EStudent $oldStudent) : ?EPhoto{

        $PM=FPersistentManager::getInstance();

        if(!is_null($oldPhoto)){

                    
            $photoId=$oldStudent->getPhoto()->getId();

            is_null($picture) ? $photo = new EPhoto($photoId, $oldPhoto, 'other', null)
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

    public static function deletePhoto(){
        self::checkIfStudent();
        $PM=FPersistentManager::getInstance();
        $session=USession::getInstance();
        $username=$session::getSessionElement('username');
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
            $session->setSessionElement('photo',$photo);
            header('Location:/UniRent/Student/profile');
        }  else header('Location:/UniRent/Student/profile/error');

    }


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
        
    public static function publicProfileFromStudent(string $username, ?string $modalSuccess=null)
    {   self::checkIfStudent();
        $session=USession::getInstance();
        if ($session::getSessionElement('username') === $username) {
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
        $student_photo=$student->getPhoto();
        if(is_null($student_photo)){}
        else
        {
            $student_photo_64=EPhoto::toBase64(array($student_photo));
            $student->setPhoto($student_photo_64[0]);
            #print_r($owner);
        }
        
        foreach ($reviews as $review) {
            $author = $PM->load( 'E' . $review->getAuthorType()->value, $review->getIdAuthor());
            if ($review->isBanned()) {
                continue;
            }
            $status = $author->getStatus();
            $profilePic = $author->getPhoto();
            if($status === TStatusUser::BANNED){
                $profilePic = "/UniRent/Smarty/images/BannedUser.png";
            }
            elseif ($profilePic === null) {
                $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
            }
            else
            {
                $profilePic=(EPhoto::toBase64(array($profilePic)))[0]->getPhoto();
            }
            if ($review->getDescription()===null) {
                $content='No additional details were provided by the author.';
            }
            else
            {
                $content=$review->getDescription();
            }
            $reviewsData[] = [
                'id' => $review->getId(),
                'title' => $review->getTitle(),
                'username' => $author->getUsername(),
                'userStatus' => $author->getStatus()->value,
                'stars' => $review->getValutation(),
                'content' => $content,
                'userPicture' => $profilePic,
            ];
        }
        $leavebleReviews=$PM->remainingReviewStudentToStudent($session::getSessionElement('id'), $student->getId());
        $view->publicProfileFromStudent($student, $reviewsData, $self, $leavebleReviews, $modalSuccess);
    }
    public static function publicProfileFromOwner(string $username, ?string $modalSuccess=null)
    {   self::checkIfOwner();
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
        $student_photo=$student->getPhoto();
        if(is_null($student_photo)){}
        else
        {
            $student_photo_64=EPhoto::toBase64(array($student_photo));
            $student->setPhoto($student_photo_64[0]);
            #print_r($owner);
        }
        
        foreach ($reviews as $review) {
            $author = $PM->load( 'E' . $review->getAuthorType()->value, $review->getIdAuthor());
            if ($review->isBanned()) {
                continue;
            }
            $status = $author->getStatus();
            $profilePic = $author->getPhoto();
            if($status === TStatusUser::BANNED){
                $profilePic = "/UniRent/Smarty/images/BannedUser.png";
            }
            elseif ($profilePic === null) {
                $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
            }
            else
            {
                $profilePic=(EPhoto::toBase64(array($profilePic)))[0]->getPhoto();
            }
            if ($review->getDescription()===null) {
                $content='No additional details were provided by the author.';
            }
            else
            {
                $content=$review->getDescription();
            }
            $reviewsData[] = [
                'id' => $review->getId(),
                'title' => $review->getTitle(),
                'username' => $author->getUsername(),
                'userStatus' => $author->getStatus()->value,
                'stars' => $review->getValutation(),
                'content' => $content,
                'userPicture' => $profilePic,
            ];
        }
        $session=USession::getInstance();
        $leavebleReviews=$PM->remainingReviewOwnerToStudent($session->getSessionElement('id'), $student->getId());
        $view->publicProfileFromOwner($student, $reviewsData, $modalSuccess, $leavebleReviews);
    }
      
    public static function paymentMethods(?string $modalSuccess=null)
    {   self::checkIfStudent();
        $view = new VStudent();
        $session=USession::getInstance();
        $username=$session::getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        $studentId=$PM->getStudentIdByUsername($username);
        $cards =$PM->loadStudentCards($studentId);
        $cardsData = [];
        
        foreach ($cards as $card) {
            $cardsData[] = [
                'title' => $card->getTitle() ,
                'number' => $card->getNumber(),
                'expiryDate' => $card->getExpiry(),
                'cvv' => $card->getCVV(),
                'name' => $card->getName(),
                'surname' => $card->getSurname(),
                'isMain' => $card->getMain(),
            ];
        }
        $view->paymentMethods($cardsData, $modalSuccess);
    }

    public static function addCreditCard()
    {
        $title=USuperGlobalAccess::getPost('cardTitle');
        $number=USuperGlobalAccess::getPost('cardnumber');
        $expiry=USuperGlobalAccess::getPost('expirydate');
        $cvv=USuperGlobalAccess::getPost('cvv');
        $name=USuperGlobalAccess::getPost('name');
        $surname=USuperGlobalAccess::getPost('surname');
        $username=USession::getInstance()::getSessionElement('username');
        $studentId=FPersistentManager::getInstance()->getStudentIdByUsername($username);
        if(FPersistentManager::getInstance()->existsTheCard($number))
        {
            header('Location:/UniRent/Student/paymentMethods/error');
        }
        else
        {
            if(count(FPersistentManager::getInstance()->loadStudentCards($studentId))>0)
            {
                $card = new ECreditCard($number,$name,$surname,$expiry,(int)$cvv,(int)$studentId,false,$title);
                $result=FPersistentManager::getInstance()->store($card);
                if($result)
                {
                    header('Location:/UniRent/Student/paymentMethods/success');
                }
                else
                {
                    header('Location:/UniRent/Student/paymentMethods/error');
                }
            }
            else
            {
                $card = new ECreditCard($number,$name,$surname,$expiry,(int)$cvv,(int)$studentId,true,$title);
                $result=FPersistentManager::getInstance()->store($card);
                if($result)
                {
                    header('Location:/UniRent/Student/paymentMethods/success');
                }
                else
                {
                    header('Location:/UniRent/Student/paymentMethods/error');
                }
            }
        }
    }

    public static function deleteCreditCard(string $creditCard)
    {   self::checkIfStudent();
        $number=urldecode($creditCard);  #siccome usuamo url autodescrittive il php non decodifica i parametri automaticamente ma bisogna farlo a mano
        $PM=FPersistentManager::getInstance();
        $session=USession::getInstance();
        $card=$PM->loadCreditCard($number);
        if ($card->getStudentID() !== $session::getSessionElement('id')) {
            $view=new VError();
            $view->error(403);
            exit();
        }
        $result=$PM->deleteCreditCard($number);
        if($result)
        {
            header('Location:/UniRent/Student/paymentMethods/success');
        }
        else
        {
           header('Location:/UniRent/Student/paymentMethods/error');
        }
    }

    public static function editCreditCard()
    {
        $title=USuperGlobalAccess::getPost('cardTitle1');
        $number=USuperGlobalAccess::getPost('cardnumber1');
        $expiry=USuperGlobalAccess::getPost('expirydate1');
        $cvv=USuperGlobalAccess::getPost('cvv1');
        $name=USuperGlobalAccess::getPost('name1');
        $surname=USuperGlobalAccess::getPost('surname1');
        $username=USession::getInstance()::getSessionElement('username');
        $studentId=FPersistentManager::getInstance()->getStudentIdByUsername($username);
        #print $title.' '.$number.' '.$expiry.' '.$cvv.' '.$name.' '.$surname.' '.$oldNumber;
        $PM=FPersistentManager::getInstance();
        if($PM->isMainCard($studentId,$number))
        {
            $c= new ECreditCard($number,$name,$surname,$expiry,$cvv,$studentId,true,$title);
            $result=$PM->update($c);
            if($result)
            {
                header('Location:/UniRent/Student/paymentMethods/success');
            }
            else
            {
                header('Location:/UniRent/Student/paymentMethods/error');
            }
        }
        else
        {
            $c= new ECreditCard($number,$name,$surname,$expiry,$cvv,$studentId,false,$title);
            $result=$PM->update($c);
            if($result)
            {
               header('Location:/UniRent/student/paymentMethods/success');
            }
            else
            {
                header('Location:/UniRent/Student/paymentMethods/error');
            }
        }  
    }

    public static function makeMainCreditCard(string $number)
    {   self::checkIfStudent();
        $n=urldecode($number);
        $session=USession::getInstance();
        $username=$session::getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        $studentId=$PM->getStudentIdByUsername($username);
        $actualcard=$PM->loadCreditCard($n);
        if ($actualcard->getStudentID() !== $studentId) {
            $view=new VError();
            $view->error(403);
            exit();
        }
        $actualMain=$PM->getStudentMainCard($studentId);
        if(is_null($actualMain))
        {
            $actualcard->setMain(true);
            $res=$PM->update($actualcard);
            if ($res)
            {
                header('Location:/UniRent/Student/paymentMethods/success');
            }
            else
            {
                header('Location:/UniRent/Student/paymentMethods/error');
            }
        }
        else
        {
            $actualMain->setMain(false);
            $res_1=$PM->update($actualMain);
            if($res_1)
            {
                $actualcard->setMain(true);
                $res_2=$PM->update($actualcard);
                if($res_2)
                {
                    header('Location:/UniRent/Student/paymentMethods/success');
                }
                else
                {
                    header('Location:/UniRent/Student/paymentMethods/error');
                }
            }
            else
            {
                header('Location:/UniRent/Student/paymentMethods/error');
            }
        }
    }
    public static function postedReview(?string $modalSuccess=null) {
        self::checkIfStudent();
        $view = new VStudent();
        $session=USession::getInstance();
        $studentId=$session::getSessionElement('id');
        $PM=FPersistentManager::getInstance();
        $reviews = $PM->loadReviewsByAuthor($studentId, TType::STUDENT);
        $reviewsData = [];

        foreach ($reviews as $review) {
            $recipient = $PM->load( 'E' . ucfirst($review->getRecipientType()->value), $review->getIdRecipient());
            if ($review->isBanned()) {
                continue;
            }
            $profilePic = $recipient->getPhoto();
            if ($recipient->getStatus() === TStatusUser::BANNED) {
                $profilePic = "/UniRent/Smarty/images/BannedUser.png";
            } else if ($profilePic === null) {
                $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
            } else if (gettype($profilePic) === 'array') {

                if(count($profilePic)==0) #if the accommodation has no photos
                {
                    $profilePic = "/UniRent/Smarty/images/noPic.png";
                }
                else
                {
                    $profilePic = $profilePic[0];
                    $profilePic=(EPhoto::toBase64(array($profilePic))[0])->getPhoto();
                }
            }
            else
            {
                $profilePic=(EPhoto::toBase64(array($profilePic))[0])->getPhoto();
            }
            if ($review->getRecipientType()->value === 'accommodation') {
                $username = $recipient->getTitle();
                $status = $recipient->getStatus();
            } else {
                $username = $recipient->getUsername();
                $status = $recipient->getStatus()->value;
            }
            if ($review->getDescription()===null) {
                $content='No additional details were provided by the author.';
            }
            else
            {
                $content=$review->getDescription();
            }
            $reviewsData[] = [
                'title' => $review->getTitle(),
                'username' => $username,
                'userStatus' => $status,
                'stars' => $review->getValutation(),
                'content' => $content,
                'userPicture' => $profilePic,
                'id'=> $review->getId(),
                'type' => ucfirst($review->getRecipientType()->value),
                'idRecipient' => $review->getIdRecipient(),
                'reported' => $review->isReported()
            ];
        }
        $view->postedReview($reviewsData, $modalSuccess);
    }

    public static function reserveAccommodation(int $idAccommodation)
    {   self::checkIfStudent();
        // get student's id
        $session=USession::getInstance();
        $PM=FPersistentManager::getInstance();
        $student_username=$session::getSessionElement('username');
        $student_id=$PM->getStudentIdByUsername($student_username);

        //get post informations
        $year=(int)USuperGlobalAccess::getPost('year');
        $date=USuperGlobalAccess::getPost('date');
        $year_2=$year+1;
        $date_2=null;
        if($date=='September' or $date=='september')
        {
            $date=9;
            $date_2=6;
        }
        else
        {
            $date=10;
            $date_2=7;
        }

        $result=$PM->reserve($idAccommodation,$year,$date,$year_2,$date_2,$student_id);
        
        if($result)
        {
            header('Location:/UniRent/Student/accommodation/'.$idAccommodation.'/null/sent');
        }
        else
        {
            header('Location:/UniRent/Student/accommodation/'.$idAccommodation.'/null/full');
        }
    }
    private static function checkIfOwner() {
        $session = USession::getInstance();
        if ($session::getSessionElement('userType') !== 'Owner') {
            $view= new VError();
            $view->error(403);
            exit();
        }
    }
    private static function checkIfStudent() {
        $session = USession::getInstance();
        if ($session::getSessionElement('userType') !== 'Student') {
            $view= new VError();
            $view->error(403);
            exit();
        }
    }
}