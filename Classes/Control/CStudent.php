<?php

namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Entity\EPhoto;
use Classes\Foundation\FPersistentManager;
use Classes\Entity\EStudent;
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
    public static function profile(){
        $view = new VStudent();
        $session=USession::getInstance();
        $user = $session->getSessionElement('username');
        $password = $session->getSessionElement('password');
        $name = $session->getSessionElement('name');
        #print $user.' '.$password.' '.$name;
        $surname = $session->getSessionElement('surname');
        //$photo
        $email = $session->getSessionElement('email');
        $duration = $session->getSessionElement('courseDuration');
        $immatricolation = $session->getSessionElement('immatricolationYear');
        $birthDate = $session->getSessionElement('birthDate');
        $sex = $session->getSessionElement('sex');
        $smoker =USession::booleanSolver($session->getSessionElement('smoker')) ;
        $animals = USession::booleanSolver($session->getSessionElement('animal'));
        #print $user.' '.$password.' '.$name.' '.$surname.' '.$email.' '.$duration.' '.$immatricolation.' '.$sex.' '.$smoker.' '.$animals;
        $student = new EStudent($user, $password, $name, $surname, null, $email, $duration, $immatricolation, $birthDate, $sex, $smoker, $animals);
        #print $student->__toString();
        #$student = new EStudent('elisa', 'password', 'Elisa', 'Bianchi', null,'elisa.bianchi@univaq.it', 3, 2018, new DateTime('1998-05-12'), 'F', false, false);
        $view->profile($student);
    }
    
    public static function editProfile(){
        $view = new VStudent();
        $student = new EStudent('elisa', 'password', 'Elisa', 'Bianchi', null,'elisa.bianchi@univaq.it', 3, 2018, new DateTime('1998-05-12'), 'F', false, false);
        $view->editProfile($student);
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
        
}