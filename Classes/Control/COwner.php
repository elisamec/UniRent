<?php

namespace Classes\Control;

require __DIR__.'../../../vendor/autoload.php';

use Classes\Utilities\USession;
use Classes\Utilities\USuperGlobalAccess;
use Classes\View\VOwner;
use Classes\Foundation\FPersistentManager;
use Classes\Entity\EOwner;
use Classes\Entity\EPhoto;
use Classes\Foundation\FOwner;
use Classes\Foundation\FReview;
use Classes\Tools\TType;

class COwner 
{
    public static function home()
    {
        $view = new VOwner();
        $view->home();
        print USession::getSessionElement('username');
        print USession::getSessionElement('password');
    }

    public static function ownerRegistration()
    {
        $view = new VOwner();
        $PM = FPersistentManager::getInstance();
        $session = USession::getInstance();
        if ($session->getSessionElement('picture')===null) {

            $photo = null;

        }else {
            
            $photo = null;
        } 

        $phone = USuperGlobalAccess::getPost('phoneNumber');
        $iban = USuperGlobalAccess::getPost('iban');

        $owner = new EOwner(null,
                            $session->getSessionElement('username'),
                            $session->getSessionElement('password'),
                            $session->getSessionElement('name'),
                            $session->getSessionElement('surname'),
                            $photo,
                            $session->getSessionElement('email'),
                            $phone,
                            $iban);
        $PM->store($owner);
        $session->setSessionElement('phoneNumber', USuperGlobalAccess::getPost('phoneNumber'));
        $session->setSessionElement('iban', USuperGlobalAccess::getPost('iban'));
        header('Location:/UniRent/Owner/home');
    }
    
    /**
     * Method profile
     * This method call a view to show the owner profile from db
     * @return void
     */
    public static function profile()
    {
        $view = new VOwner();
        $session=USession::getInstance();
        $user = $session->getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        $owner=$PM->getOwnerByUsername($user);
        if(is_null($owner))
        {
            print '<b>500 : SERVER ERROR </b>';
        }
        else
        {
            $view->profile($owner);
        }
    }
    public static function editProfile()
    {
        $view = new VOwner();
        $session=USession::getInstance();
        $user = $session->getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        $owner=$PM->getOwnerByUsername($user);
        if(is_null($owner))
        {
            print '<b>500 : SERVER ERROR </b>';
        }
        else
        {
            $view->editProfile($owner);
        }
    }
    public static function contact()
    {
        $view = new VOwner();
        $view->contact();
    }
    public static function about()
    {
        $view = new VOwner();
        $view->about();
    }
     public static function reviews() {
        $view = new VOwner();
        $reviews = FReview::getInstance()->loadByRecipient(1, TType::OWNER);
        $reviewsData = [];
        
        foreach ($reviews as $review) {
            $profilePic = FOwner::getInstance()->load($review->getIdAuthor())->getPhoto();
            if ($profilePic === null) {
                $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
            }
            $reviewsData[] = [
                'title' => $review->getTitle(),
                'username' => FOwner::getInstance()->load($review->getIdAuthor())->getUsername(),
                'stars' => $review->getValutation(),
                'content' => $review->getDescription(),
                'userPicture' => $profilePic,
            ];
        }
        $view->reviews($reviewsData);
    }
}