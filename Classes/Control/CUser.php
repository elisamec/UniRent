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
        $mail = USuperGlobalAccess::getPost('email');

        if($PM->verifyUserEmail($mail)==false && $PM->verifyUserUsername(USuperGlobalAccess::getPost('username'))==false)
        {
            $session=USession::getInstance();
            $session::setSessionElement('email', $mail);
            $session::setSessionElement('username',USuperGlobalAccess::getPost('username'));
            $session::setSessionElement('password',USuperGlobalAccess::getPost('password'));
            $session::setSessionElement('name',USuperGlobalAccess::getPost('name'));
            $session::setSessionElement('surname',USuperGlobalAccess::getPost('surname'));
            $session::setSessionElement('picture',USuperGlobalAccess::getFiles('img'));


            if($type==='Student'){

                $viewStudent->showStudentRegistration();
            }else{  
 
                $viewOwner->showOwnerRegistration();
            }
        }  
        else
        {   print "Utente con tale email o username già registrato";
            //$view->reggistrationError();
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

        //If user exist, get the user and check the password
        if($userId != false){

            //Type can be Student or Owner
            $user = $PM->load("E$type", $userId);

            if(password_verify(USuperGlobalAccess::getPost('password'), $user->getPassword())){

                if(USession::getSessionStatus() == PHP_SESSION_NONE){
                    USession::getInstance();
                    USession::setSessionElement("$type", $userId);
                    if($type === 'Student')header('Location:/UniRent/Student/home');
                    else print  header('Location:/UniRent/Owner/home');
                    //else $viewOwner->home();
                }

            }else{
                print "Password non corretta"; 
                //$view->loginError();
            }

        }else{
            print "Username non corretto";
            //$view->loginError();
        }

    }

    /**
     * this method can logout the User, unsetting all the session element and destroing the session. Return the user to the Login Page
     * @return void
     */
    public static function logout(){
        USession::getInstance();
        USession::unsetSession();
        USession::destroySession();
        header('Location: /UniRent/User/home');
    }

}