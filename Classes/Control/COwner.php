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
        if($session->getSessionElement('picture')===null){

            $photo = null;

        }else{

            $photo = null;
            //$photo = new EPhoto(null, unserialize($session::getSessionElement('picture')),'owner',null,null );
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
}