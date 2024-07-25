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
        foreach ($reservations as $reservation) {
            $formatted = self::formatDate($reservation->getMade());
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
                'expires' => $formatted,
            ];
        }
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

            foreach ($reservations as $reservation) {
                $formatted = self::formatDate($reservation->getMade());
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
                    'idReservation' => $reservation->getID(),
                    'username' => $student->getUsername(),
                    'image' => $profilePic,
                    'period' => 'from '. $reservation->getFromDate()->format('d/m/Y') . ' to ' . $reservation->getToDate()->format('d/m/Y'),
                    'expires' => $formatted
                ];
            }

            $reservationData[] = [
                'accommodation' => $accommodationTitle,
                'reservations' => $studentList
            ];
        }
        $view->showReservations($reservationData);
    }
    public static function reservationDetails(int $idReservation): void {
        $session = USession::getInstance();
        $userType = $session->getSessionElement('userType');
        $PM=FPersistentManager::getInstance();
        $reservation = $PM->load('EReservation', $idReservation);
        if ($userType==='Student') {
            $accommodation = $PM->load('EAccommodation', $reservation->getAccomodationId());
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
            if(is_null($owner_photo)){}
            else
            {
                $owner_photo_64=EPhoto::toBase64(array($owner_photo));
                $owner->setPhoto($owner_photo_64[0]);
            }
            $view= new VStudent();
            $view->reservationDetails($reservation, $accommodation, $owner, self::formatDate($reservation->getMade()), $picture);
        }
        else {
            $student = $PM->load('EStudent', $reservation->getIdStudent());
            $student_photo=$student->getPhoto();
            if(is_null($student_photo)){}
            else
            {
                $student_photo_64=EPhoto::toBase64(array($student_photo));
                $student->setPhoto($student_photo_64[0]);
            }
            $view = new VOwner();
            $view->reservationDetails($reservation, $student, self::formatDate($reservation->getMade()));
        }
    }
    private static function formatDate(DateTime $date): string {
        $today = new DateTime('now');
                // Calculate the difference
                $interval = $today->diff($date->modify('+2 days'));

                // Extract the components of the difference
                $days = $interval->days;
                $hours = $interval->h;
                $minutes = $interval->i;

                // Create the formatted string with singular/plural logic
                // Create the formatted string with singular/plural logic
                $parts = [];

                // Add days to the array if greater than 0
                if ($days > 0) {
                    $parts[] = $days . ' ' . ($days > 1 ? 'days' : 'day');
                }

                // Add hours to the array if greater than 0
                if ($hours > 0) {
                    $parts[] = $hours . ' ' . ($hours > 1 ? 'hours' : 'hour');
                }

                // Add minutes to the array if greater than 0
                if ($minutes > 0) {
                    $parts[] = $minutes . ' ' . ($minutes > 1 ? 'minutes' : 'minute');
                }

                // Handle the case where all values might be zero
                // Join the parts with a comma and 'and'
                if (count($parts) > 1) {
                    $formatted = implode(', ', array_slice($parts, 0, -1)) . ' and ' . end($parts);
                } elseif (count($parts) === 1) {
                    $formatted = $parts[0];
                } else {
                    $formatted = '0 minutes';
                }
                return $formatted;
    }
}