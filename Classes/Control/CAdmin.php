<?php

namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Entity\EStudent;
use Classes\Entity\ESupportRequest;
use Classes\Foundation\FPersistentManager;
use Classes\Tools\TType;
use Classes\Utilities\USession;
use Classes\View\VAdmin;
use Classes\Utilities\USuperGlobalAccess;
use Classes\Utilities\UCookie;
use Classes\View\VError;

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
        else  #does not exist an username for that type
        {
            $view->loginError(true, false);
        }
    }
    public static function logout()
    {
        $session = USession::getInstance();
        $session::destroySession();
        header('Location:/UniRent/Admin/login');
    }
    public static function report(int $id, string $type)
    {
        $PM = FPersistentManager::getInstance();
        $session = USession::getInstance();
        $userType  = $session::getSessionElement('userType');
        if ($type == 'Student')
        {
            $student = $PM::load('EStudent', $id);
            $student->setStatus('reported');
            $res=$PM::update($student);
            if ($res)
            {
                header('Location:' . ucfirst($userType) . '/publicProfile/' . $student->getUsername() . '/reported');
            }
            else
            {
                header('Location:' . ucfirst($userType) . '/publicProfile/' . $student->getUsername() . '/error');
            }
        }
        else
        {
            $owner = $PM::load('EOwner', $id);
            $owner->setStatus('reported');
            $res=$PM::update($owner);
            if ($res)
            {
                header('Location:' . ucfirst($userType) . '/publicProfile/' . $owner->getUsername() . '/reported');
            }
            else
            {
                header('Location:' . ucfirst($userType) . '/publicProfile/' . $owner->getUsername() . '/error');
            }
        }
    }

    public static function supportRequest()
    {
        $session=USession::getInstance();
        $idUser=$session::getSessionElement('id');
        $type=$session::getSessionElement('userType');
        if ($type=='Student')
        {
            $type=TType::STUDENT;
        }
        else
        {
            $type=TType::OWNER;
        }
        $topic=USuperGlobalAccess::getPost('topic');
        $message=USuperGlobalAccess::getPost('message');
        $supportRequest=new ESupportRequest(0,$message,$topic,$idUser,$type);
        $PM=FPersistentManager::getInstance();
        $res=$PM::store($supportRequest);
        if ($res)
        {
            header('Location:/UniRent/'.ucfirst($type->value).'/contact/sent');
        }
        else
        {
            header('Location:/UniRent/'.ucfirst($type->value).'/contact/fail');
        }
    }
}