<?php

namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Entity\ECreditCard;
use Classes\Entity\EPhoto;
use Classes\Foundation\FPersistentManager;
use Classes\Entity\EStudent;
use Classes\Foundation\FReview;
use Classes\Foundation\FStudent;
use Classes\Tools\TType;
use Classes\Utilities\USession;
use Classes\Utilities\USuperGlobalAccess;
use Classes\View\VStudent; 
use Classes\Control; 
use DateTime;
use FCreditCard;

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
        $view->home();
    }
    public static function contact(){
        $view = new VStudent();
        $view->contact();
    }
    public static function findAccommodation(){
        $view = new VStudent();
        $view->findAccommodation();
    }
    public static function about(){
        $view = new VStudent();
        $view->about();
    }
    public static function search(){
        $view = new VStudent();
        $view->search();
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
        $student =FPersistentManager::getInstance()->getStudentByUsername(USession::getInstance()::getSessionElement('username'));
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
            
            $photo = new EPhoto(null, $picture['img'], 'other', null, null);
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
    public static function accommodation(int $idAccommodation) {
        $view = new VStudent();
        $accomm = FPersistentManager::getInstance()->load('EAccommodation', $idAccommodation);
        $owner = FPersistentManager::getInstance()->load('EOwner', $accomm->getIdOwner());
        USession::getInstance()->setSessionElement('owner', $owner->getUsername());
        $reviews = FReview::getInstance()->loadByRecipient($accomm->getIdAccommodation(), TType::ACCOMMODATION);
        $reviewsData = [];
        
        foreach ($reviews as $review) {
            $profilePic = FStudent::getInstance()->load($review->getIdAuthor())->getPhoto();
            if ($profilePic === null) {
                $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
            }
            $reviewsData[] = [
                'title' => $review->getTitle(),
                'username' => FStudent::getInstance()->load($review->getIdAuthor())->getUsername(),
                'stars' => $review->getValutation(),
                'content' => $review->getDescription(),
                'userPicture' => $profilePic,
            ];
        }
        $view->accommodation($accomm, $owner, $reviewsData);
    }


    //DA AGGIUSTARE PER LA SESSIONE
    public static function reviews() {
        $view = new VStudent();
        $session=USession::getInstance();
        $studentUsername=$session::getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        $studentId=$PM->getStudentIdByUsername($studentUsername);
        $reviews=$PM->loadByRecipient($studentId, TType::STUDENT);
       #$reviews = FReview::getInstance()->loadByRecipient($studentId, TType::STUDENT);
        $reviewsData = [];
        
        foreach ($reviews as $review) {
            $profilePic = $PM->load( 'E' . $review->getAuthorType()->value, $review->getIdAuthor())->getPhoto();
            if ($profilePic === null) {
                $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
            }
            $reviewsData[] = [
                'title' => $review->getTitle(),
                'username' => $PM->load( 'E' . $review->getAuthorType()->value, $review->getIdAuthor())->getUsername(),
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
        $oldPhoto = $session::getSessionElement('photo');

        $base64 = base64_encode($oldPhoto);
        $photoError = "data:" . 'image/jpeg' . ";base64," . $base64;

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

                    $session->setSessionElement('username',$username);
                    $session->setSessionElement('password',$password);
                    header('Location:/UniRent/Student/profile');
                } elseif (!$result) {
                    
                    header("HTTP/1.1 500 Internal Server Error");
                    
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
                    
            $photoId=$oldStudent->getPhoto()->getId();

            is_null($picture) ? $photo = new EPhoto($photoId, $oldPhoto, 'other', null, null)
                              : $photo = new EPhoto($photoId, $picture['img'], 'other', null, null);

        } else {

            if(is_null($picture)) {

                $photo = null;

            } else {

                $photo = new EPhoto(null, $picture['img'], 'other', null, null);
                $risultato = $PM->storeAvatar($photo);

                if(!$risultato){
                    header("HTTP/1.1 500 Internal Server Error");
                }
            }
        }

        return $photo;
    }

    /*public static function deletePhoto(){

        $PM=FPersistentManager::getInstance();
        $session=USession::getInstance();
        $username=$session::getSessionElement('username');
        $studentID=$PM->getStudentIdByUsername($username);
        $photo = $PM -> load('EStudent', $studentID) -> getPhoto();

        if(is_null($photo)){

            print "Non hai nessuna foto da eliminare";

        } else {
            $photoID = $photo->getId();
        }

        $result = $PM->delete('EPhoto', $photoID);

        $result > 0 ? print "Foto eliminata <br>" : print "Errore nell'eliminazione della foto <br>";

    }*/


    public static function publicProfile(string $username) {
        $PM=FPersistentManager::getInstance();
        $user=$PM->verifyUserUsername($username);
        $location='/UniRent/'.$user['type'].'/publicProfileFromStudent/'.$username;
        header('Location:'.$location);
    }
        
    public static function publicProfileFromStudent(string $username)
    {
        $view = new VStudent();
        $PM=FPersistentManager::getInstance();
        $student=$PM->getStudentByUsername($username);
        $reviews = FReview::getInstance()->loadByRecipient($student->getId(), TType::STUDENT); //va fatto il metodo nel PM
        $reviewsData = [];
        
        foreach ($reviews as $review) {
            $profilePic = $PM->load('E'. $review->getAuthorType()->value, $review->getIdAuthor())->getPhoto();
            if ($profilePic === null) {
                $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
            }
            $reviewsData[] = [
                'title' => $review->getTitle(),
                'username' => $PM->load('E'. $review->getAuthorType()->value, $review->getIdAuthor())->getUsername(),
                'stars' => $review->getValutation(),
                'content' => $review->getDescription(),
                'userPicture' => $profilePic,
            ];
        }
        $view->publicProfileFromStudent($student, $reviewsData);
    }
    public static function publicProfileFromOwner(string $username)
    {
        $view = new VStudent();
        $PM=FPersistentManager::getInstance();
        $student=$PM->getStudentByUsername($username);
        $reviews = FReview::getInstance()->loadByRecipient($student->getId(), TType::STUDENT); //va fatto il metodo nel PM
        $reviewsData = [];
        
        foreach ($reviews as $review) {
            $profilePic = $PM->load('E'. $review->getAuthorType()->value, $review->getIdAuthor())->getPhoto();
            if ($profilePic === null) {
                $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
            }
            $reviewsData[] = [
                'title' => $review->getTitle(),
                'username' => $PM->load('E'. $review->getAuthorType()->value, $review->getIdAuthor())->getUsername(),
                'stars' => $review->getValutation(),
                'content' => $review->getDescription(),
                'userPicture' => $profilePic,
            ];
        }
        $view->publicProfileFromOwner($student, $reviewsData);
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
                    print '500: SERVER ERROR!';
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
                    print '500: SERVER ERROR!';
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
        #$number=USuperGlobalAccess::getPost('cardnumber1');
        $expiry=USuperGlobalAccess::getPost('expirydate1');
        $cvv=USuperGlobalAccess::getPost('cvv1');
        $name=USuperGlobalAccess::getPost('name1');
        $surname=USuperGlobalAccess::getPost('surname1');
        $oldNumber=USuperGlobalAccess::getPost('hiddenOldCard');
        $username=USession::getInstance()::getSessionElement('username');
        $studentId=FPersistentManager::getInstance()->getStudentIdByUsername($username);
        #print $title.' '.$number.' '.$expiry.' '.$cvv.' '.$name.' '.$surname.' '.$oldNumber;
        $PM=FPersistentManager::getInstance();
        if($PM->isMainCard($studentId,$oldNumber))
        {
            $c= new ECreditCard($oldNumber,$name,$surname,$expiry,$cvv,$studentId,true,$title);
            $result=$PM::update($c);
            if($result)
            {
                header('Location:/UniRent/Student/paymentMethods');
            }
            else
            {
                print '500 : SERVER ERROR';
            }
        }
        else
        {
            $c= new ECreditCard($oldNumber,$name,$surname,$expiry,$cvv,$studentId,false,$title);
            $result=$PM::update($c);
            if($result)
            {
               header('Location:/UniRent/student/paymentMethods');
            }
            else
            {
                print '500 : SERVER ERROR';
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
}