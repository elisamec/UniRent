<?php 

namespace Classes\Control;

use Classes\Entity\EContract;
use Classes\Entity\ECreditCard;
use Classes\Entity\EPhoto;
use Classes\Foundation\FPersistentManager;
use Classes\Tools\TStatusContract;
use Classes\Tools\TStatusUser;
use Classes\Tools\TType;
use Classes\Utilities\USession;
use Classes\Utilities\USuperGlobalAccess;
use Classes\View\VOwner;
use Classes\View\VStudent;
use Classes\View\VError;
use DateTime;

require __DIR__.'/../../vendor/autoload.php';

class CContract
{
    public static function pay(int $idReservation) {
        $session = USession::getInstance();
        $idStudent = $session->getSessionElement('id');
        $PM = FPersistentManager::getInstance();
        $reservation = $PM->load('EReservation', $idReservation);
        $creditCardNumber = USuperGlobalAccess::getPost('creditCardNumber') ?? USuperGlobalAccess::getPost('creditNewCardNumber');
        $futureDate= $reservation->getFromDate() > new DateTime('today');
        if ($PM->existsTheCard($creditCardNumber)) {
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
            header('Location:/Contract/contractDetails/'.$idReservation.'/payed'); //redirect to contract page of the one you jest payed
        } else {
            header('Location:/Contract/contractDetails/'.$idReservation.'/error');
        }

    }
    public static function showStudent(string $kind) {
        $session=USession::getInstance();
        $id=$session->getSessionElement('id');
        $PM= FPersistentManager::getInstance();
        $contracts=$PM->getContractsByStudent($id, null, $kind);
        #print_r($contracts);
        
        usort($contracts, function($a, $b) {
            $today = new DateTime();
            $fromDateA = $a->getFromDate();
            $fromDateB = $b->getFromDate();
        
            $diffA = $today->diff($fromDateA)->days;
            $diffB = $today->diff($fromDateB)->days;
        
            return $diffA - $diffB;
        });
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
        $view->showContracts($contractsData, $kind);

    }
    /*
    public static function showOwner(string $kind, string $statusAccept="null", string $statusDeny="null"):void {
        $session=USession::getInstance();
        $id=$session::getSessionElement('id');
        $PM=FPersistentManager::getInstance();
        $reservationsArray=$PM->getWaitingReservations($id);
        $view = new VOwner();
        $reservationData=[];
        foreach ($reservationsArray as $idAccommodation => $reservations) {
            usort($reservations, function($a, $b) {
                $dateA = $a->getMade();
                $dateB = $b->getMade();
                
                // Compare DateTime objects directly
                return $dateA <=> $dateB;
            });
            $accommodationTitle = $PM->getTitleAccommodationById($idAccommodation);
            $studentList = [];

            foreach ($reservations as $reservation) {
                $formatted = self::formatDate($reservation->getMade()->setTime(0,0,0));
                $student=$PM->load('EStudent', $reservation->getIdStudent());
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
                    'idReservation' => $reservation->getID(),
                    'username' => $student->getUsername(),
                    'image' => $profilePic,
                    'period' => 'from '. $reservation->getFromDate()->format('d/m/Y') . ' to ' . $reservation->getToDate()->format('d/m/Y'),
                    'expires' => $formatted,
                    'status' => $studentStatus->value
                ];
            }

            $reservationData[] = [
                'accommodation' => $accommodationTitle,
                'reservations' => $studentList
            ];
        }
        $view->showReservations($reservationData);
    }
        */
    public static function contractDetails(int $idContract) {
        $session = USession::getInstance();
        $userType = $session->getSessionElement('userType');
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
                $author = $PM::load('EStudent', $review->getIdAuthor());
                $profilePic = $author->getPhoto();
                if ($author->getStatus() === TStatusUser::BANNED) {
                    $profilePic = "/UniRent/Smarty/images/BannedUser.png";
                } else if ($profilePic === null) {
                    $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
                }
                else
                {
                    $profilePic=(EPhoto::toBase64(array($profilePic)))[0]->getPhoto();
                }
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
            $view->contractDetails($contract, $accommodation, $owner, $cardNumber, $cardHolder, $picture, $reviewsData);
        }
        else {
            /*
            $student = $PM->load('EStudent', $reservation->getIdStudent());
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
                $author = $PM::load('EStudent', $review->getIdAuthor());
                $profilePic = $author->getPhoto();
                if ($author->getStatus() === TStatusUser::BANNED) {
                    $profilePic = "/UniRent/Smarty/images/BannedUser.png";
                } else if ($profilePic === null) {
                    $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
                }
                else
                {
                    $profilePic=(EPhoto::toBase64(array($profilePic)))[0]->getPhoto();
                }
                $reviewsData[] = [
                    'title' => $review->getTitle(),
                    'username' => $author->getUsername(),
                    'userStatus' => $author->getStatus()->value,
                    'stars' => $review->getValutation(),
                    'content' => $review->getDescription(),
                    'userPicture' => $profilePic,
                ];
            }
            $view = new VOwner();
            $view->reservationDetails($reservation, $student, self::formatDate($reservation->getMade()->setTime(0,0,0)), $reviewsData);
            */
        }
    }
}