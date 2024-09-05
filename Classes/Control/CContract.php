<?php 

namespace Classes\Control;

use Classes\Entity\EContract;
use Classes\Entity\ECreditCard;
use Classes\Entity\EPhoto;
use Classes\Foundation\FPersistentManager;
use Classes\Tools\TStatusContract;
use Classes\Tools\TStatusUser;
use Classes\Tools\TType;
use Classes\Utilities\UFormat;
use Classes\Utilities\USession;
use Classes\Utilities\USort;
use Classes\Utilities\USuperGlobalAccess;
use Classes\View\VOwner;
use Classes\View\VStudent;
use Classes\View\VError;
use DateTime;

require __DIR__.'/../../vendor/autoload.php';

/**
 * Class CContract
 * 
 * This class is responsible for managing contracts
 * 
 * @package Classes\Control
 */
class CContract
{
    /**
     * Method pay
     * 
     * this method is used to pay a reservation
     * 
     * @param int $idReservation
     * @return void
     */
    public static function pay(int $idReservation): void {
        CStudent::checkIfStudent();
        $session = USession::getInstance();
        $idStudent = $session->getSessionElement('id');
        $PM = FPersistentManager::getInstance();
        $reservation = $PM->load('EReservation', $idReservation);
        $creditCardNumber = USuperGlobalAccess::getPost('creditCardNumber') ?? USuperGlobalAccess::getPost('creditNewCardNumber');
        $futureDate= $reservation->getFromDate() > new DateTime('today');
        if ($PM->existsTheCard($creditCardNumber)) {
            $card=$PM->loadCreditCard($creditCardNumber);
            if ($idStudent !== $card->getStudentID()) {
                $viewError= new VError();
                $viewError->error(403);
            exit();
            }
            if ($futureDate) {
                $status=TStatusContract::FUTURE;
            } else {
                $status=TStatusContract::ONGOING;
            }
            $contract= new EContract($status, $creditCardNumber, $reservation, new DateTime('now'));
            $res=$PM->store($contract);
        } else {
            $name = USuperGlobalAccess::getPost('name');
            $surname = USuperGlobalAccess::getPost('surname');
            $expirationDate = USuperGlobalAccess::getPost('expirydate');
            $cvv = (int)USuperGlobalAccess::getPost('cvv');
            $title = USuperGlobalAccess::getPost('cardTitle');
            $creditCard = new ECreditCard($creditCardNumber, $name, $surname, $expirationDate, $cvv, false, $idStudent, $title);
            $PM->store($creditCard);
            if ($futureDate) {
                $status=TStatusContract::FUTURE;
            } else {
                $status=TStatusContract::ONGOING;
            }
            $contract= new EContract($status, $creditCardNumber, $reservation, new DateTime('now'));
            $res=$PM->store($contract);
        }
        if ($res) {
            header('Location:/UniRent/Contract/contractDetails/'.$idReservation.'/success'); //redirect to contract page of the one you jest payed
        } else {
            header('Location:/UniRent/Contract/contractDetails/'.$idReservation.'/error');
        }

    }

    /**
     * Method showStudent
     * 
     * This method shows the contract list to a student
     * 
     * @param string $kind
     * @param string|null $modalSuccess
     * @return void
     */
    public static function showStudent(string $kind, ?string $modalSuccess=null):void {
        CStudent::checkIfStudent();
        $session=USession::getInstance();
        $id=$session->getSessionElement('id');
        $PM= FPersistentManager::getInstance();
        $contracts=$PM->getContractsByStudent($id, null, $kind);
        
        $contracts = USort::sortArray($contracts, 'contract');
        $contractsData = [];
        foreach ($contracts as $contract) {
            $accommodation=$PM->load('EAccommodation', $contract->getAccomodationId());
            $period = $contract->getFromDate()->format('d/m/Y') . ' - ' . $contract->getToDate()->format('d/m/Y');
            if ($accommodation->getPhoto() === []) {
                $accommodationPhoto = "/UniRent/Smarty/images/NoPic.png";
            } else {
                $accommodationPhoto = (EPhoto::toBase64($accommodation->getPhoto()))[0]->getPhoto();
            }
            $contractsData[] = [
                'idContract' => $contract->getId(),
                'title' => $accommodation->getTitle(),
                'photo' => $accommodationPhoto,
                'period' => $period,
                'price' => $accommodation->getPrice(),
                'address' => $accommodation->getAddress()->getAddressLine1() . ', ' . $accommodation->getAddress()->getLocality(),
            ];
        }
        $view= new VStudent();
        $view->showContracts($contractsData, $kind, $modalSuccess);

    }
    
    /**
     * Method showOwner
     * 
     * This method shows the contract list to an owner
     * 
     * @param string $kind
     * @param string|null $modalSuccess
     * @return void
     */
    public static function showOwner(string $kind, ?string $modalSuccess=null):void {
        COwner::checkIfOwner();
        $session=USession::getInstance();
        $id=$session->getSessionElement('id');
        $PM=FPersistentManager::getInstance();
        $contractsArray=$PM->getContractsByOwner($id, $kind);
        $view = new VOwner();
        $contractsData=[];
        foreach ($contractsArray as $idAccommodation => $contracts) {
            $accommodationTitle = $PM->getTitleAccommodationById($idAccommodation);
            $studentList = [];
            $contracts = USort::sortArray($contracts, 'contract');

            foreach ($contracts as $contract) {
                $student=$PM->load('EStudent', $contract->getIdStudent());
                UFormat::photoFormatUser($student);
                $profilePic = $student->getPhoto() === null ? "/UniRent/Smarty/images/ImageIcon.png" : $student->getPhoto()->getPhoto();
                $studentList[] = [
                    'idContract' => $contract->getID(),
                    'username' => $student->getUsername(),
                    'image' => $profilePic,
                    'period' => 'from '. $contract->getFromDate()->format('d/m/Y') . ' to ' . $contract->getToDate()->format('d/m/Y')
                ];
            }

            $contractsData[] = [
                'accommodation' => $accommodationTitle,
                'contracts' => $studentList
            ];
        }
        $view->showContracts($contractsData, $kind, $modalSuccess);
    }

    /**
     * Method contractDetails
     * 
     * This method shows the details of a contract
     * 
     * @param int $idContract
     * @param string|null $modalSuccess
     * @return void
     */
    public static function contractDetails(int $idContract, ?string $modalSuccess=null):void {
        $session = USession::getInstance();
        $userType = $session->getSessionElement('userType');
        if ($userType===null) {
            $viewError= new VError();
            $viewError->error(403);
            exit();
        }
        $PM=FPersistentManager::getInstance();
        $contract = $PM->load('EContract', $idContract);
        if ($userType==='Student') {
            $accommodation = $PM->load('EAccommodation', $contract->getAccomodationId());
            $photos_acc=$accommodation->getPhoto();
            $photo_acc_64=EPhoto::toBase64($photos_acc);
            $accommodation->setPhoto($photo_acc_64);

            $picture=array();
            foreach($accommodation->getPhoto() as $p)
            {
                if(is_null($p)){}
                else
                {
                    $picture[]=$p->getPhoto();
                }
            }
            
            $owner = $PM->load('EOwner', $accommodation->getIdOwner());
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
            $reviews = $PM->loadByRecipient($accommodation->getIdAccommodation(), TType::ACCOMMODATION);
            $reviewsData = [];
            
            foreach ($reviews as $review) {
                $author = $PM->load('EStudent', $review->getIdAuthor());
                if ($review->isBanned()) {
                    continue;
                }
                $profilePic = $author->getPhoto();
                $profilePic = UFormat::photoFormatReview($profilePic, $author->getStatus());
                $reviewsData[] = [
                    'title' => $review->getTitle(),
                    'username' => $author->getUsername(),
                    'userStatus' => $author->getStatus()->value,
                    'stars' => $review->getValutation(),
                    'content' => $review->getDescription(),
                    'userPicture' => $profilePic,
                ];
            }
            $creditCard = $PM->loadCreditCard($contract->getCardNumber());
            $cardNumber='**** **** **** ' . substr($creditCard->getNumber(), -4);
            $cardHolder = $creditCard->getName() . " ". $creditCard->getSurname();
            $view= new VStudent();
            $leavebleReviews=$PM->remainingReviewStudentToAccommodation($session->getSessionElement('id'), $accommodation->getIdAccommodation());
            $tenantsArray= $PM->getTenants('current',$accommodation->getIdOwner());
            $tenants=array();
            foreach ($tenantsArray as $idAccommodation => $students) {
                $accommodationTitle = FPersistentManager::getInstance()->getTitleAccommodationById($idAccommodation);
                $tenants=UFormat::getFilterTenantsFormatArray($students, $idAccommodation, $accommodationTitle, 'Student');
            }
            $view->contractDetails($contract, $accommodation, $owner, $cardNumber, $cardHolder, $picture, $reviewsData, $modalSuccess, $leavebleReviews, $tenants);
        }
        else {
            $student = $PM->load('EStudent', $contract->getIdStudent());
            $student_photo=$student->getPhoto();
            $studentStatus = $student->getStatus();
            if($studentStatus === TStatusUser::BANNED){
                
                $path = __DIR__ . "/../../Smarty/images/BannedUser.png";
                $student_photo = new EPhoto(null, file_get_contents($path), 'other', null);
                $student_photo_64=EPhoto::toBase64(array($student_photo));
                $student->setPhoto($student_photo_64[0]);
            }
            elseif(!is_null($student_photo))
            {
                $student_photo_64=EPhoto::toBase64(array($student_photo));
                $student->setPhoto($student_photo_64[0]);
            }
            $reviews = $PM->loadByRecipient($student->getId(), TType::STUDENT);
            $reviewsData = [];
            
            foreach ($reviews as $review) {
                $author = $PM->load('E'.ucfirst($review->getAuthorType()->value), $review->getIdAuthor());
                if ($review->isBanned()) {
                    continue;
                }
                $profilePic = $author->getPhoto();
                $profilePic = UFormat::photoFormatReview($profilePic, $author->getStatus());
                $reviewsData[] = [
                    'title' => $review->getTitle(),
                    'username' => $author->getUsername(),
                    'userStatus' => $author->getStatus()->value,
                    'stars' => $review->getValutation(),
                    'content' => $review->getDescription(),
                    'userPicture' => $profilePic,
                ];
            }
            $leavebleReviews=$PM->remainingReviewOwnerToStudent($session->getSessionElement('id'), $student->getId());
            $view = new VOwner();
            $view->contractDetails($contract, $student, $reviewsData, $modalSuccess, $leavebleReviews);
        }
    }
    
    /**
     * Method viewOngoing
     * the method return to the view all onGoing contracts for the accommodation with given ID
     * @param int $id
     *
     * @return void
     */
    public static function viewOngoing(int $id, ?string $modalSuccess=null):void
    {   
        COwner::checkIfOwner();
        $PM=FPersistentManager::getInstance();
        $session = USession::getInstance();
        $accommodationOwner = $PM->load('EAccommodation', $id)->getIdOwner();
        $ownerId = $session->getSessionElement('id');
        if ($accommodationOwner !== $ownerId) {
            $viewError= new VError();
            $viewError->error(403);
            exit();
        }
        $result=$PM->getOnGoingContractsByAccommodationId($id);
        $view = new VOwner();
        $contractsData=[];
        foreach ($result as $idAccommodation => $contracts) {
            $accommodationTitle = $PM->getTitleAccommodationById($idAccommodation);
            $studentList = [];
            $contracts = USort::sortArray($contracts, 'contract');

            foreach ($contracts as $contract) {
                $student=$PM->load('EStudent', $contract->getIdStudent());
                $student_photo=$student->getPhoto();
                $studentStatus = $student->getStatus();
                if($studentStatus === TStatusUser::BANNED){
                
                    $path = __DIR__ . "/../../Smarty/images/BannedUser.png";
                    $student_photo = new EPhoto(null, file_get_contents($path), 'other', null);
                    $student_photo_64=EPhoto::toBase64(array($student_photo));
                    $student->setPhoto($student_photo_64[0]);
                }
                else if(is_null($student_photo)){}
                else
                {
                    $student_photo_64=EPhoto::toBase64(array($student_photo));
                    $student->setPhoto($student_photo_64[0]);
                    #print_r($owner);
                }
                $profilePic = $student->getPhoto() === null ? "/UniRent/Smarty/images/ImageIcon.png" : $student->getPhoto()->getPhoto();
                $studentList[] = [
                    'idContract' => $contract->getID(),
                    'username' => $student->getUsername(),
                    'image' => $profilePic,
                    'period' => 'from '. $contract->getFromDate()->format('d/m/Y') . ' to ' . $contract->getToDate()->format('d/m/Y')
                ];
            }

            $contractsData[] = [
                'accommodation' => $accommodationTitle,
                'contracts' => $studentList
            ];
        }
        $view->showContracts($contractsData, 'onGoing', $modalSuccess);
    }

    /**
     * Method deleteCreditCard
     * 
     * metod used to delete a credit card
     *
     * @param string $creditCard [creditcard number]
     *
     * @return void
     */
    public static function deleteCreditCard(string $creditCard)
    {  
        CStudent::checkIfStudent();
        $number=urldecode($creditCard);  #siccome usuamo url autodescrittive il php non decodifica i parametri automaticamente ma bisogna farlo a mano
        $PM=FPersistentManager::getInstance();
        $session=USession::getInstance();
        $card=$PM->loadCreditCard($number);
        if ($card->getStudentID() !== $session->getSessionElement('id')) {
            $view=new VError();
            $view->error(403);
            exit();
        }
        $result=$PM->deleteCreditCard($number);
        if(!$result)
        {
            header('Location:/UniRent/Student/paymentMethods/success');
        }
        else
        {
           header('Location:/UniRent/Student/paymentMethods/error');
        }
    }
    /**
     * Method editCreditCard
     *
     * this method is used to edit the credit card
     * @return void
     */
    public static function editCreditCard()
    {
        $title=USuperGlobalAccess::getPost('cardTitle1');
        $number=USuperGlobalAccess::getPost('cardnumber1');
        $expiry=USuperGlobalAccess::getPost('expirydate1');
        $cvv=USuperGlobalAccess::getPost('cvv1');
        $name=USuperGlobalAccess::getPost('name1');
        $surname=USuperGlobalAccess::getPost('surname1');
        $username=USession::getInstance()->getSessionElement('username');
        $studentId=FPersistentManager::getInstance()->getStudentIdByUsername($username);

        $PM=FPersistentManager::getInstance();
        if($PM->isMainCard($studentId,$number))
        {
            $c= new ECreditCard($number,$name,$surname,$expiry,$cvv,$studentId,true,$title);
            $result=$PM->update($c);
            if($result)
            {
                header('Location:/UniRent/Student/paymentMethods/success');
            }
            else
            {
                header('Location:/UniRent/Student/paymentMethods/error');
            }
        }
        else
        {
            $c= new ECreditCard($number,$name,$surname,$expiry,$cvv,$studentId,false,$title);
            $result=$PM->update($c);
            if($result)
            {
               header('Location:/UniRent/student/paymentMethods/success');
            }
            else
            {
                header('Location:/UniRent/Student/paymentMethods/error');
            }
        }  
    }
    /**
     * Method makeMainCreditCard
     *
     * this method is used to make a credit card the main payment method
     * @param string $number [card number]
     *
     * @return void
     */
    public static function makeMainCreditCard(string $number)
    {   
        CStudent::checkIfStudent();
        $n=urldecode($number);
        $session=USession::getInstance();
        $username=$session->getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        $studentId=$PM->getStudentIdByUsername($username);
        $actualcard=$PM->loadCreditCard($n);
        if ($actualcard->getStudentID() !== $studentId) {
            $view=new VError();
            $view->error(403);
            exit();
        }
        $actualMain=$PM->getStudentMainCard($studentId);
        if(is_null($actualMain))
        {
            $actualcard->setMain(true);
            $res=$PM->update($actualcard);
            if ($res)
            {
                header('Location:/UniRent/Student/paymentMethods/success');
            }
            else
            {
                header('Location:/UniRent/Student/paymentMethods/error');
            }
        }
        else
        {
            $actualMain->setMain(false);
            $res_1=$PM->update($actualMain);
            if($res_1)
            {
                $actualcard->setMain(true);
                $res_2=$PM->update($actualcard);
                if($res_2)
                {
                    header('Location:/UniRent/Student/paymentMethods/success');
                }
                else
                {
                    header('Location:/UniRent/Student/paymentMethods/error');
                }
            }
            else
            {
                header('Location:/UniRent/Student/paymentMethods/error');
            }
        }
    }
        /**
     * Method addCreditCard
     *
     * this method is used to add a credit card as a payment method
     * @return void
     */
    public static function addCreditCard()
    {
        $title=USuperGlobalAccess::getPost('cardTitle');
        $number=USuperGlobalAccess::getPost('cardnumber');
        $expiry=USuperGlobalAccess::getPost('expirydate');
        $cvv=USuperGlobalAccess::getPost('cvv');
        $name=USuperGlobalAccess::getPost('name');
        $surname=USuperGlobalAccess::getPost('surname');
        $username=USession::getInstance()->getSessionElement('username');
        $studentId=FPersistentManager::getInstance()->getStudentIdByUsername($username);
        if(FPersistentManager::getInstance()->existsTheCard($number))
        {
            header('Location:/UniRent/Student/paymentMethods/error');
        }
        else
        {
            if(count(FPersistentManager::getInstance()->loadStudentCards($studentId))>0)
            {
                $card = new ECreditCard($number,$name,$surname,$expiry,(int)$cvv,(int)$studentId,false,$title);
                $result=FPersistentManager::getInstance()->store($card);
                if($result)
                {
                    header('Location:/UniRent/Student/paymentMethods/success');
                }
                else
                {
                    header('Location:/UniRent/Student/paymentMethods/error');
                }
            }
            else
            {
                $card = new ECreditCard($number,$name,$surname,$expiry,(int)$cvv,(int)$studentId,true,$title);
                $result=FPersistentManager::getInstance()->store($card);
                if($result)
                {
                    header('Location:/UniRent/Student/paymentMethods/success');
                }
                else
                {
                    header('Location:/UniRent/Student/paymentMethods/error');
                }
            }
        }
    }
    
    
}