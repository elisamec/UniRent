<?php

namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Foundation\FPersistentManager;
use Classes\Utilities\USession;
use Classes\View\VUser; 
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

    public static function registration()
    {
        $view= new VUser();
        $PM=FPersistentManager::getInstance();
        if($PM->verifyUserEmail(USuperGlobalAccess::getPost('email')) && $PM->verifyUserUsername(USuperGlobalAccess::getPost('username')))
        {
            $session=USession::getInstance();
            $session::setSessionElement('email',USuperGlobalAccess::getPost('email'));
            $session::setSessionElement('username',USuperGlobalAccess::getPost('username'));
            $session::setSessionElement('password',USuperGlobalAccess::getPost('password'));
            $session::setSessionElement('name',USuperGlobalAccess::getPost('name'));
            $session::setSessionElement('surname',USuperGlobalAccess::getPost('surname'));
            $session::setSessionElement('picture',serialize(USuperGlobalAccess::getPost('picture')));
            if(USuperGlobalAccess::getPost('studente/owner')==='student')
            {
               $view->showStudentRegistration();
            }
            else
            {
                $view->showOwnerRegistration();
            }
        }  
        else
        {
            $view->reggistrationError();
        }   
    }
}