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

    
    public static function accommodationManagement(int $idAccommodation, ?string $modalSuccess=null):void {
        self::checkIfOwner();
        $view = new VOwner();
        $PM = FPersistentManager::getInstance();
        $session=USession::getInstance();
        $accomm = $PM->load('EAccommodation', $idAccommodation);
        if($session->getSessionElement('id') != $accomm->getIdOwner())
        {
            $viewError= new VError();
            $viewError->error(403);
            exit();
        }
        #print_r($accomm);
        $photos_acc=$accomm->getPhoto();
        $photo_acc_64=EPhoto::toBase64($photos_acc);
        $accomm->setPhoto($photo_acc_64);

        $picture=array();
        foreach($accomm->getPhoto() as $p)
        {
            if(!is_null($p)){
                
                $picture[]=$p->getPhoto();
            }
        }

        $owner = $PM->load('EOwner', $accomm->getIdOwner());
        $owner_photo=$owner->getPhoto();
        $ownerStatus = $owner->getStatus();
        if($ownerStatus === TStatusUser::BANNED){
            
            $path = __DIR__ . "/../../Smarty/images/BannedUser.png";
            $owner_photo = new EPhoto(null, file_get_contents($path), 'other', null);
            $owner_photo_64=EPhoto::toBase64(array($owner_photo));
            $owner->setPhoto($owner_photo_64[0]);
        }
        elseif(!is_null($owner_photo))
        {
            $owner_photo_64=EPhoto::toBase64(array($owner_photo));
            $owner->setPhoto($owner_photo_64[0]);
            #print_r($owner);
        }
        
        $reviewsData = CReview::getProfileReviews($accomm->getIdAccommodation(), TType::ACCOMMODATION);
        $num_places=$accomm->getPlaces();
        $tenantsArray= $PM->getTenants('current',$accomm->getIdOwner());
        foreach ($tenantsArray as $idAccommodation => $students) {
            $accommodationTitle = FPersistentManager::getInstance()->getTitleAccommodationById($idAccommodation);
            $tenants=UFormat::getFilterTenantsFormatArray($students, $idAccommodation, $accommodationTitle, 'OwnerManagement')[$idAccommodation]['tenants'];
        }
        $disabled=$accomm->getStatus();
        $deletable=false;
        
        $view->accommodationManagement($accomm, $owner, $reviewsData, $picture, $tenants, $num_places, $disabled, $deletable, $modalSuccess);
    }

    public static function ownerRegistration(){

        $view = new VOwner();
        $PM = FPersistentManager::getInstance();
        $session = USession::getInstance();
        $picture = $session->getSessionElement('picture');
        
        if ($picture['img']===null) {

            $photo = null;

        } else {
            
            $photo = new EPhoto(null, $picture['img'], 'other', null);
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
        $ownerId = $owner->getId();
        if($result){
            $session->setSessionElement('id', $ownerId);
            $session->setSessionElement('phoneNumber', $phone);
            $session->setSessionElement('iban', $iban);
            
            header('Location:/UniRent/Owner/home');

        }else{

            header('Location:/UniRent/User/home/error');
        }
        
    }
    
    /**
     * Method profile
     * This method shows the owner's profile
     * 
     * @param string|null $modalSuccess
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
            setcookie('PHPSESSID','',time()-3600);
            header('Location:/UniRent/User/home');
        }
        else
        {
            header('Location:/UniRent/Owner/profile/error');
        }
    }

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

        $oldPhoto = $session->getSessionElement('photo');
        $base64 = base64_encode($oldPhoto);
        $photoError = "data:" . 'image/jpeg' . ";base64," . $base64;

        if($ownerId===null)
        {
            $viewError= new VError();
            $viewError->error(403);
            exit();
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
                            $passChange = COwner::changePassword($oldPassword, $newPassword, $owner, $photoError);

                            $newPassword = $passChange[0];
                            $error = $passChange[1];
                            
                            $photo = COwner::changePhoto($oldPhoto, $picture, $owner);

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
                                $password = $owner->getPassword();
                                $session->setSessionElement('password',$password);
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

    private static function changePhoto(?string $oldPhoto, ?array $picture, EOwner $oldOwner) : ?EPhoto{

        $PM=FPersistentManager::getInstance();

        if(!is_null($oldPhoto)){
                    
            $photoId=$oldOwner->getPhoto()->getId();

            is_null($picture) ? $photo = new EPhoto($photoId, $oldPhoto, 'other', null)
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

    private static function changePassword($oldPassword, $newPassword, $owner, $photoError):array{

        $session=USession::getInstance();
        $view = new VOwner();
        $error = 0;

        if($newPassword===''){
            //If i don't have any new password, i'll use the old one
            $password=$session->getSessionElement('password');
        } else {
            
            if($oldPassword===$session->getSessionElement('password')){
                if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&()])[A-Za-z\d@$!%*?&()]{8,}$/' , $newPassword)){

                    $error = 1;
                    $password=$session->getSessionElement('password');
                    $view->editProfile($owner, $photoError, false, false, false, false, false, true);

                } else $password=$newPassword;
                
            } else {
                $error = 1;
                $view->editProfile($owner, $photoError, false, false, false, false, true, false);

            }
        }

        return [$password, $error];
    }

    /** */
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

    //Da spostare in CReview
    public static function reviews(?string $modalSuccess=null) :void {
        self::checkIfOwner();
        $view = new VOwner();
        $session=USession::getInstance();
        $id=$session->getSessionElement('id');
        $reviewsData = CReview::getProfileReviews($id, TType::OWNER);
        
        $view->reviews($reviewsData, $modalSuccess);
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