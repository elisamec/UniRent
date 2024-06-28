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
    }

    public static function ownerRegistration(){

        $view = new VOwner();
        $PM = FPersistentManager::getInstance();
        $session = USession::getInstance();

        if ($session->getSessionElement('picture')===null) {

            $photo = null;

        } else {
            
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
        $result=$PM->store($owner);
        if($result){

            $session->setSessionElement('phoneNumber', $phone);
            $session->setSessionElement('iban', $iban);
            
            header('Location:/UniRent/Owner/home');

        }else{

            print 'Spiacenti non sei stato registrato';
        }
        
    }
    
    /**
     * Method profile
     * Thisrn void
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
    
    /**
     * Method deleteProfile
     *
     * this method deletes the owner profile
     * @return void
     */
    public static function deleteProfile()
    {
        $PM=FPersistentManager::getInstance();
        $user=USession::getInstance()::getSessionElement('username');
        $result=$PM->deleteOwner($user);
        if($result)
        {
            $session=USession::getInstance();
            $session::unsetSession();
            $session::destroySession();
            setcookie('PHPSESSID','',time()-3600);
            header('Location:/UniRent/User/home');
        }
        else
        {
            print 'Spiacenti non puoi andartene ;)';
        }
    }

    public static function modifyOwnerProfile()
    {
        $PM=FPersistentManager::getInstance();
        print 'Qui arriva';
        $name=USuperGlobalAccess::getPost('name');
        $surname=USuperGlobalAccess::getPost('surname');
        $newemail=USuperGlobalAccess::getPost('email');
        $newUsername=USuperGlobalAccess::getPost('username');
        $newPassword=USuperGlobalAccess::getPost('password');
        $newPhoneNumber=USuperGlobalAccess::getPost('phoneNumber');
        $newIBAN=USuperGlobalAccess::getPost('iban');
        #print $name.' '.$surname.' '.$newemail.' '.$newUsername.' '.$newPassword.' '.$newPhoneNumber.' '.$newIBAN;
        $ownerId=$PM->getOwnerIdByUsername(USession::getInstance()::getSessionElement('username'));
        $owner=$PM->load("EOwner", $ownerId);
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