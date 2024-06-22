<?php

namespace Classes\Control;

require __DIR__.'../../../vendor/autoload.php';

use Classes\Utilities\USession;
use Classes\Utilities\USuperGlobalAccess;
use Classes\View\VOwner;
use Classes\Foundation\FPersistentManager;
use Classes\Entity\EOwner;
use Classes\Entity\EPhoto;
class COwner 
{
    public static function home()
    {
        $view = new VOwner();
        $view->home();
    }

    public static function ownerRegistration()
    {
        $view = new VOwner();
        $PM = FPersistentManager::getInstance();
        $session = USession::getInstance();
        if(USession::getSessionElement('picture')==null)
        {
            $photo = null;
        }
        else
        {
            $photo = new EPhoto(null, unserialize($session::getSessionElement('picture')),'owner',null,null );
        }
        $owner = new EOwner(null,
                            $session->getSessionElement('username'),
                            $session->getSessionElement('password'),
                            $session->getSessionElement('name'),
                            $session->getSessionElement('surname'),
                            $photo,
                            $session->getSessionElement('email'),
                            USuperGlobalAccess::getPost('phoneNumber'),
                            USuperGlobalAccess::getPost('iban'));
        $PM->store($owner);
        $session->setSessionElement('phoneNumber', USuperGlobalAccess::getPost('phoneNumber'));
        $session->setSessionElement('iban', USuperGlobalAccess::getPost('iban'));
        header('Location:/UniRent/Owner/home');
    }
                            
    /*private static $instance=null;

    private function __construct()
    {}
    public static function getInstance() {
        if(is_null(self::$instance))
        {
            self::$instance = new COwner();
        }
        return self::$instance;
    }
    public static function registration():void {
        
    }
    private static function ValidateMail(string $mail):bool {
        $validator = new EmailValidator();
        return $validator->isValid($mail, new RFCValidation());
    }
    private static function ValidateIban(string $iban):bool {
        return verify_iban($iban);
    }*/
}