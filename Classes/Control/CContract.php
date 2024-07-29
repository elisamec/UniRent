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
        $creditCardNumber = USuperGlobalAccess::getPost('creditCardNumber');
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
            $cvv = USuperGlobalAccess::getPost('cvv');
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
            header('Location:' . $_SERVER['HTTP_REFERER'].'/payed');
        } else {
            header('Location:' . $_SERVER['HTTP_REFERER'].'/error');
        }

    }
}