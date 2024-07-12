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
use DateTime;

class COwner 
{
    public static function home()
    {
        $view = new VOwner();
        $PM=FPersistentManager::getInstance();
        $username=USession::getInstance()::getSessionElement('username');
        $ownerId=$PM->getOwnerIdByUsername($username);
        $accommodationEntities=$PM->loadAccommodationsByOwner($ownerId);
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
            $accommodations[]=[
                'id'=>$accom->getIdAccommodation(),
                'photo'=>$photo,
                'title'=>$accom->getTitle(),
                'address'=>$accom->getAddress()->getAddressLine1() .", ". $accom->getAddress()->getLocality(),
                'price'=>$accom->getPrice()
            ];
        }
        #print_r($accommodations);
        $view->home($accommodations);
    }
    public static function accommodation(int $idAccommodation) {
        $view = new VOwner();
        $PM = FPersistentManager::getInstance();
       
        $accomm = $PM->load('EAccommodation', $idAccommodation);
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
        if(is_null($owner_photo)){}
        else
        {
            $owner_photo_64=EPhoto::toBase64(array($owner_photo));
            $owner->setPhoto($owner_photo_64[0]);
            #print_r($owner);
        }
        
        $reviews = $PM->loadByRecipient($accomm->getIdAccommodation(), TType::ACCOMMODATION);
        $reviewsData = [];
        
        foreach ($reviews as $review) {
            $author = $PM::load('EStudent',$review->getIdAuthor());
            $profilePic = $author->getPhoto();
            if ($profilePic === null) {
                $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
            }
            else
            {
                $profilePic=(EPhoto::toBase64(array($profilePic)))[0];
            }
            $reviewsData[] = [
                'title' => $review->getTitle(),
                'username' => $author->getUsername(),
                'stars' => $review->getValutation(),
                'content' => $review->getDescription(),
                'userPicture' => $profilePic,
            ];
        }
        $view->accommodation($accomm, $owner, $reviewsData,$picture);
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
        $ownerId = $owner->getId();
        if($result){
            $session->setSessionElement('id', $ownerId);
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
        //$user = $session->getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        //$owner=$PM->getOwnerByUsername($user);
        $id = $session->getSessionElement('id');
        $owner=$PM->load("EOwner", $id);
        $ph = null;

        if(is_null($owner)){
            $session->setSessionElement('photo', $ph);
            print '<b>500 : SERVER ERROR </b>';
        } else {

            $ph = $owner->getPhoto();
            
            if(!is_null($ph)) {

                $ph=$ph->getPhoto();

                $session->setSessionElement('photo', $ph);

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
        $id = $session->getSessionElement('id');
        $owner=$PM->load("EOwner", $id);
        if(is_null($owner)){

            print '<b>500 : SERVER ERROR </b>';
        } else {

            $photo = USession::getInstance()::getSessionElement('photo');

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
        $oldUsername=$session::getSessionElement('username');

        $ownerId=$session::getSessionElement('id');
        $oldPassword=USuperGlobalAccess::getPost('oldPassword');

        $oldPhoto = $session::getSessionElement('photo');
        $base64 = base64_encode($oldPhoto);
        $photoError = "data:" . 'image/jpeg' . ";base64," . $base64;

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
                            $result=$PM::update($owner);
                            if($result  && !$error)
                            {   
                                $ph = $photo->getPhoto();
                                if (is_null($ph)) $ph = null;
                                $session::setSessionElement('username', $newUsername);
                                $session::setSessionElement('password',$newPassword);
                                $session->setSessionElement('photo',$ph);
                                header("Location:/UniRent/Owner/profile");
                            }
                            elseif (!$result) {
                    
                                header("HTTP/1.1 500 Internal Server Error");
                                
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

            print "La vecchia foto non è null<br>";
                    
            $photoId=$oldOwner->getPhoto()->getId();

            is_null($picture) ? $photo = new EPhoto($photoId, $oldPhoto, 'other', null, null)
                              : $photo = new EPhoto($photoId, $picture['img'], 'other', null, null);

        } else {

            print "La vecchia foto è null";

            if(is_null($picture)) {

                print "La nuova foto è null<br>";
                $photo = null;

            } else {

                print "La nuova foto non è null<br>";
                $photo = new EPhoto(null, $picture['img'], 'other', null, null);
                $risultato = $PM->storeAvatar($photo);

                if(!$risultato){
                    header("HTTP/1.1 500 Internal Server Error");
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
            $password=$session::getSessionElement('password');
        } else {
            
            if($oldPassword===$session::getSessionElement('password')){
                if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&()])[A-Za-z\d@$!%*?&()]{8,}$/' , $newPassword)){

                    $error = 1;
                    $password=$session::getSessionElement('password');
                    print "La password non rispetta i requisiti minimi<br>";
                    //$view->editProfile($oldStudent, $photoError, true, false, false, false);

                } else $password=$newPassword;
                
            } else {
                $error = 1;
                print "La vecchia password non corrisponde<br>";
                //$view->editProfile($oldStudent, $photoError, false, false, false, true);
                $password=$session::getSessionElement('password');
            }
        }

        return [$password, $error];
    }

    public static function deletePhoto(){

        $PM=FPersistentManager::getInstance();
        $session=USession::getInstance();
        $ownerId=$session::getSessionElement('id');
        $photo = $PM -> load('EOwner', $ownerId) -> getPhoto();

        if(is_null($photo)){

            print "Non hai nessuna foto da eliminare";
            $result = 0;

        } else {
            $photoID = $photo->getId();
            $result = $PM->delete('EPhoto', $photoID);
        }

        

        if ($result > 0) {
            $photo = null;
            $session->setSessionElement('photo',$photo);
            header('Location:/UniRent/Owner/profile');
        }  else print "Errore nell'eliminazione della foto <br>";

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
        $pictures=USuperGlobalAccess::getPost('uploadedImagesData');
        $myarray=json_decode($pictures,true);
        $array_photos=EPhoto::fromJsonToPhotos($myarray);

        $title=USuperGlobalAccess::getPost('title');
        $price=USuperGlobalAccess::getPost('price');
        $deposit=(float)USuperGlobalAccess::getPost('deposit');
        $startDate=(int) USuperGlobalAccess::getPost('startDate');
        $month=USuperGlobalAccess::getPost('month');
        $visits=USuperGlobalAccess::getPost('visitAvailabilityData');  #json in arrivo dal post
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
        $men=USession::booleanSolver(USuperGlobalAccess::getPost('men'));
        $women=USession::booleanSolver(USuperGlobalAccess::getPost('women'));
        $smokers=USession::booleanSolver(USuperGlobalAccess::getPost('smokers'));
        $animals=USession::booleanSolver(USuperGlobalAccess::getPost('animals'));

        $date= new DateTime('now');
        $year=(int)$date->format('Y');
        $date=$date->setDate($year,$month,$startDate);

        $PM=FPersistentManager::getInstance();
        
        $idOwner=$PM->getOwnerIdByUsername(USession::getInstance()::getSessionElement('username'));
        $addressObj= new Address();
        $addressObj=$addressObj->withAddressLine1($address)->withPostalcode($postalCode)->withLocality($city);
        #print $addressObj->getAddressLine1().' '.$addressObj->getPostalCode().' '.$addressObj->getLocality();
        $accomodation = new EAccommodation(null,$array_photos,$title,$addressObj,$price,$date,$description,$deposit,$array_visit,$duration,$men,$women,$animals,$smokers,$idOwner);
        $result=$PM::store($accomodation);
        if($result)
        {
            header('Location:/UniRent/Owner/home');
        }
        else
        {
            print '500 : SERVER ERROR';
        }
    }
    public static function publicProfileFromOwner(string $username)
    {
        $view = new VOwner();
        $PM=FPersistentManager::getInstance();
        $owner=$PM->getOwnerByUsername($username);
        $owner_photo=$owner->getPhoto();
        if(is_null($owner_photo)){}
        else
        {
            $owner_photo_64=EPhoto::toBase64(array($owner_photo));
            $owner->setPhoto($owner_photo_64[0]);
            #print_r($owner);
        }


        $reviews = FReview::getInstance()->loadByRecipient($owner->getId(), TType::OWNER);
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
        $view->publicProfileFromOwner($owner, $reviewsData);
    }
    public static function publicProfileFromStudent(string $username)
    {
        $view = new VOwner();
        $PM=FPersistentManager::getInstance();
        $owner=$PM->getOwnerByUsername($username);
        $owner_photo=$owner->getPhoto();
        if(is_null($owner_photo)){}
        else
        {
            $owner_photo_64=EPhoto::toBase64(array($owner_photo));
            $owner->setPhoto($owner_photo_64[0]);
            #print_r($owner);
        }


        $reviews = FReview::getInstance()->loadByRecipient($owner->getId(), TType::OWNER);
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
        $view->publicProfileFromStudent($owner, $reviewsData);
    }
    public static function publicProfile(string $username) {
        $PM=FPersistentManager::getInstance();
        $user=$PM->verifyUserUsername($username);
        $location='/UniRent/'.$user['type'].'/publicProfileFromOwner/'.$username;
        header('Location:'.$location);
    }
    public static function postedReview() {
        $view = new VOwner();
        $session=USession::getInstance();
        $username=$session::getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        $ownerId=$PM->getOwnerIdByUsername($username);
        $reviews = $PM->loadReviewsByAuthor($ownerId, TType::OWNER);
        $reviewsData = [];

        foreach ($reviews as $review) {
            $profilePic = $PM->load( 'E' . $review->getAuthorType()->value, $review->getIdAuthor())->getPhoto();
            if ($profilePic === null) {
                $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
            }
            $reviewsData[] = [
                'title' => $review->getTitle(),
                'username' => $PM->load( 'E' . $review->getRecipientType()->value, $review->getIDRecipient())->getUsername(),
                'stars' => $review->getValutation(),
                'content' => $review->getDescription(),
                'userPicture' => $profilePic,
            ];
        }
        $view->postedReview($reviewsData);
    }

}