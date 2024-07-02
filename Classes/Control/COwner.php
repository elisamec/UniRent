<?php

namespace Classes\Control;

require __DIR__.'../../../vendor/autoload.php';

use Classes\Entity\EAccommodation;
use Classes\Utilities\USession;
use Classes\Utilities\USuperGlobalAccess;
use Classes\View\VOwner;
use Classes\Foundation\FPersistentManager;
use Classes\Entity\EOwner;
use Classes\Entity\EPhoto;
use Classes\Foundation\FOwner;
use Classes\Foundation\FReview;
use Classes\Tools\TType;
use CommerceGuys\Addressing\Address;

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
        $picture = $session->getSessionElement('picture');
        
        if ($picture['img']===null) {

            $photo = null;

        } else {
            
            $photo = new EPhoto(null, $picture['img'], 'other', null, null);
        }

        $phone = EOwner::formatPhoneNumber(USuperGlobalAccess::getPost('phoneNumber'));
        $iban = USuperGlobalAccess::getPost('iban');
        $verifyIBAN=$PM->verifyIBAN($iban);   #da true se l'iban è presente
        $verifyPhoneNumber=$PM->verifyPhoneNumber($phone); #da true se il numero di telefono è presente
        #print 'phone prima:'.$phone;
        #print '  phone dopo:'.EOwner::formatPhoneNumber($phone);
        
        if ($verifyIBAN && $verifyPhoneNumber) {    # c'è già un utente con lo stesso iban e lo stesso numero di telefono
            $view->registrationError(true, true, "", "");
        } elseif ($verifyIBAN) {  # se solo l'iban è già presente
            $view->registrationError(false, true, $phone, "");
        } elseif ($verifyPhoneNumber) {  # se solo il numero di telefono è già presente
            $view->registrationError(true, false, "", $iban);
        }
        // in alternativa tutto è valido quindi creo il nuovo owner per fare store
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
     * This method shows the owner's profile
     * 
     * @return void
     */
    public static function profile(): void {
        $view = new VOwner();
        $session=USession::getInstance();
        $user = $session->getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        $owner=$PM->getOwnerByUsername($user);

        if(is_null($owner)){

            print '<b>500 : SERVER ERROR </b>';
        } else {

            $ph = $owner->getPhoto();
            
            if(!is_null($ph)) {

                $ph=$ph->getPhoto();
                $base64 = base64_encode($ph);
                $ph = "data:" . 'image/jpeg' . ";base64," . $base64;
            }

            $view->profile($owner, $ph);
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
        $name=USuperGlobalAccess::getPost('name');
        $surname=USuperGlobalAccess::getPost('surname');
        $newemail=USuperGlobalAccess::getPost('email');
        $newUsername=USuperGlobalAccess::getPost('username');
        $newPassword=USuperGlobalAccess::getPost('password');
        $newPhoneNumber=EOwner::formatPhoneNumber(USuperGlobalAccess::getPost('phoneNumber'));
        $newIBAN=USuperGlobalAccess::getPost('iban');
        #print $name.' '.$surname.' '.$newemail.' '.$newUsername.' '.$newPassword.' '.$newPhoneNumber.' '.$newIBAN;
        $ownerId=$PM->getOwnerIdByUsername(USession::getInstance()::getSessionElement('username'));
        if($ownerId===null)
        {
            print 'Spiacenti non sei un owner';
        }
        else
        {
            $owner=$PM->load("EOwner", $ownerId);    
            
            if(($newemail===$owner->getMail())||($PM->verifyUserEmail($newemail)===false))
            {
                if(($newUsername===$owner->getUsername())||($PM->verifyUserUsername($newUsername)===false))
                {
                    if(($newPhoneNumber==$owner->getPhoneNumber())||($PM->verifyPhoneNumber($newPhoneNumber)===false))
                    {
                        if(($newIBAN===$owner->getIban())||($PM->verifyIBAN($newIBAN)===false))
                        {
                            $owner->setName($name);
                            $owner->setSurname($surname);
                            $owner->setMail($newemail);
                            $owner->setUsername($newUsername);
                            $owner->setPassword($newPassword);
                            $owner->setPhoneNumber($newPhoneNumber);
                            $owner->setIban($newIBAN);
                            $result=$PM::update($owner);
                            if($result)
                            {
                                $session=USession::getInstance();
                                $session::setSessionElement('username', $newUsername);
                                $session::setSessionElement('password',$newPassword);
                                header("Location:/UniRent/Owner/profile");
                            }
                            else
                            {
                                print '<b>500 : SERVER ERROR!</b>';
                            }
                        }
                        else
                        {
                            print 'IBAN già in uso';
                            #header('Location:/UniRent/Owner/profile');
                        }
                    }
                    else
                    {
                        print 'Numero di telefono già in uso';
                        #header('Location:/UniRent/Owner/profile');
                    }
                }
                else
                {
                    print 'Username già in uso';
                    #header('Location:/UniRent/Owner/profile');
                }
            }
            else
            {
                print 'Email già in uso';
                #header('Location:/UniRent/Owner/profile');
            }
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
/*
    public static function changePicture()
    {
        
    }*/

    public static function addAccommodation()
    {
        $view=new VOwner();
        $view->addAccommodation();
    }

    public static function addAccommodationOperations()
    {
        $title=USuperGlobalAccess::getPost('title');
        $price=USuperGlobalAccess::getPost('price');
        $deposit=USuperGlobalAccess::getPost('deposit');
        $startDate=USuperGlobalAccess::getPost('startDate');
        $month=USuperGlobalAccess::getPost('endDate');
        $address=USuperGlobalAccess::getPost('address');
        $city=USuperGlobalAccess::getPost('city');
        $postalCode=USuperGlobalAccess::getPost('postalCode');
        $description=USuperGlobalAccess::getPost('description');
        $notes=USuperGlobalAccess::getPost('comment');
        #print $title.' '.$price.' '.' '.$deposit.' '.$startDate.' '.$month.' '.$address.' '.$city.' '.$postalCode.' '.$description.' '.$notes;
        $PM=FPersistentManager::getInstance();
        $addressObj= new Address('IT', '',$city,'',$postalCode);
        

        //Da fare....
    }

}