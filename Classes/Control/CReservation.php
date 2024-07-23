<?php 

namespace Classes\Control;

use Classes\Entity\EPhoto;
use Classes\Foundation\FPersistentManager;
use Classes\Utilities\USession;
use Classes\View\VStudent;

require __DIR__.'/../../vendor/autoload.php';

class CReservation
{
    public static function showStudent(string $kind) {
        $session=USession::getInstance();
        $id=$session->getSessionElement('id');
        $PM= FPersistentManager::getInstance();
        $reservations=$PM->loadReservationsByStudent($id, $kind);
        $reservationsData = [];
        foreach ($reservations as $reservation) {
            $accommodation=$PM->load('EAccommodation', $reservation->getIdAccommodation());
            $period = $reservation->getFromDate()->format('d/m/Y') . ' - ' . $reservation->getToDate()->format('d/m/Y');
            if ($accommodation->getPhoto() === null) {
                $accommodationPhoto = "/UniRent/Smarty/images/NoPic.png";
            } else {
                $accommodationPhoto = (EPhoto::toBase64($accommodation->getPhoto()))[0]->getPhoto();
            }
            $reservationsData[] = [
                'idReservation' => $reservation->getId(),
                'title' => $accommodation->getTitle(),
                'photo' => $accommodationPhoto,
                'period' => $period,
                'price' => $accommodation->getPrice(),
                'address' => $accommodation->getAddress()
            ];
        }
        $view= new VStudent();
        $view->showReservations($reservationsData);

    }

}