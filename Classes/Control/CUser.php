<?php

namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Foundation\FPersistentManager;
use Classes\Utilities\UAccessUniversityFile;
use Classes\Utilities\USession;
use Classes\View\VUser; 
use Classes\View\VStudent;
use Classes\View\VOwner;
use Classes\Utilities\USuperGlobalAccess;
use Classes\Utilities\UCookie;
use Classes\View\VError;
use Classes\Tools\TStatusUser;

class CUser
{
    
    public static function home(){
        $session=USession::getInstance();
        $type=$session::getSessionElement('userType');
        $id=$session::getSessionElement('id');
        $PM=FPersistentManager::getInstance();
        if($type==='Student')
        {
            $student=$PM->load('EStudent', $id);
            if($student->getStatus()==TStatusUser::BANNED)
            {
                $v=new VError();
                $v->error(600, $student->getUsername(), 'null', $PM->getBanReason($student->getUsername()));
                return;
            }
            else
            {
                header('Location: /UniRent/Student/home');
            }
        }
        elseif($type==='Owner')
        {
            $owner=$PM->load('EOwner',$id);
            if($owner->getStatus()==TStatusUser::BANNED)
            {
                $v=new VError();
                $v->error(600, $owner->getUsername(), 'null', $PM->getBanReason($owner->getUsername()));
                return;
            }
            else
            {
                header('Location: /UniRent/Owner/home');
            }
        }
        else{
        $view = new VUser();
        $accommodations = $PM->lastAccommodationsUser();
        $view->home($accommodations);}
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
    public static function search(){
        $view = new VUser();
        $city=USuperGlobalAccess::getPost('city');
        $date=USuperGlobalAccess::getPost('date');
        $university=USuperGlobalAccess::getPost('university');
        if (USuperGlobalAccess::getPost('rateA')!== null) {
            $rateA=USuperGlobalAccess::getPost('rateA');
        } else {
            $rateA=0;
        }
        if (USuperGlobalAccess::getPost('rateO')!== null) {
            $rateO=USuperGlobalAccess::getPost('rateO');
        } else {
            $rateO=0;
        }
        if (USuperGlobalAccess::getPost('min-price')!== null) {
            $minPrice=USuperGlobalAccess::getPost('min-price');
        } else {
            $minPrice=0;
        }
        if (USuperGlobalAccess::getPost('max-price')!== null) {
            $maxPrice=USuperGlobalAccess::getPost('max-price');
        } else {
            $maxPrice=1000;
        }
        $PM=FPersistentManager::getInstance();
        $searchResult=$PM->findAccommodationsUser($city,$date,$rateA,$rateO,$minPrice,$maxPrice);
        /*$searchResult= [
            0 => [
                'title' => 'Appartamento in centro',
                'price' => '500',
                'photo' => null,
                'address' => 'Via Roma 1, Milano',
            ]
        ];*/
        $view->findAccommodation($city,$university,$searchResult,$date, $rateO, $rateA, $minPrice, $maxPrice);
    }

    public static function getCities()
    {
        $UF=UAccessUniversityFile::getInstance();
        $result=$UF->getCities();
        header('Content-Type: application/json');
        echo json_encode($result);
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
        $picture = USuperGlobalAccess::getPhoto('img');

        if($PM->verifyUserEmail($mail)==false && $PM->verifyUserUsername(USuperGlobalAccess::getPost('username'))==false)
        {
            http_response_code(500);
            $session=USession::getInstance();
            $session::setSessionElement('email', $mail);
            $session::setSessionElement('username', $username);
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&()])[A-Za-z\d@$!%*?&()]{8,}$/' , $password)) {
                $view->registrationError(false, false, false, true, $username, $mail, $name, $surname, $type);
            }
            $session::setSessionElement('password',USuperGlobalAccess::getPost('password'));
            $session::setSessionElement('name',$name);
            $session::setSessionElement('surname',$surname);
            $session::setSessionElement('picture',$picture);
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
        if($result_username_array != false){

            if($result_username_array['type']==$type) { //if exist an username for that type
            
                $user = $PM->load("E$type", $result_username_array['id']);
                if($user->getStatus()==TStatusUser::BANNED)
                {
                    $banReason=$PM->getBanReason($username);
                    $v=new VError();
                    $v->error(600, $username, 'null', $banReason);
                    return;
                }
                $passwordIn=USuperGlobalAccess::getPost('password');
                if(password_verify($passwordIn, $user->getPassword())){
                   
                    $session = USession::getInstance();
                    $session::setSessionElement("id", $result_username_array['id']);
                    $session::setSessionElement("userType", $type);
                    $session::setSessionElement('username', $username);
                    $session::setSessionElement('password', $passwordIn);
    
                    $type === 'Student' ? header('Location:/UniRent/Student/home') : header('Location:/UniRent/Owner/home');
  
                } else { //password is not correct
                
                    $view->loginError(true, false, $username, $type);
                }
                
            } else  { //doesn't exist an username for that type
               
                $view->loginError(false, true, $username, $type);
            }

        } else {#user dose not exist
        
           $view->loginUsernameError(false, true, $type);
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