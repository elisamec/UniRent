<?php

namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Entity\EPhoto;
use Classes\Foundation\FPersistentManager;
use Classes\Entity\EStudent;
use Classes\Foundation\FReview;
use Classes\Foundation\FStudent;
use Classes\Tools\TType;
use Classes\Utilities\USession;
use Classes\Utilities\USuperGlobalAccess;
use Classes\View\VStudent; 
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

        if(is_null($student)){
            
            print '<b>500 : SERVER ERROR </b>';

        } else {   

            $ph = $student->getPicture();
            
            if(!is_null($ph)) {

                $ph=$ph->getPhoto();
                $base64 = base64_encode($ph);
                $ph = "data:" . 'image/jpeg' . ";base64," . $base64;
            }

            $view->profile($student, $ph);
        }
    }
    
    public static function editProfile(){
        $view = new VStudent();
        $student =FPersistentManager::getInstance()->getStudentByUsername(USession::getInstance()::getSessionElement('username'));
        $view->editProfile($student);
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
            $profilePic = FStudent::getInstance()->load($review->getIdAuthor())->getPicture();
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
    public static function reviews() {
        $view = new VStudent();
        $reviews = FReview::getInstance()->loadByRecipient(1, TType::STUDENT);
        $reviewsData = [];
        
        foreach ($reviews as $review) {
            $profilePic = FStudent::getInstance()->load($review->getIdAuthor())->getPicture();
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
        $view->reviews($reviewsData);
    }

    public static function modifyStudentProfile(){

        $session=USession::getInstance();

        //reed the data from the form
        $newUsername=USuperGlobalAccess::getPost('username');
        $name=USuperGlobalAccess::getPost('name');
        $surname=USuperGlobalAccess::getPost('surname');
        $password=USuperGlobalAccess::getPost('password');
        $newEmail=USuperGlobalAccess::getPost('email');
        $sex=USuperGlobalAccess::getPost('sex');
        $courseDuration=USuperGlobalAccess::getPost('courseDuration');
        $immatricolationYear=USuperGlobalAccess::getPost('immatricolationYear');
        $birthDate= new DateTime(USuperGlobalAccess::getPost('birthDate'));
        $smoker=$session::booleanSolver(USuperGlobalAccess::getPost('smoker'));
        $animals=$session::booleanSolver(USuperGlobalAccess::getPost('animals'));
        #print $smoker.'  '.$animals;

        $oldUsername=$session::getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        $oldEmail=$PM::getInstance()->getStudentEmailByUsername($oldUsername);

        if((($PM->verifyUserEmail($newEmail)==false)&&($PM->verifyStudentEmail($newEmail)))||($oldEmail===$newEmail)) #se la mail non è in uso ed è una mail universitaria oppure se non l'hai modificata
        {
            if(($PM->verifyUserUsername($newUsername)==false)||($oldUsername===$newUsername)) #se il nuovo username non è già in uso o non l'hai modificato
            {
                $studentID=$PM->getStudentIdByUsername($oldUsername);
                $photo=$PM->getStudentPhotoById($studentID);
                $student=new EStudent($newUsername,$password,$name,$surname,$photo,$newEmail,$courseDuration,$immatricolationYear,$birthDate,$sex,$smoker,$animals);
                $student->setID($studentID);
                $result=$PM->update($student);
                if($result)
                {
                    $session->setSessionElement('username',$newUsername);
                    $session->setSessionElement('password',$password);
                    header('Location:/UniRent/Student/profile');
                }
                else
                {
                    print '<h1><b>500 SERVER ERROR!</b></h1>';
                }
            }
            else
            {
                print '<b>Username già preso</b>';
                #header('Location:/UniRent/Student/profile');
            }
        }
        else
        {
            print '<b>mail già in uso o mail non universitaria</b>';
            #header('Location:/UniRent/Student/profile');
        }  
    }

    public static function publicProfile(string $username) {
        $PM=FPersistentManager::getInstance();
        $user=$PM->verifyUserUsername($username);
        if($user['type']==='Student')
        {
            self::publicProfileStudent($username);
        }
        else
        {
            self::publicProfileOwner($username);
        }
    }
        
    public static function publicProfileStudent(string $username)
    {
        $view = new VStudent();
        $PM=FPersistentManager::getInstance();
        $student=$PM->getStudentByUsername($username);
        $reviews = FReview::getInstance()->loadByRecipient($student->getId(), TType::STUDENT);
        $reviewsData = [];
        
        foreach ($reviews as $review) {
            $profilePic = FStudent::getInstance()->load($review->getIdAuthor())->getPicture();
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
        $view->publicProfileStudent($student, $reviewsData);
    }
    public static function publicProfileOwner(string $username)
    {
        $view = new VStudent();
        $PM=FPersistentManager::getInstance();
        $owner=$PM->getOwnerByUsername($username);


        $reviews = FReview::getInstance()->loadByRecipient($owner->getId(), TType::OWNER);
        $reviewsData = [];
        
        foreach ($reviews as $review) {
            $profilePic = FStudent::getInstance()->load($review->getIdAuthor())->getPicture();
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
        $view->publicProfileOwner($owner, $reviewsData);
    }
}