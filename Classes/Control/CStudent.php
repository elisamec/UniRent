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
     * @return void
     */
    public static function profile(){
        $view = new VStudent();
        $session=USession::getInstance();
        $user = $session->getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        $student=$PM->getStudentByUsername($user);
        if(is_null($student))
        {
            print '<b>500 : SERVER ERROR </b>';
        }
        else
        {
            $view->profile($student);
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
        
        if ($session->getSessionElement('picture')===null) {

            $photo = null;

        }else {
            
            $photo = null;
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

        $PM->store($student);
        $session->setSessionElement('courseDuration', $duration);
        $session->setSessionElement('immatricolationYear', $immatricolation);
        $session->setSessionElement('birthDate', $birthDate);
        $session->setSessionElement('sex', $sex);
        $session->setSessionElement('smoker', $smok);
        $session->setSessionElement('animal', $anim);
        header('Location:/UniRent/Student/home');

    }
    public static function accommodation() {
        $view = new VStudent();
        $accomm = FPersistentManager::getInstance()->load('EAccommodation', 2);
        $view->accommodation($accomm);
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

    public static function modifyStudentProfile()
    {
        $name=USuperGlobalAccess::getPost('name');
        $surname=USuperGlobalAccess::getPost('surname');
        $password=USuperGlobalAccess::getPost('password');
        $email=USuperGlobalAccess::getPost('email');
        $sex=USuperGlobalAccess::getPost('sex');
        $courseDuration=USuperGlobalAccess::getPost('courseDuration');
        $immatricolationYear=USuperGlobalAccess::getPost('immatricolationYear');
        $birthDate= new DateTime(USuperGlobalAccess::getPost('birthDate'));
        $smoker=USuperGlobalAccess::getPost('smoker');
        $animals=USuperGlobalAccess::getPost('animals');
        print $birthDate->format('Y-m-d');
    }
        
}