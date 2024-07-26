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
    public static function home(){
        $view = new VStudent();
        $PM=FPersistentManager::getInstance();
        $session=USession::getInstance();
        $user = $session->getSessionElement('username');
        $student = $PM->getStudentByUsername($user);
        $accommodations = $PM->lastAccommodationsStudent($student);
        $view->home($accommodations);
    }
    public static function contact(){
        $view = new VStudent();
        $view->contact();
    }
    public static function findAccommodation()
    {
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
        $view = new VStudent();
        $view->about();
    }
    
    
    /**
     * Method profile
     * This method shows the student's profile
     * 
     * @return void
     */
    public static function profile(): void{

        $view = new VStudent();
        $session=USession::getInstance();

        $user = $session->getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        $student=$PM->getStudentByUsername($user);
        $ph = null;

        if(is_null($student)){

            $session->setSessionElement('photo', $ph);
            print '<b>500 : SERVER ERROR </b>';

        } else {   

            $ph = $student->getPhoto();
            
            if(!is_null($ph)) {

                $ph=$ph->getPhoto();

                $session->setSessionElement('photo', $ph);

                $base64 = base64_encode($ph);
                $ph = "data:" . 'image/jpeg' . ";base64," . $base64;
            }

            $view->profile($student, $ph);
        }
    }
    
    public static function editProfile(){
        $view = new VStudent();
        $PM = FPersistentManager::getInstance();
        $student = $PM->getStudentByUsername(USession::getInstance()::getSessionElement('username'));
        $photo = USession::getInstance()::getSessionElement('photo');

        if(is_null($student)) {
            print '<b>500 : SERVER ERROR </b>';
        }else{
            $base64 = base64_encode($photo);
            $photo = "data:" . 'image/jpeg' . ";base64," . $base64;

            $view->editProfile($student, $photo, false, false, false, false);
        }
        
    }

    public static function deleteProfile()
    {
        $PM=FPersistentManager::getInstance();
        $user=USession::getInstance()::getSessionElement('username');
        $result=$PM->d($user);
        if($result)
        {
            $session=USession::getInstance();
            $session::unsetSession();
            $session::destroySession();
            setcookie('PHPSESSID','',time()-3600);
            header('Location:/UniRent/User/home');
        }
        else
        {
            print 'Spiacenti non puoi andartene ;)';
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
            $session->setSessionElement('courseDuration', $duration);
            $session->setSessionElement('immatricolationYear', $immatricolation);
            $session->setSessionElement('birthDate', $birthDate);
            $session->setSessionElement('sex', $sex);
            $session->setSessionElement('smoker', $smok);
            $session->setSessionElement('animal', $anim);
            header('Location:/UniRent/Student/home');
        }
        else{
            print 'Spiacenti non sei stato registrato';
        }
    }
    
    public static function accommodation(int $idAccommodation, string $successVisit='null', string $successReserve='null', bool $disabled=false) {
        $view = new VStudent();
        $PM = FPersistentManager::getInstance();

        $accomm = $PM->load('EAccommodation', $idAccommodation);
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
        if(is_null($owner_photo)){}
        else
        {
            $owner_photo_64=EPhoto::toBase64(array($owner_photo));
            $owner->setPhoto($owner_photo_64[0]);
            #print_r($owner);
        }
        
        $reviews = $PM->loadByRecipient($accomm->getIdAccommodation(), TType::ACCOMMODATION);
        $reviewsData = [];
        
        foreach ($reviews as $review) {
            $author=$PM->load( 'EStudent', $review->getIdAuthor());
            $authorStatus = $author->getStatus();
            $profilePic = $author->getPhoto();
            if($authorStatus === 'banned'){
                $profilePic = "/UniRent/Smarty/images/BannedUser.png";
            }
            elseif ($profilePic === null) {
                $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
            }
            else
            {
                $profilePic=(EPhoto::toBase64(array($profilePic)))[0]->getPhoto();
            }
            $reviewsData[] = [
                'title' => $review->getTitle(),
                'username' => $author->getUsername(),
                'userStatus' => $author->getStatus(),
                'stars' => $review->getValutation(),
                'content' => $review->getDescription(),
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
                $profilePic = $i[0]->getPhoto();
                if ($profilePic === null) {
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
                ];
            }
        }
        $view->accommodation($accomm, $owner, $reviewsData, $period, $picture, $visits, $visitDuration, $tenants, $num_places, $studBooked, $dayOfBooking, $timeOfBooking, $disabled, $successReserve, $successVisit);
    }


    //DA AGGIUSTARE PER LA SESSIONE
    public static function reviews() {
        $view = new VStudent();
        $session=USession::getInstance();
        $studentUsername=$session::getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        $studentId=$PM->getStudentIdByUsername($studentUsername);
        $reviews=$PM->loadByRecipient($studentId, TType::STUDENT);
        $reviewsData = [];
        
        foreach ($reviews as $review) {
            $author = $PM::load( 'E' . $review->getAuthorType()->value, $review->getIdAuthor());
            $profilePic = $author->getPhoto();
            if ($profilePic === null) {
                $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
            }
            else
            {
                $profilePic=(EPhoto::toBase64(array($profilePic)))[0]->getPhoto();
            }
            $reviewsData[] = [
                'title' => $review->getTitle(),
                'username' => $author->getUsername(),
                'userStatus' => $author->getStatus(),
                'stars' => $review->getValutation(),
                'content' => $review->getDescription(),
                'userPicture' => $profilePic,
            ];
        }
        $view->reviews($reviewsData);
    }

    public static function modifyStudentProfile(){

        $session=USession::getInstance();
        $view = new VStudent();
        $error = 0;

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
        #$oldPhoto = $session::getSessionElement('photo');
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

                print "Sto per aggionare lo studente <br>";

                $result=$PM->update($student);

                print "Studente aggiornato <br>";
                
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
                    $session->setSessionElement('password',$password);
                    $session->setSessionElement('photo',$ph);
                    header('Location:/UniRent/Student/profile');
                } elseif (!$result) {
                    
                    http_response_code(500);
                    
                } 
            }
            else
            {
                //Username error
                $view->editProfile($oldStudent, $photoError, false, true, false, false);
                #header('Location:/UniRent/Student/profile');s
            }
        }
        else
        {
            //Email error
            $view->editProfile($oldStudent, $photoError, false, false, false, true);
            #header('Location:/UniRent/Student/profile');
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
                    $view->editProfile($oldStudent, $photoError, true, false, false, false);

                } else $password=$newPassword;
                
            } else {
                $error = 1;
                $view->editProfile($oldStudent, $photoError, false, false, false, true);
                $password=$session::getSessionElement('password');
            }
        }

        return [$password, $error];
    }

    private static function changePhoto(?string $oldPhoto, ?array $picture, EStudent $oldStudent) : ?EPhoto{

        $PM=FPersistentManager::getInstance();

        if(!is_null($oldPhoto)){

            print "La vecchia foto non è null<br>";
                    
            $photoId=$oldStudent->getPhoto()->getId();

            is_null($picture) ? $photo = new EPhoto($photoId, $oldPhoto, 'other', null)
                              : $photo = new EPhoto($photoId, $picture['img'], 'other', null);

        } else {

            print "La vecchia foto è null";

            if(is_null($picture)) {

                $photo = null;

            } else {

                $photo = new EPhoto(null, $picture['img'], 'other', null);
                $risultato = $PM->storeAvatar($photo);

                if(!$risultato){
                    header("HTTP/1.1 500 Internal Server Error");
                }
            }
        }

        return $photo;
    }

    public static function deletePhoto(){

        $PM=FPersistentManager::getInstance();
        $session=USession::getInstance();
        $username=$session::getSessionElement('username');
        $studentID=$PM->getStudentIdByUsername($username);
        $photo = $PM -> load('EStudent', $studentID) -> getPhoto();

        if(is_null($photo)){

            print "Non hai nessuna foto da eliminare";
            $result = 0;

        } else {
            $photoID = $photo->getId();
            $result = $PM->delete('EPhoto', $photoID);
        }

        

        if ($result > 0) {
            $photo = null;
            $session->setSessionElement('photo',$photo);
            header('Location:/UniRent/Student/profile');
        }  else print "Errore nell'eliminazione della foto <br>";

    }


    public static function publicProfile(string $username, ?string $kind="#") {
        $PM=FPersistentManager::getInstance();
        $user=$PM->verifyUserUsername($username);
        $location='/UniRent/'.$user['type'].'/publicProfileFromStudent/'.$username .$kind;
        header('Location:'.$location);
    }
        
    public static function publicProfileFromStudent(string $username, ?string $kind="#")
    {
        $view = new VStudent();
        $PM=FPersistentManager::getInstance();
        $student=$PM->getStudentByUsername($username);
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
            $author = $PM::load( 'E' . $review->getAuthorType()->value, $review->getIdAuthor());
            $status = $author->getStatus();
            $profilePic = $author->getPhoto();
            if($status === 'banned'){
                $profilePic = "/UniRent/Smarty/images/BannedUser.png";
            }
            elseif ($profilePic === null) {
                $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
            }
            else
            {
                $profilePic=(EPhoto::toBase64(array($profilePic)))[0]->getPhoto();
            }
            $reviewsData[] = [
                'title' => $review->getTitle(),
                'username' => $author->getUsername(),
                'userStatus' => $author->getStatus(),
                'stars' => $review->getValutation(),
                'content' => $review->getDescription(),
                'userPicture' => $profilePic,
            ];
        }
        $view->publicProfileFromStudent($student, $reviewsData, $kind);
    }
    public static function publicProfileFromOwner(string $username, ?string $kind="#")
    {
        $view = new VStudent();
        $PM=FPersistentManager::getInstance();
        $student=$PM->getStudentByUsername($username);
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
            $author = $PM::load( 'E' . $review->getAuthorType()->value, $review->getIdAuthor());
            $status = $author->getStatus();
            $profilePic = $author->getPhoto();
            if($status === 'banned'){
                $profilePic = "/UniRent/Smarty/images/BannedUser.png";
            }
            elseif ($profilePic === null) {
                $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
            }
            else
            {
                $profilePic=(EPhoto::toBase64(array($profilePic)))[0]->getPhoto();
            }
            $reviewsData[] = [
                'title' => $review->getTitle(),
                'username' => $author->getUsername(),
                'userStatus' => $author->getStatus(),
                'stars' => $review->getValutation(),
                'content' => $review->getDescription(),
                'userPicture' => $profilePic,
            ];
        }
        $view->publicProfileFromOwner($student, $reviewsData, $kind);
    }
      
    public static function paymentMethods()
    {
        $view = new VStudent();
        $session=USession::getInstance();
        $username=$session::getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        $studentId=$PM->getStudentIdByUsername($username);
        $cards =$PM->loadStudentCards($studentId);
        $cardsData = [];
           /* [
                'title' => 'Card 1',
                'number' => '1234 4321 1234 1234',
                'expiryDate' => '12/25',
                'cvv' => '122',
                'name' => 'John',
                'surname' => 'Doe',
                'isMain' => true,
            ],
            [
                'title' => 'Card 2',
                'number' => '1234 1234 4321 1234',
                'expiryDate' => '12/25',
                'cvv' => '122',
                'name' => 'John',
                'surname' => 'Doe',
                'isMain' => false,
            ],
            ['title' => 'Card 13',
                'number' => '1234 1234 1234 1234',
                'expiryDate' => '12/25',
                'cvv' => '122',
                'name' => 'John',
                'surname' => 'Doe',
                'isMain' => false,
            ]
        ];*/
        
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
        #print_r($cardsData);
        $view->paymentMethods($cardsData);
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
            print 'This credit card already exists!';
        }
        else
        {
            if(count(FPersistentManager::getInstance()->loadStudentCards($studentId))>0)
            {
                $card = new ECreditCard($number,$name,$surname,$expiry,(int)$cvv,(int)$studentId,false,$title);
                $result=FPersistentManager::getInstance()::store($card);
                if($result)
                {
                    header('Location:/UniRent/Student/paymentMethods');
                }
                else
                {
                    http_response_code(500);
                }
            }
            else
            {
                $card = new ECreditCard($number,$name,$surname,$expiry,(int)$cvv,(int)$studentId,true,$title);
                $result=FPersistentManager::getInstance()::store($card);
                if($result)
                {
                    header('Location:/UniRent/Student/paymentMethods');
                }
                else
                {
                    http_response_code(500);
                }
            }
        }
    }

    public static function deleteCreditCard(string $creditCard)
    {
        $number=urldecode($creditCard);  #siccome usuamo url autodescrittive il php non decodifica i parametri automaticamente ma bisogna farlo a mano
        $PM=FPersistentManager::getInstance();
        $result=$PM->deleteCreditCard($number);
        if($result)
        {
            http_response_code(200);  # ok
        }
        else
        {
            http_response_code(500);   # server error
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
            $result=$PM::update($c);
            if($result)
            {
                header('Location:/UniRent/Student/paymentMethods');
            }
            else
            {
                http_response_code(500);
            }
        }
        else
        {
            $c= new ECreditCard($number,$name,$surname,$expiry,$cvv,$studentId,false,$title);
            $result=$PM::update($c);
            if($result)
            {
               header('Location:/UniRent/student/paymentMethods');
            }
            else
            {
                http_response_code(500);
            }
        }  
    }

    public static function makeMainCreditCard(string $number)
    {
        $n=urldecode($number);
        $session=USession::getInstance();
        $username=$session::getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        $studentId=$PM->getStudentIdByUsername($username);
        $actualcard=$PM->loadCreditCard($n);
        $actualMain=$PM->getStudentMainCard($studentId);
        if(is_null($actualMain))
        {
            $actualcard->setMain(true);
            $PM::update($actualcard);
            http_response_code(200);
        }
        else
        {
            $actualMain->setMain(false);
            $res_1=$PM::update($actualMain);
            if($res_1)
            {
                $actualcard->setMain(true);
                $res_2=$PM::update($actualcard);
                if($res_2)
                {
                    http_response_code(200);
                }
                else
                {
                    http_response_code(500);
                }
            }
            else
            {
                http_response_code(500);
            }
        }
    }
    public static function postedReview() {
        $view = new VStudent();
        $session=USession::getInstance();
        $username=$session::getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        $studentId=$PM->getStudentIdByUsername($username);
        $reviews = $PM->loadReviewsByAuthor($studentId, TType::STUDENT);
        $reviewsData = [];

        foreach ($reviews as $review) {
            $recipient = $PM::load( 'E' . $review->getRecipientType()->value, $review->getIdRecipient());
            $profilePic = $recipient->getPhoto();
            if ($profilePic === null) {
                $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
            }
            else
            {
                $profilekPic=(EPhoto::toBase64(array($profilePic)))[0]->getPhoto();
            }
            $reviewsData[] = [
                'title' => $review->getTitle(),
                'username' => $recipient->getUsername(),
                'userStatus' => $recipient->getStatus(),
                'stars' => $review->getValutation(),
                'content' => $review->getDescription(),
                'userPicture' => $profilePic,
                'id'=> $review->getId()
            ];
        }
        $view->postedReview($reviewsData);
    }

    public static function reserveAccommodation(int $idAccommodation)
    {
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
            header('Location:/UniRent/Student/accommodation/'.$idAccommodation.'/null/true');
        }
        else
        {
            print 'Spiacenti non ci sono posti disponibili';
            header('Location:/UniRent/Student/accommodation/'.$idAccommodation.'/null/full');
        }
    }
}