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
use Classes\Tools\TStatusUser;
use Classes\Tools\TType;
use CommerceGuys\Addressing\Address;
use DateTime;
use Classes\View\VError;
use Classes\Tools\TRequestType;

class COwner 
{
    public static function home(?string $modalSuccess=null)
    {
        self::checkIfOwner();
        $view = new VOwner();
        $PM=FPersistentManager::getInstance();
        $ownerId=USession::getInstance()->getSessionElement('id');
        $accommodationEntities=$PM->loadAccommodationsByOwner($ownerId);
        $accommodationsActive=[];
        $accommodationsInactive=[];
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
                    $accommodationsActive[] = [
                        'id' => $accom->getIdAccommodation(),
                        'photo' => $photo,
                        'title' => $accom->getTitle(),
                        'address' => $accom->getAddress()->getAddressLine1() . ", " . $accom->getAddress()->getLocality(),
                        'price' => $accom->getPrice(),
                    ];
                } else {
                    $accommodationsInactive[] = [
                        'id' => $accom->getIdAccommodation(),
                        'photo' => $photo,
                        'title' => $accom->getTitle(),
                        'address' => $accom->getAddress()->getAddressLine1() . ", " . $accom->getAddress()->getLocality(),
                        'price' => $accom->getPrice(),
                    ];
                }
            }
                
        #print_r($accommodations);
        
        $view->home($accommodationsActive, $accommodationsInactive, $modalSuccess);
    }


    public static function accommodationManagement(int $idAccommodation, ?string $modalSuccess=null) {
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
            if(is_null($p)){}
            else
            {
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
        
        $reviewsData = self::reviewsDataByrecipient($accomm->getIdAccommodation(), TType::ACCOMMODATION);
        $num_places=$accomm->getPlaces();
        $tenantOwner= $PM->getTenants('current',$accomm->getIdOwner());
        if (!array_key_exists($idAccommodation, $tenantOwner)) {
            $tenants=[];
        }
        else
        {
            $tenants=[];
            foreach ($tenantOwner[$idAccommodation] as $i) {
                $profilePic = $i[0]->getPhoto();
                if ($i[0]->getStatus() === TStatusUser::BANNED) {
                    $profilePic = "/UniRent/Smarty/images/BannedUser.png";
                } else if ($profilePic === null) {
                    $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
                }
                else
                {
                    $profilePic=$profilePic->getPhoto();
                }
                $tenants[]= [
                    'username' => $i[0]->getUsername(),
                    'expiryDate' => $i[1],
                    'profilePic' => $profilePic,
                    'status' => $i[0]->getStatus()->value,
                ];
            }
        }
        $disabled=$accomm->getStatus();
        $deletable=false;
        
        $view->accommodationManagement($accomm, $owner, $reviewsData, $picture, $tenants, $num_places, $disabled, $deletable, $modalSuccess);
    }
    private static function reviewsDataByrecipient(int $idRecipient, TType $typeRecipient): array {
        $reviewsData = [];
        $PM = FPersistentManager::getInstance();
        $reviews = $PM->loadByRecipient($idRecipient, $typeRecipient);
        
        foreach ($reviews as $review) {
            $author = $PM->load('EStudent', $review->getIdAuthor());
            if ($review->isBanned()) {
                continue;
            }
            $profilePic = $author->getPhoto();
            if ($author->getStatus() === TStatusUser::BANNED) {
                $profilePic = "/UniRent/Smarty/images/BannedUser.png";
            } else if ($profilePic === null) {
                $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
            }
            else
            {
                $profilePic=(EPhoto::toBase64(array($profilePic)))[0];
            }
            if ($review->getDescription()===null) {
                $content='No additional details were provided by the author.';
            }
            else
            {
                $content=$review->getDescription();
            }
            $reviewsData[] = [
                'id' => $review->getId(),
                'title' => $review->getTitle(),
                'username' => $author->getUsername(),
                'userStatus' => $author->getStatus()->value,
                'stars' => $review->getValutation(),
                'content' => $content,
                'userPicture' => $profilePic,
            ];
        }
        return $reviewsData;
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
     * This method shows the owner's profile
     * 
     * @return void
     */
    public static function profile(?string $modalSuccess=null): void {
        self::checkIfOwner();
        $view = new VOwner();
        $session=USession::getInstance();
        //$user = $session->getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        //$owner=$PM->getOwnerByUsername($user);
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

    public static function editProfile(?string $modalSuccess=null)
    {
        self::checkIfOwner();
        $view = new VOwner();
        $session=USession::getInstance();
        $user = $session->getSessionElement('username');
        
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
            
            $view->editProfile($owner, $photo, false, false, false, false, false, false, $modalSuccess);

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

    public static function modifyOwnerProfile()
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
                                $session->setSessionElement('password',$newPassword);
                                $session->setSessionElement('photo',$ph);
                                header("Location:/UniRent/Owner/profile");
                            }
                            elseif (!$result) {
                    
                                header('Location:/UniRent/Owner/profile/error');
                                
                            }
                        }
                        else
                        {
                            $view->editProfile($owner, $picture, false, false, false, true, false, false, null); //Iban already in use
                            #header('Location:/UniRent/Owner/profile');
                        }
                    }
                    else
                    {
                        $view->editProfile($owner, $picture, false, false, true, false, false, false, null); //phone already in use
                        #header('Location:/UniRent/Owner/profile');
                    }
                }
                else
                {   
                    $view->editProfile($owner, $picture, true, false, false, false, false, false, null); //Username already in use
                    #header('Location:/UniRent/Owner/profile');
                }
            }
            else
            {   
                $view->editProfile($owner, $picture, false, true, false, false, false, false, null); //Email already in use
                #header('Location:/UniRent/Owner/profile');
            }
        }
    }

    private static function changePhoto(?string $oldPhoto, ?array $picture, EOwner $oldOwner) : ?EPhoto{

        $PM=FPersistentManager::getInstance();

        if(!is_null($oldPhoto)){

            print "La vecchia foto non è null<br>";
                    
            $photoId=$oldOwner->getPhoto()->getId();

            is_null($picture) ? $photo = new EPhoto($photoId, $oldPhoto, 'other', null)
                              : $photo = new EPhoto($photoId, $picture['img'], 'other', null);

        } else {

            print "La vecchia foto è null";

            if(is_null($picture)) {

                print "La nuova foto è null<br>";
                $photo = null;

            } else {

                print "La nuova foto non è null<br>";
                $photo = new EPhoto(null, $picture['img'], 'other', null);
                $risultato = $PM->storeAvatar($photo);

                if(!$risultato){
                    header('Location:/UniRent/Owner/profile/error');
                }
            }
        }

        return $photo;
    }

    private static function changePassword($oldPassword, $newPassword, $oldStudent, $photoError):array{

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
                    print "La password non rispetta i requisiti minimi<br>";
                    //$view->editProfile($oldStudent, $photoError, true, false, false, false);

                } else $password=$newPassword;
                
            } else {
                $error = 1;
                print "La vecchia password non corrisponde<br>";
                //$view->editProfile($oldStudent, $photoError, false, false, false, true);
                $password=$session->getSessionElement('password');
            }
        }

        return [$password, $error];
    }

    public static function deletePhoto(){
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

    public static function contact(?string $modalSuccess=null)
    {   $session = USession::getInstance();
        $type = $session->getSessionElement('userType');
        if ($type === null) {
            header('Location:/UniRent/User/contact');
        } else if ($type ==='Student') {
            header('Location:/UniRent/Student/contact');
        }
        $view = new VOwner();
        
        $view->contact($modalSuccess);
    }
    public static function about()
    {   $session = USession::getInstance();
        $type = $session->getSessionElement('userType');
        if ($type === null) {
            header('Location:/UniRent/User/about');
        } else if ($type ==='Student') {
            header('Location:/UniRent/Student/about');
        }
        $view = new VOwner();
        
        $view->about();
    }
     public static function reviews(?string $modalSuccess=null) {
        self::checkIfOwner();
        $view = new VOwner();
        $session=USession::getInstance();
        $id=$session->getSessionElement('id');
        $reviewsData = self::reviewsDataByrecipient($id, TType::OWNER);
        
        $view->reviews($reviewsData, $modalSuccess);
    }

    public static function addAccommodation()
    {   self::checkIfOwner();
        $view=new VOwner();
        
        $view->addAccommodation();
    }

    public static function addAccommodationOperations()
    {
        $pictures=USuperGlobalAccess::getPost('uploadedImagesData');
        $myarray=json_decode($pictures,true);
        $array_photos=EPhoto::fromJsonToPhotos($myarray);

        $title=USuperGlobalAccess::getPost('title');
        $price=USuperGlobalAccess::getPost('price');
        $deposit=(float)USuperGlobalAccess::getPost('deposit');
        $startDate=(int) USuperGlobalAccess::getPost('startDate');
        $month=USuperGlobalAccess::getPost('month');
        $visits=USuperGlobalAccess::getPost('visitAvailabilityData');  #json in arrivo dal post
        $places=(int)USuperGlobalAccess::getPost('places');
        $duration=EAccommodation::DurationOfVisit($visits);
        
        if(!is_null($duration) and $duration>0)  #se la durata delle visite è zero non ci saranno visite
        {
            $array_visit=EAccommodation::fromJsonToArrayOfVisit($visits);
        }
        else
        {
            $array_visit=array();
            $duration=0;
        }
 
        if($month=='Sep')
        {
            $month=8;
        }
        else
        {
            $month=9;
        }

        $address=USuperGlobalAccess::getPost('address');
        $city=USuperGlobalAccess::getPost('city');
        $postalCode=USuperGlobalAccess::getPost('postalCode');
        $description=USuperGlobalAccess::getPost('description');
        $men=USession::getInstance()->booleanSolver(USuperGlobalAccess::getPost('men'));
        $women=USession::getInstance()->booleanSolver(USuperGlobalAccess::getPost('women'));
        $smokers=USession::getInstance()->booleanSolver(USuperGlobalAccess::getPost('smokers'));
        $animals=USession::getInstance()->booleanSolver(USuperGlobalAccess::getPost('animals'));

        $date= new DateTime('now');
        $year=(int)$date->format('Y');
        $date=$date->setDate($year,$month,$startDate);

        $PM=FPersistentManager::getInstance();
        
        $idOwner=$PM->getOwnerIdByUsername(USession::getInstance()->getSessionElement('username'));
        $addressObj= new Address();
        $addressObj=$addressObj->withAddressLine1($address)->withPostalcode($postalCode)->withLocality($city);
        #print $addressObj->getAddressLine1().' '.$addressObj->getPostalCode().' '.$addressObj->getLocality();
        $accomodation = new EAccommodation(null,$array_photos,$title,$addressObj,$price,$date,$description,$places,$deposit,$array_visit,$duration,$men,$women,$animals,$smokers, true,$idOwner);
        $result=$PM->store($accomodation);
        $result ? header('Location:/UniRent/Owner/home/success') : header('Location:/UniRent/Owner/home/error');

    }
    public static function publicProfileFromOwner(string $username, ?string $modalSuccess=null)
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
            #print_r($owner);
        }
        $reviewsData = self::reviewsDataByrecipient($owner->getId(), TType::OWNER);
        
        $view->publicProfileFromOwner($owner, $reviewsData, $self, $modalSuccess);
    }
    public static function publicProfileFromStudent(string $username, ?string $modalSuccess=null)
    {   self::checkIfStudent();
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

        $reviewsData = self::reviewsDataByrecipient($owner->getId(), TType::OWNER);
        $session=USession::getInstance();
        $leavebleReviews=$PM->remainingReviewStudentToOwner($session->getSessionElement('id'), $owner->getId());
        $view->publicProfileFromStudent($owner, $reviewsData, $modalSuccess, $leavebleReviews);
    }
    public static function publicProfile(string $username) {
        self::checkIfOwner();
        $PM=FPersistentManager::getInstance();
        $user=$PM->verifyUserUsername($username);
        if ($user['type'] === 'Student') {
            CStudent::publicProfileFromOwner($username);
        } else {
            self::publicProfileFromOwner($username);
        }
    }
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
            if ($recipient->getStatus() === TStatusUser::BANNED) {
                $profilePic = "/UniRent/Smarty/images/BannedUser.png";
            } else if ($profilePic === null) {
                $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
            }
            else
            {
                $profilePic=(EPhoto::toBase64(array($profilePic)))[0]->getPhoto();
            }
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
    public static function viewOwnerAds(int $id) {
        self::checkIfStudent();
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
        #print_r($accommodations);
        
        $view->viewOwnerAds($accommodations, $username);
    }


    public static function editAccommodation(string $id) {
        self::checkIfOwner();
        $session = USession::getInstance();
        $view = new VOwner();
        $PM=FPersistentManager::getInstance();
        $accommodation = $PM->load('EAccommodation', (int)$id);
        if ($accommodation->getIdOwner() !== $session->getSessionElement('id')) {
            $viewError= new VError();
            $viewError->error(403);
            exit();
        }
        $photos_acc=$accommodation->getPhoto();
        $uploadedPhotos=EPhoto::toBase64($photos_acc);

        $img=array();
        foreach($uploadedPhotos as $p)
        {
            $img[]=$p->getPhoto();
        }

        $accommodationData = [
            'title' => $accommodation->getTitle(),
            'price' => $accommodation->getPrice(),
            'deposit' => $accommodation->getDeposit(),
            'date' => $accommodation->getStart(),
            'startDate' => $accommodation->getStart()->format('d'),
            'month' => $accommodation->getStart()->format('M') == 9 ? 'september' : 'october',
            'address' => $accommodation->getAddress()->getAddressLine1(),
            'city' => $accommodation->getAddress()->getLocality(),
            'postalCode' => $accommodation->getAddress()->getPostalCode(),
            'description' => $accommodation->getDescription(),
            'men' => $accommodation->getMan(),
            'women' => $accommodation->getWoman(),
            'animals' => $accommodation->getPets(),
            'smokers' => $accommodation->getSmokers(),
            'places' => $accommodation->getPlaces()
        ];
        
        $visitAvailabilityData = [];
        foreach ($accommodation->getVisit() as $day =>$times) {
            if ($times !== []) {
            $endTime= new DateTime($times[count($times)-1]);
            $endTime->modify('+' . $accommodation->getVisitDuration() . ' minutes');
            $visitAvailabilityData[$day] = [
                'start' => $times[0],
                'end' => $endTime->format('H:i'),
                'duration' => $accommodation->getVisitDuration()
            ];
        }
        }
        print_r($accommodation->getVisit());
        
        $view->editAccommodation($accommodationData, $img , $visitAvailabilityData, $id);
        
    }
    
    public static function editAccommodationOperations(int $id)
    {
        self::checkIfOwner();
        $pictures=USuperGlobalAccess::getPost('uploadedImagesData');
        $myarray=json_decode($pictures,true);
        $array_photos=EPhoto::fromJsonToPhotos($myarray);
        $title=USuperGlobalAccess::getPost('title');
        $price=USuperGlobalAccess::getPost('price');
        $deposit=(float)USuperGlobalAccess::getPost('deposit');
        $startDate=(int) USuperGlobalAccess::getPost('startDate');
        $month=USuperGlobalAccess::getPost('month');
        $visits=USuperGlobalAccess::getPost('visitAvailabilityData');  #json in arrivo dal post
        $places=(int)USuperGlobalAccess::getPost('places');
        $duration=EAccommodation::DurationOfVisit($visits);
        
        if(!is_null($duration) and $duration>0)  #se la durata delle visite è zero non ci saranno visite
        {
            $array_visit=EAccommodation::fromJsonToArrayOfVisit($visits);
            print_r($array_visit);
        }
        else
        {
            $array_visit=array();
            $duration=0;
        }
 
        if($month=='september')
        {
            $month=9;
        }
        else
        {
            $month=10;
        }

        $address=USuperGlobalAccess::getPost('address');
        $city=USuperGlobalAccess::getPost('city');
        $postalCode=USuperGlobalAccess::getPost('postalCode');
        $description=USuperGlobalAccess::getPost('description');
        $men=USession::getInstance()->booleanSolver(USuperGlobalAccess::getPost('men'));
        $women=USession::getInstance()->booleanSolver(USuperGlobalAccess::getPost('women'));
        $smokers=USession::getInstance()->booleanSolver(USuperGlobalAccess::getPost('smokers'));
        $animals=USession::getInstance()->booleanSolver(USuperGlobalAccess::getPost('animals'));

        $date= new DateTime('now');
        $year=(int)$date->format('Y');
        $date=$date->setDate($year,$month,$startDate);
    
        $PM=FPersistentManager::getInstance();

        $status=$PM->load('EAccommodation',$id)->getStatus();
        
        $idOwner=$PM->getOwnerIdByUsername(USession::getInstance()->getSessionElement('username'));
        $addressId = $PM->load('EAccommodation', $id)->getAddress()->getSortingCode();
        $addressObj= new Address();
        $addressObj=$addressObj->withAddressLine1($address)->withPostalcode($postalCode)->withLocality($city)->withSortingCode($addressId);
        #print $addressObj->getAddressLine1().' '.$addressObj->getPostalCode().' '.$addressObj->getLocality();
        //Nella seguente riga manca il penultimo attributo status che deve essere true o false
        $accomodation = new EAccommodation($id,$array_photos,$title,$addressObj,$price,$date,$description,$places,$deposit,$array_visit,$duration,$men,$women,$animals,$smokers,$status,$idOwner);
        $result=$PM->update($accomodation);
        $id = $accomodation->getIdAccommodation();
        
        if ($result) {
            header('Location:/UniRent/Owner/accommodationManagement/'.$id.'/success');
        } else {
            header('Location:/UniRent/Owner/accommodationManagement/'.$id.'/error');
        }
    }


    public static function tenants(string $kind) {
        self::checkIfOwner();
        $session=USession::getInstance();
        $ownerId=$session->getSessionElement('id');
        $PM=FPersistentManager::getInstance();
        $view = new VOwner();
        $tenantsArray = $PM->getTenants($kind,$ownerId);
        $tenants=[];
        $accommodations=$PM->loadAccommodationsByOwner($ownerId);
        foreach ($accommodations as $accom) {
            $accommodationTitles[$accom->getIdAccommodation()]=$accom->getTitle();
        }
        foreach ($tenantsArray as $idAccommodation => $students) {
            $accommodationTitle = $PM->getTitleAccommodationById($idAccommodation);
            $tenantList = [];
            foreach ($students as $student) {
                $profilePic = ($student[0])->getPhoto();
                if ($student[0]->getStatus() === TStatusUser::BANNED) {
                    $profilePic = "/UniRent/Smarty/images/BannedUser.png";
                } else if ($profilePic === null) {
                    $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
                }
                else
                {
                    $profilePic=$profilePic->getPhoto();
                }
                $tenantList[] = [
                    'username' => ($student[0])->getUsername(),
                    'image' => $profilePic,
                    'expiryDate' => $student[1],
                    'status' => ($student[0])->getStatus()->value
                ];
            }

            $tenants[] = [
                'accommodation' => $accommodationTitle,
                'tenants' => $tenantList
            ];
        }
        /*
        $tenants[]=[
            'accommodation' => 'Tutti',
            'tenants' =>[ [
                'username' => 'Tutti',
                'image' => '/UniRent/Smarty/images/ImageIcon.png',
                'expiryDate' => '01-11-2024'
            ], 
            [
                'username' => 'Another',
                'image' => '/UniRent/Smarty/images/ImageIcon.png',
                'expiryDate' => '01-11-2025'
            ]
            ]
        ];
        */
        
        $view->tenants($tenants, $kind, $accommodationTitles,0);
    }

    public static function filterTenants(string $type)
    {   self::checkIfOwner();
        $session=USession::getInstance();
        $ownerId=$session->getSessionElement('id');
        $PM=FPersistentManager::getInstance();
        $view = new VOwner();

        $accommodation_name=USuperGlobalAccess::getPost('accommodation');
        $t_username=USuperGlobalAccess::getPost('username');
        $rateT=(int)USuperGlobalAccess::getPost('rateT');
        $date=USuperGlobalAccess::getPost('date');
        $t_age=(int)USuperGlobalAccess::getPost('age');
        $men=USuperGlobalAccess::getPost('men');
        $women=USuperGlobalAccess::getPost('women');
        if($men==='false')
        {
            $men=false;
        }
        else
        {
            $men=true;
        }
        if($women==='false')
        {
            $women=false;
        }
        else
        {
            $women=true;
        }

        #print var_dump($rateT);
    
        $tenantsArray=$PM->getFilterTenants($type,$accommodation_name,$t_username,$t_age,$rateT,$date,$men,$women,$ownerId);

        $tenants=[];
        foreach ($tenantsArray as $idAccommodation => $students) {
            $accommodationTitle = $PM->getTitleAccommodationById($idAccommodation);
            $tenantList = [];
            foreach ($students as $student) {
                $profilePic = $student[0]->getPhoto();
                if ($student[0]->getStatus() === TStatusUser::BANNED) {
                    $profilePic = "/UniRent/Smarty/images/BannedUser.png";
                } else if ($profilePic === null) {
                    $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
                }
                else
                {
                    $profilePic=$profilePic->getPhoto();
                }
                $tenantList[] = [
                    'username' => $student[0]->getUsername(),
                    'image' => $profilePic,
                    'expiryDate' => $student[1],
                    'status' => $student[0]->getStatus()->value
                ];
            }

            $tenants[] = [
                'accommodation' => $accommodationTitle,
                'tenants' => $tenantList
            ];
        }
        $accommodations=$PM->loadAccommodationsByOwner($ownerId);
        foreach ($accommodations as $accom) {
            $accommodationTitles[$accom->getIdAccommodation()]=$accom->getTitle();
        }
        
        $view->tenants($tenants, $type, $accommodationTitles, $rateT);
    }
    private static function checkIfOwner() {
        $session = USession::getInstance();
        if ($session->getSessionElement('userType') !== 'Owner') {
            $view= new VError();
            $view->error(403);
            exit();
        }
    }
    private static function checkIfStudent() {
        $session = USession::getInstance();
        if ($session->getSessionElement('userType') !== 'Student') {
            $view= new VError();
            $view->error(403);
            exit();
        }
    }
}