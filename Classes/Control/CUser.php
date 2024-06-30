<?php

namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Foundation\FPersistentManager;
use Classes\Utilities\USession;
use Classes\View\VUser; 
use Classes\View\VStudent;
use Classes\View\VOwner;
use Classes\Utilities\USuperGlobalAccess;
use Classes\Utilities\UCookie;

class CUser
{
    
    public static function home(){
        $view = new VUser();
        $view->home();
    }
    public static function about(){
        $view = new VUser();
        $view->about();
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
        $view = new VUser();
        $view->login();
    }

    public static function register(){
        $view = new VUser();
        $view->register();
    }
    public static function contact(){
        $view = new VUser();
        $view->contact();
    }
    public static function findAccommodation(){
        $view = new VUser();
        $view->findAccommodation();
    }


    public static function showRegistration()
    {
        $view= new VUser();
        $viewStudent = new VStudent();
        $viewOwner = new VOwner();
        $PM=FPersistentManager::getInstance();
        $type = USuperGlobalAccess::getPost('userType');
        $password = USuperGlobalAccess::getPost('password');
        $mail = USuperGlobalAccess::getPost('email');
        $username = USuperGlobalAccess::getPost('username');
        $name = USuperGlobalAccess::getPost('name');
        $surname = USuperGlobalAccess::getPost('surname');

        
        if($PM->verifyUserEmail($mail)==false && $PM->verifyUserUsername(USuperGlobalAccess::getPost('username'))==false)
        {
            $session=USession::getInstance();
            $session::setSessionElement('email', $mail);
            $session::setSessionElement('username', $username);
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&()])[A-Za-z\d@$!%*?&()]{8,}$/' , $password)) {
                $view->registrationError(false, false, false, true, $username, $mail, $name, $surname, $type);
            }
            $session::setSessionElement('password',USuperGlobalAccess::getPost('password'));
            $session::setSessionElement('name',$name);
            $session::setSessionElement('surname',$surname);
            $session::setSessionElement('picture',USuperGlobalAccess::getFiles('img'));
            $session::setSessionElement('userType',$type);

            if($type==='Student'){
                if($PM->verifyStudentEmail($session::getSessionElement('email'))==true){

                    $viewStudent->showStudentRegistration();

                }else{
                    $view->registrationError(false, false, true, false, $username, "", $name, $surname, $type);
                }
            }else{  
 
                $viewOwner->showOwnerRegistration();
            }
        } elseif ($PM->verifyUserUsername(USuperGlobalAccess::getPost('username'))==true && $PM->verifyUserEmail($mail)==true) {
            $view->registrationError(true, true, false, false, "", "", $name, $surname, $type);
        }
        elseif ($PM->verifyUserEmail($mail)==true) {
            $view->registrationError(true, false, false, false, $username, "", $name, $surname, $type);
        }  else {
            $view->registrationError(false, true, false, false, "", $mail, $name, $surname, $type);
        }
    }

    public static function checkLogin()
    {

        $view = new VUser();
        $viewStudent = new VStudent();
        $viewOwner = new VOwner();
        $username=USuperGlobalAccess::getPost('username');
        $type = USuperGlobalAccess::getPost('userType');
        $PM = FPersistentManager::getInstance();
        $result_username_array = $PM->verifyUserUsername($username);
        
        //If user exist, get the user and check the password
        if($result_username_array != false)
        {

            if($result_username_array['type']==$type) #if exist an username for that tipe
            {
                $user = $PM->load("E$type", $result_username_array['id']);
                $passwordIn=USuperGlobalAccess::getPost('password');

                if(password_verify($passwordIn, $user->getPassword()))
                {
                    print 'La password è corretta!';
                   
                    $session = USession::getInstance();
                    $session::setSessionElement("id", $result_username_array['id']);
                    $session::setSessionElement("userType", $type);
                    $session::setSessionElement('username', $username);
                    $session::setSessionElement('password',USuperGlobalAccess::getPost('password'));
                    if($type === 'Student')
                    {
                        header('Location:/UniRent/Student/home');
                    }
                    else 
                    {
                        header('Location:/UniRent/Owner/home');
                    }  
                }
                else  #password is not correct
                {
                    $view->loginError(true, false, false, $username, $type);
                }
            }
            else  #dose not exist an username for that type
            {
                $view->loginError(false, true, false, $username, $type);
            }

        }
        else  #user dose not exist
        {
           $view->loginUsernameError(false, true, false, $type);
        }
    }

    /**
     * this method can logout the User, unsetting all the session element and destroing the session. Return the user to the Login Page
     * @return void
     */
    public static function logout()
    {
        $session=USession::getInstance();
        $session::unsetSession();
        $session::destroySession();
        setcookie('PHPSESSID','',time()-3600,'/','',isset($_SERVER["HTTPS"]),true);
        header('Location: /UniRent/User/home');
    }

}