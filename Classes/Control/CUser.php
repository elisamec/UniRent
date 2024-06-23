<?php

namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Foundation\FPersistentManager;
use Classes\Utilities\USession;
use Classes\View\VUser; 
use Classes\View\VStudent;
use Classes\View\VOwner;
use Classes\Utilities\USuperGlobalAccess;

class CUser
{
    
    public static function home(){
        $view = new VUser();
        $view->home();
    }

    public static function login(){
        $view = new VUser();
        $view->login();
    }

    public static function register(){
        $view = new VUser();
        $view->register();
    }


    public static function showRegistration()
    {
        $view= new VUser();
        $viewStudent = new VStudent();
        $viewOwner = new VOwner();
        $PM=FPersistentManager::getInstance();

        if($PM->verifyUserEmail(USuperGlobalAccess::getPost('email'))==false && $PM->verifyUserUsername(USuperGlobalAccess::getPost('username'))==false)
        {
            $session=USession::getInstance();
            $session::setSessionElement('email',USuperGlobalAccess::getPost('email'));
            $session::setSessionElement('username',USuperGlobalAccess::getPost('username'));
            $session::setSessionElement('password',USuperGlobalAccess::getPost('password'));
            $session::setSessionElement('name',USuperGlobalAccess::getPost('name'));
            $session::setSessionElement('surname',USuperGlobalAccess::getPost('surname'));
            $session::setSessionElement('picture',serialize(USuperGlobalAccess::getPost('picture')));
            if(USuperGlobalAccess::getPost('student/owner')==='student')
            {
                $viewStudent->showStudentRegistration();
            }
            else
            {   
                $viewOwner->showOwnerRegistration();
            }
        }  
        else
        {
            $view->reggistrationError();
        }   
    }

    public static function checkLogin(){

        $view = new VUser();
        $type = USuperGlobalAccess::getPost('student/owner');
        $PM = FPersistentManager::getInstance();

        $username = $PM->verifyUserUsername(USuperGlobalAccess::getPost('username')); 

        if($username != false){
            
            $user = $PM->load($type, $username);
            if(password_verify(USuperGlobalAccess::getPost('password'), $user->getPassword())){

                if(USession::getSessionStatus() == PHP_SESSION_NONE){
                    USession::getInstance();
                    USession::setSessionElement("$type", $user->getId());
                    $type === 'Student' ? $view->showStudentHome() : $view->showOwnerHome();
                }

            }else{
                $view->loginError();
            }
        }else{
            $view->loginError();
        }

    }

}