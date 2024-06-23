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
        $type = USuperGlobalAccess::getPost('userType');

        if($PM->verifyUserEmail(USuperGlobalAccess::getPost('email'))==false && $PM->verifyUserUsername(USuperGlobalAccess::getPost('username'))==false)
        {
            $session=USession::getInstance();
            $session::setSessionElement('email',USuperGlobalAccess::getPost('email'));
            $session::setSessionElement('username',USuperGlobalAccess::getPost('username'));
            $session::setSessionElement('password',USuperGlobalAccess::getPost('password'));
            $session::setSessionElement('name',USuperGlobalAccess::getPost('name'));
            $session::setSessionElement('surname',USuperGlobalAccess::getPost('surname'));
            $session::setSessionElement('picture',serialize(USuperGlobalAccess::getPost('img')));
            if(USuperGlobalAccess::getPost('userType')==='Student')
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
        $viewStudent = new VStudent();
        $viewOwner = new VOwner();
        $type = USuperGlobalAccess::getPost('userType');
        $PM = FPersistentManager::getInstance();
        $userId = false;

        //Get id of user based on him username
        if($type === 'Student' || $type === 'Owner') {
            $userId = $PM->verifyUserUsername(USuperGlobalAccess::getPost('username')); 
        }

        //If the id is not false, get the user and check the password
        if($userId != false){

            //Type can be Student or Owner
            $user = $PM->load($type, $userId);

            if(password_verify(USuperGlobalAccess::getPost('password'), $user->getPassword())){

                if(USession::getSessionStatus() == PHP_SESSION_NONE){
                    USession::getInstance();
                    USession::setSessionElement("$type", $userId);
                    if($type === 'Student') $viewStudent->home();
                    else $viewOwner->home();
                }

            }else{
                $view->loginError();
            }

        }else{
            $view->loginError();
        }

    }

}