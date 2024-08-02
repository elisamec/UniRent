<?php

namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Entity\EStudent;
use Classes\Entity\ESupportRequest;
use Classes\Foundation\FPersistentManager;
use Classes\Tools\TRequestType;
use Classes\Tools\TStatusUser;
use Classes\Tools\TType;
use Classes\Utilities\USession;
use Classes\View\VAdmin;
use Classes\Utilities\USuperGlobalAccess;
use Classes\Utilities\UCookie;
use Classes\View\VError;
use Classes\Utilities\UAccessUniversityFile;

class CAdmin
{
    public static function home(){
        $PM=FPersistentManager::getInstance();
        $stats=$PM->getStatistics();
        $view = new VAdmin();
        $view->home($stats);
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
            $student = $PM->load('EStudent', $id);
            $student->setStatus('reported');
            $res=$PM->update($student);
            if ($res)
            {
                header('Location:/UniRent/' . ucfirst($userType) . '/publicProfile/' . $student->getUsername() . '/reported');
            }
            else
            {
                header('Location:/UniRent/' . ucfirst($userType) . '/publicProfile/' . $student->getUsername() . '/error');
            }
        }
        else
        {
            $owner = $PM->load('EOwner', $id);
            $owner->setStatus('reported');
            $res=$PM->update($owner);
            if ($res)
            {
                header('Location:/UniRent/' . ucfirst($userType) . '/publicProfile/' . $owner->getUsername() . '/reported');
            }
            else
            {
                header('Location:/UniRent/' . ucfirst($userType) . '/publicProfile/' . $owner->getUsername() . '/error');
            }
        }
    }

    public static function supportRequest()
    {
        $session=USession::getInstance();
        if (!$session::isSetSessionElement('id'))
        {
            $topic=TRequestType::BUG;
            $message=USuperGlobalAccess::getPost('Message');
            $supportRequest=new ESupportRequest(0,$message,$topic,null,null);
            $PM=FPersistentManager::getInstance();
            $res=$PM->store($supportRequest);
            if ($res)
            {
                header('Location:/UniRent/User/contact/sent');
            }
            else
            {
                header('Location:/UniRent/User/contact/fail');
            }
        } else {
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
            $message=USuperGlobalAccess::getPost('Message');
            $supportRequest=new ESupportRequest(0,$message,$topic,$idUser,$type);
            $PM=FPersistentManager::getInstance();
            $res=$PM->store($supportRequest);
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
    public static function ban(int $id, string $type)
    {
        $PM = FPersistentManager::getInstance();
        $user=$PM->load('E'.ucfirst($type), $id);
        $user->setStatus(TStatusUser::BANNED);
        if (ucfirst($type)==='Owner') {
            $accommodationArray=$PM->loadAccommodationsByOwner($id);
            foreach ($accommodationArray as $accommodation) {
                $accommodation->setStatus(false);
                $PM->update($accommodation);
            }
        }
        $res=$PM->update($user);
        if ($res)
        {
            #header('Location:/UniRent/Admin/home/banned');
        }
        else
        {
            #header('Location:/UniRent/Admin/home/error');
        }
    }
    public static function studentEmailIssue() {
        $mail = USuperGlobalAccess::getPost('emailIssue');
        $supportRequest= new ESupportRequest(null, 'A student is trying to register with the following email, which is not accepted by the system: '. $mail, TRequestType::REGISTRATION, null, null);
        $PM=FPersistentManager::getInstance();
        $res=$PM->store($supportRequest);
        if ($res)
        {
            header('Location:/UniRent/User/showRegistration/issueSent');
        }
        else
        {
            header('Location:/UniRent/User/showRegistration/error');
        }
    }
    public static function removeBanRequest(string $username) {
        $PM=FPersistentManager::getInstance();
        $topic=TRequestType::REMOVEBAN;
        $user= $PM->verifyUserUsername($username);
        $message=USuperGlobalAccess::getPost('Message');
        $supportRequest=new ESupportRequest(null,$message, $topic, $user['id'], $user['type']);
        $res=$PM->store($supportRequest);
        $res=true;
        if ($res)
        {
            $view=new VError();
            $view->error(600, $username, 'requestSent');
        }
        else
        {
            $view=new VError();
            $view->error(600, $username, 'error');
        }
    }

     /**
     * Method getStatistics
     * 
     * this method gets the statistics for administrator from DataBase
     *
     * @return void
     */
    public static function getStatistics()
    {
        $view = new VAdmin();
        $PM=FPersistentManager::getInstance();
        $result = $PM->getStatistics();
        print_r($result); #momentaneo, da collegare con view
    }
    
    /**
     * Method active
     * 
     * this method permits the administrator to make active a Owner or a Student
     *
     * @param string $type [Owner/Student]
     * @param int $id [Owner/Student id]
     *
     * @return void
     */
    public static function active(string $type, int $id)
    {
        $PM = FPersistentManager::getInstance();
        $user = $PM->load('E'.ucfirst($type), $id);
        $user->setStatus(TStatusUser::ACTIVE);
        $res = $PM->update($user);
        if ($res)
        {
            header('Location:/UniRent/Admin/home/active');
        }
        else
        {
            header('Location:/UniRent/Admin/home/error');
        }
    }
    
    /**
     * Method bannedList
     * 
     * this method return an associavie array with the list of Student and Owner banned
     *
     * @return void
     */
    public static function bannedList()
    {
        $view = new VAdmin();
        $PM=FPersistentManager::getInstance();
        $result=$PM->getBannedList();
        print_r($result); #temporaneo bisogna mettere una vista
    }

    /**
     * Method verifyEmail
     * If an email isn't in the list of the universities, it will be added by admin
     * 
     * @param string $email [email to verify]
     * @param string $uniName [name of the university]
     * @param string $city [city of the university]
     * 
     * @return void
     */
    public static function verifyEmail(string $email, string $uniName, string $city):void{

        $email = explode(".", str_replace("@", ".", $email));
        $domain = array_slice($email, -2);
        $domain = "www." . $domain[0] . "." . $domain[1];

        $AUF=UAccessUniversityFile::getInstance();
        $AUF->addElement($domain, $uniName, $city);        

    }

}