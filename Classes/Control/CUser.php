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
    /**
     * Method home
     * 
     * this method is used to redirect the user to the correct home page
     * @param mixed $modalSuccess
     * @return void
     */
    public static function home(?string $modalSuccess=null){
        $session=USession::getInstance();
        $type=$session->getSessionElement('userType');
        $PM=FPersistentManager::getInstance();
        if($type==='Student')
        {
            header('Location: /UniRent/Student/home');
        }
        elseif($type==='Owner')
        {
            header('Location: /UniRent/Owner/home');
        }
        else{
            $view = new VUser();
            $accommodations = $PM->lastAccommodationsUser();
            $view->home($accommodations, $modalSuccess);
        }
    }
    
    /**
     * Method about
     *
     * this method is used to show the about page
     * @return void
     */
    public static function about(){
        $view = new VUser();
        $view->about();
    }

    /**
     * Method guidelines
     *
     * this method is used to show the guidelines page
     * @return void
     */
    public static function guidelines(){
        $view = new VUser();
        $view->guidelines();
    }

    /**
     * Method login
     *
     * this method is used to show the login page
     * @return void
     */
    public static function login(){
        if(UCookie::isSet('PHPSESSID'))
        {
            if(session_status() == PHP_SESSION_NONE)
            {
                $session = USession::getInstance();
            }
        }
        $view = new VUser();
        $view->login();
    }

    /**
     * Method register
     *
     * this method is used to show the registration page
     * @param mixed $modalSuccess
     * @return void
     */
    public static function register(?string $modalSuccess=null){
        $view = new VUser();
        $view->register($modalSuccess);
    }

    /**
     * Method contact
     *
     * this method is used to show the contact page
     * @param mixed $modalSuccess
     * @return void
     */
    public static function contact(?string $modalSuccess=null){
        $view = new VUser();
        $view->contact($modalSuccess);
    }
    
    /**
     * Method search
     *
     * used by a non logged user to search for an accommodation
     * @return void
     */
    public static function search(){
        $view = new VUser();
        $city = USuperGlobalAccess::getPost('city');
        $date = USuperGlobalAccess::getPost('date');
        $year = USuperGlobalAccess::getPost('year');
        $university = USuperGlobalAccess::getPost('university');
        $rateA = USuperGlobalAccess::getPost('rateA') ?? 0;
        $rateO = USuperGlobalAccess::getPost('rateO') ?? 0;
        $minPrice = USuperGlobalAccess::getPost('min-price') ?? 0;
        $maxPrice = USuperGlobalAccess::getPost('max-price') ?? 1000;
        $PM=FPersistentManager::getInstance();
        $searchResult=$PM->findAccommodationsUser($city,$date,$rateA,$rateO,$minPrice,$maxPrice,$year);
        $view->findAccommodation($city,$university,$searchResult,$date, $rateO, $rateA, $minPrice, $maxPrice,$year);
    }
    
    /**
     * Method getCities
     *
     * method used to get an array  with key=city and value=array of universities
     * @return void
     */
    public static function getCities()
    {
        $UF=UAccessUniversityFile::getInstance();
        $result=$UF->getCities();
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * Method showRegistration
     *
     * method used to show the specific registration page, or show an error if something is wrong
     * @return void
     */
    public static function showRegistration(?string $modalSuccess=null)
    {
        $view= new VUser();
        $viewStudent = new VStudent();
        $viewOwner = new VOwner();
        $PM=FPersistentManager::getInstance();
        $username=USuperGlobalAccess::getPost('username');
        $mail=USuperGlobalAccess::getPost('email');
        $name=USuperGlobalAccess::getPost('name');
        $surname=USuperGlobalAccess::getPost('surname');
        $type=USuperGlobalAccess::getPost('userType');
        $password=USuperGlobalAccess::getPost('password');
        $picture = USuperGlobalAccess::getPhoto('img');
        $verify = !is_null($mail) && (!is_null($username));

        if($verify && $PM->verifyUserEmail($mail)==false && $PM->verifyUserUsername(USuperGlobalAccess::getPost('username'))==false)
        {
            $session=USession::getInstance();
            $session->setSessionElement('email', $mail);
            $session->setSessionElement('username', $username);
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&()])[A-Za-z\d@$!%*?&()]{8,}$/' , $password)) {
                $view->registrationError(false, false, false, true, $username, $mail, $name, $surname, $type, $modalSuccess);
                exit();
            }
            $password = password_hash($password, PASSWORD_DEFAULT);
            $session->setSessionElement('password',$password);
            $session->setSessionElement('name',$name);
            $session->setSessionElement('surname',$surname);
            $session->setSessionElement('picture',$picture);
            $session->setSessionElement('userType',$type);
            if($type==='Student'){
                if($PM->verifyStudentEmail($session->getSessionElement('email'))==true){
                    $viewStudent->showStudentRegistration();
                }else{
                    $view->registrationError(false, false, true, false, $username, "", $name, $surname, $type, $modalSuccess);
                    exit();
                }
            }else{  
                $viewOwner->showOwnerRegistration();
            }
        } elseif ($PM->verifyUserUsername(USuperGlobalAccess::getPost('username'))==true && $PM->verifyUserEmail($mail)==true) {
            $view->registrationError(true, true, false, false, "", "", $name, $surname, $type, $modalSuccess);
        }
        elseif ($PM->verifyUserEmail($mail)==true) {
            $view->registrationError(true, false, false, false, $username, "", $name, $surname, $type, $modalSuccess);
        }  else {
            $view->registrationError(false, true, false, false, "", $mail, $name, $surname, $type, $modalSuccess);
        }
    }

    /**
     * Method checkLogin
     *
     * method used to log the user in, if the user is banned, show an error
     * @return void
     */
    public static function checkLogin()
    {
        $view = new VUser();
        $username=USuperGlobalAccess::getPost('username');
        $type=USuperGlobalAccess::getPost('userType');
        $PM = FPersistentManager::getInstance();
        $result_username_array = $PM->verifyUserUsername($username);
        //If user exist, get the user and check the password
        if($result_username_array != false){
            if($result_username_array['type']==$type) { //if exist an username for that type
                $user = $PM->load("E$type", $result_username_array['id']);
                if($user->getStatus()==TStatusUser::BANNED){
                    $banReason=$PM->getLastBanReport($username)->getDescription();
                    $v=new VError();
                    $v->error(600, $username, 'null', $banReason);
                    return;
                }
                $passwordIn=USuperGlobalAccess::getPost('password');
                if(password_verify($passwordIn, $user->getPassword())){
                    $session = USession::getInstance();
                    $session->setSessionElement("id", $result_username_array['id']);
                    $session->setSessionElement("userType", $type);
                    $session->setSessionElement('username', $username);
                    $passwordIn = password_hash($passwordIn, PASSWORD_DEFAULT);
                    $session->setSessionElement('password', $passwordIn);
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
     * Method logout
     * 
     * this method can logout the User, unsetting all the session element and destroing the session. Return the user to the Login Page
     * @return void
     */
    public static function logout()
    {
        $session = USession::getInstance();
        $session->unsetSession();
        $session->destroySession();
        if (ini_get("session.use_cookies")) #return the value of the configuration option
        {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', $params['lifetime'] - 66666642000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        header('Location: /UniRent/User/home');
    }

}