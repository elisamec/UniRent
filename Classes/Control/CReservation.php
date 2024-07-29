<?php 

namespace Classes\Control;

use Classes\Entity\EPhoto;
use Classes\Foundation\FPersistentManager;
use Classes\Tools\TStatusUser;
use Classes\Tools\TType;
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
            $formatted = self::formatDate($reservation->getMade()->setTime(0,0,0));
            $accommodation=$PM->load('EAccommodation', $reservation->getAccomodationId());
            $period = $reservation->getFromDate()->format('d/m/Y') . ' - ' . $reservation->getToDate()->format('d/m/Y');
            if ($accommodation->getPhoto() === []) {
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
    public static function showOwner(string $statusAccept="null", string $statusDeny="null"):void {
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
            $creditCardDataArray = $PM->loadStudentCards($reservation->getIdStudent());
            $creditCardData = [];
            foreach ($creditCardDataArray as $card) {
                $cardNumber='**** **** **** ' . substr($card->getNumber(), -4);
                $creditCardData[] = [
                    'cardNumber' => $cardNumber,
                    'cardName' => $card->getName(). ' ' . $card->getSurname(),
                    'main' => $card->getMain()
                ];
            }
            $view= new VStudent();
            $view->reservationDetails($reservation, $accommodation, $owner, self::formatDate($reservation->getMade()->setTime(0,0,0)), $picture, $reviewsData, $creditCardData);
        }
        else {
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
        }
    }
    private static function formatDate(DateTime $date): string {
        $today = new DateTime('today');
                // Calculate the difference
                $interval = $today->diff($date->modify('+2 days'));

                // Extract the components of the difference
                $days = $interval->days;
                // Add days to the array if greater than 0
                if ($days > 0) {
                    $formatted = $days . ' ' . ($days > 1 ? 'days' : 'day');
                } else {
                    $formatted = 'no days';
                }
                return $formatted;
    }
    public static function accept(int $id) {
        $PM = FPersistentManager::getInstance();
        $reservation = $PM::load('EReservation', $id);
        $reservation->setStatus(true);
        $reservation->setMade(new DateTime('now'));
        $res = $PM::update($reservation);
        if ($res) {
            header('Location:/UniRent/Reservation/showOwner/accepted');
        } else {
            header('Location:/UniRent/Reservation/showOwner/error');
        }
    }
    public static function deny(int $id) {
        $PM = FPersistentManager::getInstance();
        $res = $PM::delete('EReservation', $id); 
        if ($res) {
            header('Location:/UniRent/Reservation/showOwner/null/denied');
        } else {
            header('Location:/UniRent/Reservation/showOwner/null/error');
        }
    }

}