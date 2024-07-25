<?php 

namespace Classes\Control;

use Classes\Entity\EPhoto;
use Classes\Foundation\FPersistentManager;
use Classes\Utilities\USession;
use Classes\View\VOwner;
use Classes\View\VStudent;
use Classes\View\VError;
use DateTime;

require __DIR__.'/../../vendor/autoload.php';

class CReservation
{
    public static function showStudent(string $kind) {
        $session=USession::getInstance();
        $id=$session->getSessionElement('id');
        $PM= FPersistentManager::getInstance();
        $reservations=$PM->loadReservationsByStudent($id, $kind);
        $reservationsData = [];
        $today = new DateTime('today');
        foreach ($reservations as $reservation) {
            $accommodation=$PM->load('EAccommodation', $reservation->getAccomodationId());
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
                'address' => $accommodation->getAddress()->getAddressLine1() . ', ' . $accommodation->getAddress()->getLocality(),
                'expires' => $today->diff($reservation->getMade())->format('%a days %h hours %i minutes'),
            ];
        }
        print $reservation->getMade()->format('Y-m-d H:i:s') . '-' . $today->format('Y-m-d H:i:s');
        $view= new VStudent();
        $view->showReservations($reservationsData, $kind);

    }
    public static function showOwner():void {
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
            $today = new DateTime('today');
            foreach ($reservations as $reservation) {
                $student=$PM->load('EStudent', $reservation->getIdStudent());
                $student_photo=$student->getPhoto();
                if(is_null($student_photo)){}
                else
                {
                    $student_photo_64=EPhoto::toBase64(array($student_photo));
                    $student->setPhoto($student_photo_64[0]);
                    #print_r($owner);
                }
                $profilePic = $student->getPhoto() === null ? "/UniRent/Smarty/images/ImageIcon.png" : $student->getPhoto()->getPhoto();
                $studentList[] = [
                    'username' => $student->getUsername(),
                    'image' => $profilePic,
                    'period' => 'from '. $reservation->getFromDate()->format('d/m/Y') . ' to ' . $reservation->getToDate()->format('d/m/Y'),
                    'expires' => $today->diff($reservation->getMade())->format('%a days %h hours %i minutes')
                ];
            }

            $reservationData[] = [
                'accommodation' => $accommodationTitle,
                'reservations' => $studentList
            ];
        }
        $view->showReservations($reservationData);
    }

}