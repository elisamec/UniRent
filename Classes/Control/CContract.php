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
        if ($kind=='ongoing') {
            $kind='onGoing';
        }
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
}