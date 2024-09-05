<?php

namespace Classes\Control;

require __DIR__.'../../../vendor/autoload.php';

use Classes\Utilities\USession;
use Classes\Utilities\USuperGlobalAccess;
use Classes\View\VOwner;
use Classes\Foundation\FPersistentManager;
use Classes\Entity\EOwner;
use Classes\Entity\EPhoto;
use Classes\Tools\TStatusUser;
use Classes\Tools\TType;
use Classes\View\VError;
use Classes\Utilities\UFormat;

/**
 * This class is responsible for managing owners.
 * 
 * @package Classes\Control
 */
class COwner 
{
    /**
     * Method home
     * Show the owner's home page
     * 
     * @param string|null $modalSuccess
     * @return void
     */
    public static function home(?string $modalSuccess=null) :void
    {
        self::checkIfOwner();
        $view = new VOwner();
        $PM=FPersistentManager::getInstance();
        $ownerId=USession::getInstance()->getSessionElement('id');
        $accommodationEntities=$PM->loadAccommodationsByOwner($ownerId);
        
        $accommodationsActive=array();
        $accommodationsInactive=array();

        foreach($accommodationEntities as $accom) {
            if(count($accom->getPhoto())==0)
                {
                    $photo=null;
                }
                else
                {
                   $base64 = base64_encode((($accom->getPhoto())[0])->getPhoto());
                   $photo = "data:" . 'image/jpeg' . ";base64," . $base64;
                }

                $acc = [
                    'id' => $accom->getIdAccommodation(),
                    'photo' => $photo,
                    'title' => $accom->getTitle(),
                    'address' => $accom->getAddress()->getAddressLine1() . ", " . $accom->getAddress()->getLocality(),
                    'price' => $accom->getPrice(),
                ];

                if ($accom->getStatus() == true) {
                    $accommodationsActive[] = $acc;
                } else {
                    $accommodationsInactive[] = $acc;
                }
            }
            
        $view->home($accommodationsActive, $accommodationsInactive, $modalSuccess);
    }

    
    /**
     * Handles the management of an accommodation.
     *
     * @param int $idAccommodation The ID of the accommodation.
     * @param string|null $modalSuccess (optional) The success message to display in a modal.
     * @return void
     */
    public static function profile(?string $modalSuccess=null): void {
        self::checkIfOwner();
        $view = new VOwner();
        $session=USession::getInstance();
        $PM=FPersistentManager::getInstance();
        $id = $session->getSessionElement('id');
        $owner=$PM->load("EOwner", $id);
        $ph = null;

        if(is_null($owner)){
            $session->setSessionElement('photo', $ph);
            $viewError= new VError();
            $viewError->error(500);
            exit();
        } else {

            $ph = $owner->getPhoto();
            
            if(!is_null($ph)) {

                $ph=$ph->getPhoto();

                $session->setSessionElement('photo', $ph);

                $base64 = base64_encode($ph);
                $ph = "data:" . 'image/jpeg' . ";base64," . $base64;
            }
            
            $view->profile($owner, $ph, $modalSuccess);
        }
    }

    /**
     * Method editProfile
     * This method shows the owner's profile edit page
     * 
     * @param string|null $modalSuccess
     * @return void
     */
    public static function editProfile() :void
    {
        self::checkIfOwner();
        $view = new VOwner();
        $session=USession::getInstance();
        $session->getSessionElement('username');
        
        $PM=FPersistentManager::getInstance();
        $id = $session->getSessionElement('id');
        $owner=$PM->load("EOwner", $id);
        if(is_null($owner)){

            $viewError= new VError();
            $viewError->error(500);
            exit();
        } else {

            $photo = USession::getInstance()->getSessionElement('photo');

            $base64 = base64_encode($photo);
            $photo = "data:" . 'image/jpeg' . ";base64," . $base64;
            
            $view->editProfile($owner, $photo, false, false, false, false, false, false);

        }
    }
    
    /**
     * Method deleteProfile
     *
     * this method deletes the owner profile
     * @return void
     */
    public static function deleteProfile() :void
    {
        self::checkIfOwner();
        $PM=FPersistentManager::getInstance();
        $user=USession::getInstance()->getSessionElement('username');
        $result=$PM->deleteOwner($user);
        if($result)
        {
            $session=USession::getInstance();
            $session->unsetSession();
            $session->destroySession();
            setcookie('PHPSESSID','',time()-2592000);
            header('Location:/UniRent/User/home');
        }
        else
        {
            header('Location:/UniRent/Owner/profile/error');
        }
    }

    /**
     * Method modifyOwnerProfile
     * This method modifies the owner's profile
     * 
     * @return void
     */
    public static function modifyOwnerProfile() :void
    {
        self::checkIfOwner();

        $view = new VOwner();
        $session=USession::getInstance();
        $error = 0;
        $PM=FPersistentManager::getInstance();
        

        //Reed the data from the form
        $name=USuperGlobalAccess::getPost('name');
        $surname=USuperGlobalAccess::getPost('surname');
        $picture = USuperGlobalAccess::getPhoto('img');
        $newemail=USuperGlobalAccess::getPost('email');
        $newUsername=USuperGlobalAccess::getPost('username');
        $newPassword=USuperGlobalAccess::getPost('password');
        $newPhoneNumber=EOwner::formatPhoneNumber(USuperGlobalAccess::getPost('phoneNumber'));
        $newIBAN=USuperGlobalAccess::getPost('iban');
        $oldUsername=$session->getSessionElement('username');

        $ownerId=$session->getSessionElement('id');
        $oldPassword=USuperGlobalAccess::getPost('oldPassword');

        

        if($ownerId===null)
        {
            $viewError= new VError();
            $viewError->error(403);
            exit();
        }
        else
        {
            $owner=$PM->load("EOwner", $ownerId);   
            
            $oldPhoto = $owner->getPhoto();

            if(!is_null($oldPhoto)){
                $photoError = $oldPhoto->getPhoto();
                $photoError = "data:" . 'image/jpeg' . ";base64," . base64_encode($photoError);
            } else $photoError = null;
            
            if(($newemail===$owner->getMail())||($PM->verifyUserEmail($newemail)===false))
            {
                if(($newUsername===$owner->getUsername())||($PM->verifyUserUsername($newUsername)===false))
                {
                    if(($newPhoneNumber==$owner->getPhoneNumber())||($PM->verifyPhoneNumber($newPhoneNumber)===false))
                    {
                        if(($newIBAN===$owner->getIban())||($PM->verifyIBAN($newIBAN)===false))
                        {   
                            $passChange = COwner::changePassword($oldPassword, $newPassword, $owner, $photoError);
                            $newPassword = $passChange[0];
                            $error = $passChange[1];
                            
                            $photo = COwner::changePhoto($oldPhoto, $picture);

                            $owner->setName($name);
                            $owner->setSurname($surname);
                            $owner->setPhoto($photo);
                            $owner->setMail($newemail);
                            $owner->setUsername($newUsername);
                            $owner->setPassword($newPassword);
                            $owner->setPhoneNumber($newPhoneNumber);
                            $owner->setIban($newIBAN);
                            #$owner = new EOwner($ownerId, $newUsername, $newPassword, $name, $surname, $photo, $newemail, $newPhoneNumber, $newIBAN);
                            $result=$PM->update($owner);
                            if($result  && !$error)
                            {   
                                $ph = $photo->getPhoto();
                                if (is_null($ph)) $ph = null;
                                $session->setSessionElement('username', $newUsername);
                                $session->setSessionElement('photo',$ph);
                                header("Location:/UniRent/Owner/profile");
                            }
                            elseif (!$result) {
                    
                                header('Location:/UniRent/Owner/profile/error');
                                
                            }
                        }
                        else
                        {
                            $view->editProfile($owner, $picture, false, false, false, true, false, false); //Iban already in use
                            #header('Location:/UniRent/Owner/profile');
                        }
                    }
                    else
                    {
                        $view->editProfile($owner, $picture, false, false, true, false, false, false); //phone already in use
                        #header('Location:/UniRent/Owner/profile');
                    }
                }
                else
                {   
                    $view->editProfile($owner, $picture, true, false, false, false, false, false); //Username already in use
                    #header('Location:/UniRent/Owner/profile');
                }
            }
            else
            {   
                $view->editProfile($owner, $picture, false, true, false, false, false, false); //Email already in use
                #header('Location:/UniRent/Owner/profile');
            }
        }
    }


    /**
     * Change the photo of the owner.
     *
     * @param EPhoto|null $oldPhoto The old photo of the owner.
     * @param array|null $picture The new photo to be set.
     * @return EPhoto|null The updated photo of the owner.
     */
    private static function changePhoto(?EPhoto $oldPhoto, ?array $picture) : ?EPhoto{

        $PM=FPersistentManager::getInstance();

        if(!is_null($oldPhoto)){
                    
            $photoId=$oldPhoto->getId();

            is_null($picture) ? $photo = $oldPhoto
                              : $photo = new EPhoto($photoId, $picture['img'], 'other', null);

        } else {

            if(is_null($picture)) {
                $photo = null;

            } else {

                $photo = new EPhoto(null, $picture['img'], 'other', null);
                $risultato = $PM->storeAvatar($photo);

                if(!$risultato){
                    header('Location:/UniRent/Owner/profile/error');
                }
            }
        }

        return $photo;
    }

    /**
     * Change the password for the owner.
     *
     * @param string $formOldPassword The old password entered in the form.
     * @param string $newPassword The new password to be set.
     * @param object $owner The owner object.
     * @param string $photoError The error message related to the photo upload.
     * @return array The updated owner details.
     */
    private static function changePassword($formOldPassword, $newPassword, $owner, $photoError):array{

        $view = new VOwner();
        $error = 0;

        $oldPassword = $owner->getPassword();

        if($newPassword === ''){
            //If i don't have any new password, i'll use the old one
            $password = $oldPassword;
        } else {
            
            if(password_verify($formOldPassword, $oldPassword)){
                if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&()])[A-Za-z\d@$!%*?&()]{8,}$/' , $newPassword)){

                    $error = 1;
                    $password = $oldPassword;
                    $view->editProfile($owner, $photoError, false, false, false, false, false, true);

                } else $password=$newPassword;
                
            } else {
                $error = 1;
                $view->editProfile($owner, $photoError, false, false, false, false, true, false);
                $password=$oldPassword;
            }
        }

        return [$password, $error];
    }

    /**
     * Method deletePhoto
     * This method deletes owner's photo
     * 
     * @return void
     */
    public static function deletePhoto() :void {
        self::checkIfOwner();
        $PM=FPersistentManager::getInstance();
        $session=USession::getInstance();
        $ownerId=$session->getSessionElement('id');
        $photo = $PM -> load('EOwner', $ownerId) -> getPhoto();

        if(is_null($photo)){

            $result = 0;
            $viewError= new VError();
            $viewError->error(500);
            exit();

        } else {
            $photoID = $photo->getId();
            $result = $PM->delete('EPhoto', $photoID);
        }

        if ($result > 0) {
            $photo = null;
            $session->setSessionElement('photo',$photo);
            header('Location:/UniRent/Owner/profile/success');
        }  else header('Location:/UniRent/Owner/profile/error');

    }

    /**
     * Method contact
     * This method shows the contact page
     * 
     * @param string|null $modalSuccess
     * @return void
     */
    public static function contact(?string $modalSuccess=null) :void
    {   
        $session = USession::getInstance();
        $type = $session->getSessionElement('userType');
        if ($type === null) {
            header('Location:/UniRent/User/contact');
        } else if ($type ==='Student') {
            header('Location:/UniRent/Student/contact');
        }
        $view = new VOwner();
        
        $view->contact($modalSuccess);
    }

    /**
     * Method about
     * This method shows the about page
     * 
     * @return void
     */
    public static function about() :void
    {   
        $session = USession::getInstance();
        $type = $session->getSessionElement('userType');
        if ($type === null) {
            header('Location:/UniRent/User/about');
        } else if ($type ==='Student') {
            header('Location:/UniRent/Student/about');
        }
        $view = new VOwner();
        
        $view->about();
    }

    /**
     * Method guidelines
     * This method shows the guidelines page
     * 
     * @return void
     */
    public static function guidelines() :void
    {   $session = USession::getInstance();
        $type = $session->getSessionElement('userType');
        if ($type === null) {
            header('Location:/UniRent/User/guidelines');
        } else if ($type ==='Student') {
            header('Location:/UniRent/Student/guidelines');
        }
        $view = new VOwner();
        
        $view->guidelines();
    }

   

    /**
     * Method publicProfileFromOwner
     * This method shows the public profile of an owner
     * 
     * @param string $username
     * @param string|null $modalSuccess
     * @return void
     */
    public static function publicProfileFromOwner(string $username, ?string $modalSuccess=null) :void
    {   self::checkIfOwner();
        $session=USession::getInstance();
        if ($session->getSessionElement('username') === $username) {
            $self = true;
        } else {
            $self = false;
        }
        $view = new VOwner();
        $PM=FPersistentManager::getInstance();
        $owner=$PM->getOwnerByUsername($username);
        if ($owner->getStatus() === TStatusUser::BANNED) {
            $viewError=new VError();
            $viewError->error(403);
            exit();
        }
        $owner_photo=$owner->getPhoto();
        if(is_null($owner_photo)){}
        else
        {
            $owner_photo_64=EPhoto::toBase64(array($owner_photo));
            $owner->setPhoto($owner_photo_64[0]);
        }
        $reviewsData = CReview::getProfileReviews($owner->getId(), TType::OWNER);
        
        $view->publicProfileFromOwner($owner, $reviewsData, $self, $modalSuccess);
    }

    /**
     * Method publicProfileFromStudent
     * This method shows the public profile of an owner from a student
     * 
     * @param string $username
     * @param string|null $modalSuccess
     * @return void
     */
    public static function publicProfileFromStudent(string $username, ?string $modalSuccess=null) :void
    {   CStudent::checkIfStudent();
        $view = new VOwner();
        $PM=FPersistentManager::getInstance();
        $owner=$PM->getOwnerByUsername($username);
        if ($owner->getStatus() === TStatusUser::BANNED) {
            $viewError=new VError();
            $viewError->error(403);
            exit();
        }
        $owner_photo=$owner->getPhoto();
        if(is_null($owner_photo)){}
        else
        {
            $owner_photo_64=EPhoto::toBase64(array($owner_photo));
            $owner->setPhoto($owner_photo_64[0]);
            #print_r($owner);
        }

        $reviewsData = CReview::getProfileReviews($owner->getId(), TType::OWNER);
        $session=USession::getInstance();
        $leavebleReviews=$PM->remainingReviewStudentToOwner($session->getSessionElement('id'), $owner->getId());
        $view->publicProfileFromStudent($owner, $reviewsData, $modalSuccess, $leavebleReviews);
    }

    /**
     * Method publicProfile
     * This method shows the public profile of an owner
     * 
     * @param string $username
     * @return void
     */
    public static function publicProfile(string $username) :void{
        self::checkIfOwner();
        $PM=FPersistentManager::getInstance();
        $user=$PM->verifyUserUsername($username);
        if ($user['type'] === 'Student') {
            CStudent::publicProfileFromOwner($username);
        } else {
            self::publicProfileFromOwner($username);
        }
    }

    //Da spostare in review e accorciare
    public static function postedReview(?string $modalSuccess=null) {
        self::checkIfOwner();
        $view = new VOwner();
        $session=USession::getInstance();
        $ownerId=$session->getSessionElement('id');
        $PM=FPersistentManager::getInstance();
        $reviews = $PM->loadReviewsByAuthor($ownerId, TType::OWNER);
        $reviewsData = [];

        foreach ($reviews as $review) {
            $recipient = $PM->load( 'E' . $review->getRecipientType()->value, $review->getIdRecipient());
            $profilePic = $recipient->getPhoto();
            $profilePic = UFormat::photoFormatReview($profilePic, $recipient->getStatus());
            if ($review->getDescription()===null) {
                $content='No additional details were provided by the author.';
            }
            else
            {
                $content=$review->getDescription();
            }
            $reviewsData[] = [
                'title' => $review->getTitle(),
                'username' => $recipient->getUsername(),
                'userStatus' => $recipient->getStatus()->value,
                'stars' => $review->getValutation(),
                'content' => $content,
                'userPicture' => $profilePic,
                'id'=> $review->getId(),
                'type' => ucfirst($review->getRecipientType()->value),
                'reported' => $review->isReported()
            ];
        }
        
        $view->postedReview($reviewsData, $modalSuccess);
    }

    //Da spostare in CAccommodation
    public static function viewOwnerAds(int $id) {
        CStudent::checkIfStudent();
        $view = new VOwner();
        $PM=FPersistentManager::getInstance();
        $accommodationEntities=$PM->loadAccommodationsByOwner($id);
        $username=$PM->getUsernameByOwnerId($id);
        $accommodations=[];
        foreach($accommodationEntities as $accom) {
            if(count($accom->getPhoto())==0)
                {
                    $photo=null;
                }
                else
                {
                   $base64 = base64_encode((($accom->getPhoto())[0])->getPhoto());
                   $photo = "data:" . 'image/jpeg' . ";base64," . $base64;
                }
            if ($accom->getStatus() == true) {
                
            $accommodations[]=[
                'id'=>$accom->getIdAccommodation(),
                'photo'=>$photo,
                'title'=>$accom->getTitle(),
                'address'=>$accom->getAddress()->getAddressLine1() .", ". $accom->getAddress()->getLocality(),
                'price'=>$accom->getPrice()
            ];
        }
        }
        
        $view->viewOwnerAds($accommodations, $username);
    }

    /**
     * Method tenants
     * This method shows the tenants of an owner
     * 
     * @param string $kind
     * @return void
     */
    public static function tenants(string $kind):void {
        self::checkIfOwner();
        $session=USession::getInstance();
        $ownerId=$session->getSessionElement('id');
        $PM=FPersistentManager::getInstance();
        $view = new VOwner();
        $tenantsArray= $PM->getTenants($kind, $ownerId);
        $tenants=[];
        foreach ($tenantsArray as $idAccommodation => $students) {
            $accommodationTitle = FPersistentManager::getInstance()->getTitleAccommodationById($idAccommodation);
            $tenants=UFormat::getFilterTenantsFormatArray($students, $idAccommodation, $accommodationTitle, 'Owner');
        }
        $accommodations=$PM->loadAccommodationsByOwner($ownerId);
        foreach ($accommodations as $accom) {
            $accommodationTitles[$accom->getIdAccommodation()]=$accom->getTitle();
        }
        $view->tenants($tenants, $kind, $accommodationTitles,0);
    }

    /**
     * Method filterTenants
     * This method filters the tenants of an owner
     * 
     * @param string $type
     * @return void
     */
    public static function filterTenants(string $type)
    {   self::checkIfOwner();
        $session=USession::getInstance();
        $ownerId=$session->getSessionElement('id');
        $PM=FPersistentManager::getInstance();
        $view = new VOwner();
        $afs=USuperGlobalAccess::getAllPost(['accommodation','username','rateT','date','age','men','women','year']);
        $men=USuperGlobalAccess::getPost('men');
        $women=USuperGlobalAccess::getPost('women');
        $men==='false'? $men=false : $men=true;
        $women==='false'? $women=false : $women=true;
        $tenantsArray=$PM->getFilterTenants($type,$afs['accommodation'],$afs['username'],(int)$afs['age'],(int)$afs['rateT'],$afs['date'],$men,$women,$ownerId,$afs['year']);
        $tenants=[];
        foreach ($tenantsArray as $idAccommodation => $students) {
            $accommodationTitle = FPersistentManager::getInstance()->getTitleAccommodationById($idAccommodation);
            $tenants=UFormat::getFilterTenantsFormatArray($students, $idAccommodation, $accommodationTitle, 'Owner');
        }
        $accommodations=$PM->loadAccommodationsByOwner($ownerId);
        foreach ($accommodations as $accom) 
        {
            $accommodationTitles[$accom->getIdAccommodation()]=$accom->getTitle();
        }
        $view->tenants($tenants, $type, $accommodationTitles,(int)$afs['rateT'], $afs['accommodation'], $afs['username'], $afs['date'], $afs['age'], $men, $women, $afs['year']);
    }

    /**
     * Method checkIfOwner
     * This method checks if the user is an owner
     * 
     * @return void
     */
    public static function checkIfOwner() :void {
        $session = USession::getInstance();
        if ($session->getSessionElement('userType') !== 'Owner') {
            $view= new VError();
            $view->error(403);
            exit();
        }
    }

}