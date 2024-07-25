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
            $today = new DateTime('now');
            // Calculate the difference
            $interval = $today->diff($reservation->getMade());

            // Extract the components of the difference
            $days = $interval->days;
            $hours = $interval->h;
            $minutes = $interval->i;

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
                $today = new DateTime('now');
                // Calculate the difference
                $interval = $today->diff($reservation->getMade());

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

}