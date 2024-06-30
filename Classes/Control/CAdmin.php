<?php

namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Foundation\FPersistentManager;
use Classes\Utilities\USession;
use Classes\View\VAdmin;
use Classes\Utilities\USuperGlobalAccess;
use Classes\Utilities\UCookie;

class CAdmin
{
    public static function home(){
        $view = new VAdmin();
        $view->home();
    }

    public static function login(){
        if(UCookie::isSet('PHPSESSID'))
        {
            if(session_status() == PHP_SESSION_NONE)
            {
                $session = USession::getInstance();
            }
        }
        /*if($session::isSetSessionElement('userType')){
            header('Location: /Agora/Student/home');
        }*/
        $view = new VAdmin();
        $view->login();
    }
    public static function checkLogin()
    {

        $view = new VAdmin();
        $username=USuperGlobalAccess::getPost('username');
        
        //If user exist, get the user and check the password
        if($username === 'Admin')
        {
                $passwordIn=USuperGlobalAccess::getPost('password');

                if(password_verify($passwordIn, '$2y$10$oqDyOSQOyj8bBbbeq1UFfe5B.zB/HGenmr9IRQnzGSBI5eRrHRF5i'))
                {
                    print 'La password è corretta!';
                   
                    $session = USession::getInstance();
                    $session::setSessionElement("userType", 'Admin');
                    $session::setSessionElement('username', $username);
                    $session::setSessionElement('password',USuperGlobalAccess::getPost('password'));
                    header('Location:/UniRent/Admin/home'); 
                }
                else  #password is not correct
                {
                    $view->loginError(false, true);
                }
            }
        else  #dose not exist an username for that type
        {
            $view->loginError(true, false);
        }
    }
}