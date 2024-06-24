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
        $student = new EStudent('elisa', 'password', 'Elisa', 'Bianchi', null,'elisa.bianchi@univaq.it', 3, 2018, new DateTime('1998-05-12'), 'F', false, false);
        $view->profile($student);
    }
    public static function editProfile(){
        $view = new VStudent();
        $student = new EStudent('elisa', 'password', 'Elisa', 'Bianchi', null,'elisa.bianchi@univaq.it', 3, 2018, new DateTime('1998-05-12'), 'F', false, false);
        $view->editProfile($student);
    }


    public static function studentRegistration()
    {
        print 'inizio';
        $animals=USuperGlobalAccess::getPost('animals');
        $smoker=USuperGlobalAccess::getPost('smoker');
        print $smoker.'  '.$animals;
        $smok=filter_var($smoker,FILTER_VALIDATE_BOOLEAN);
        $anim=filter_var($animals, FILTER_VALIDATE_BOOLEAN);
        $duration = USuperGlobalAccess::getPost('courseDuration');
        $immatricolation = USuperGlobalAccess::getPost('immatricolationYear');
        $view= new VStudent();
        $PM=FPersistentManager::getInstance();
        $session=USession::getInstance();
        print $session::getSessionElement('email');
        
        if($PM->verifyStudentEmail($session::getSessionElement('email'))==true){

            print "Email presa dalla sessione";
           
            if($session->getSessionElement('picture')!=null){

                print "Foto presa dalla sessione";
                $photo = null;
                //$photo = new EPhoto(null, unserialize($session::getSessionElement('picture')),'student',null,null );
            }
            else{
                $photo = null;
            }
            $birthDate= new DateTime(USuperGlobalAccess::getPost('birthDate'));
            
            $student=new EStudent($session->getSessionElement('username'),
                                  $session->getSessionElement('password'),
                                  $session->getSessionElement('name'),
                                  $session->getSessionElement('surname'),
                                  $photo,
                                  $session->getSessionElement('email'),
                                  $duration,
                                  $immatricolation,
                                  $birthDate,
                                  USuperGlobalAccess::getPost('sex'),
                                  $smok,
                                  $anim);
            $PM->store($student);
            $session->setSessionElement('courseDuration', 3);
            $session->setSessionElement('immatricolationYear', 2021);
            $session->setSessionElement('birthDate', new DateTime(1999-06-01));
            $session->setSessionElement('sex', 'F');
            $session->setSessionElement('smoker', $smoker);
            $session->setSessionElement('animal', $animals);
            print 'Immaginati di stare nella home dello studente';
            header('Location:/UniRent/Student/home');
        }
        else
        {
            print "Email non stava in sessione";
            //$view->registrationError();
        }
    }
        
}