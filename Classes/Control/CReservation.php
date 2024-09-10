<?php 

namespace Classes\Control;

use Classes\Entity\EPhoto;
use Classes\Foundation\FPersistentManager;
use Classes\Tools\TStatusUser;
use Classes\Tools\TType;
use Classes\Utilities\UCookie;
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
 * This class is responsible for managing reservations.
 * 
 * @package Classes\Control
 */
class CReservation
{
    /**
     * Method showStudent
     * 
     * This function is used to show the reservations of a student
     * 
     * @param string $kind
     * @param string|null $modalSuccess
     * @return void
     */
    public static function showStudent(string $kind, ?string $modalSuccess=null): void {
        CStudent::checkIfStudent();
        $session=USession::getInstance();
        $id=$session->getSessionElement('id');
        $PM= FPersistentManager::getInstance();
        $reservations=$PM->loadReservationsByStudent($id, $kind);
        $reservationsData = [];
        foreach ($reservations as $reservation) {
            $accommodation=$PM->load('EAccommodation', $reservation->getAccomodationId());
            $reservationsData[] = UFormat::formatReservationsStudent($reservation, $accommodation);
        }
        $view= new VStudent();
        $view->showReservations($reservationsData, $kind, $modalSuccess);

    }

    /**
     * Method showOwner
     * 
     * This function is used to show the reservations of an owner
     * 
     * @param string|null $modalSuccess
     * @return void
     */
    public static function showOwner(?string $modalSuccess=null):void {
        COwner::checkIfOwner();
        $session=USession::getInstance();
        $id=$session->getSessionElement('id');
        $PM=FPersistentManager::getInstance();
        $reservationsArray=$PM->getWaitingReservations($id);
        $view = new VOwner();
        $reservationData=[];
        foreach ($reservationsArray as $idAccommodation => $reservations) {
            $reservations = USort::sortArray($reservations, 'reservation');
            $accommodationTitle = $PM->getTitleAccommodationById($idAccommodation);
            $studentList = [];

            foreach ($reservations as $reservation) {
                $student=$PM->load('EStudent', $reservation->getIdStudent());
                UFormat::photoFormatUser($student);
                $studentList[] = UFormat::formatReservationsOwner($student, $reservation);
            }

            $reservationData[] = [
                'accommodation' => $accommodationTitle,
                'reservations' => $studentList
            ];
        }
        $view->showReservations($reservationData, $modalSuccess);
    }

    /**
     * Method reservationDetails
     * 
     * This function is used to show the details of a reservation
     * 
     * @param int $idReservation
     * @param string|null $modalSuccess 
     * @return void
     */
    public static function reservationDetails(int $idReservation, ?string $modalSuccess=null): void {
        $session = USession::getInstance();
        $userType = $session->getSessionElement('userType');
        if (!$userType) {
            $view= new VError();
            $view->error(403);
            exit();
        }
        $PM=FPersistentManager::getInstance();
        $reservation = $PM->load('EReservation', $idReservation);
        if ($userType==='Student') {
            if ($reservation->getIdStudent() !== $session->getSessionElement('id')) {
                $view= new VError();
                $view->error(403);
                exit();
            }
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
            UFormat::photoFormatUser($owner);
            $reviews = $PM->loadByRecipient($accommodation->getIdAccommodation(), TType::ACCOMMODATION);
            $reviewsData = [];
            foreach ($reviews as $review) {
                $author = $PM->load('EStudent', $review->getIdAuthor());
                $reviewsData[] = UFormat::reviewsFormatUser($author, $review);
            }
            $creditCardDataArray = $PM->loadStudentCards($reservation->getIdStudent());
            $creditCardData = UFormat::formatCreditCardReserve($creditCardDataArray);
            $view= new VStudent();
            $view->reservationDetails($reservation, $accommodation, $owner, UFormat::formatDate($reservation->getMade()->setTime(0,0,0)), $picture, $reviewsData, $creditCardData, $modalSuccess);
        }
        else {
            $accommodationOwner =$PM->load('EAccommodation', $reservation->getAccomodationId())->getIdOwner();
            if ($accommodationOwner !== $session->getSessionElement('id')) {
                $view= new VError();
                $view->error(403);
                exit();
            }
            $student = $PM->load('EStudent', $reservation->getIdStudent());
            UFormat::photoFormatUser($student);
            $reviews = $PM->loadByRecipient($student->getId(), TType::STUDENT);
            $reviewsData = [];
            foreach ($reviews as $review) {
                $author = $PM->load('EStudent', $review->getIdAuthor());
                $reviewsData[] = UFormat::reviewsFormatUser($author, $review);
            }
            $view = new VOwner();
            $view->reservationDetails($reservation, $student, UFormat::formatDate($reservation->getMade()->setTime(0,0,0)), $reviewsData, $modalSuccess);
        }
    }

    /**
     * Method accept
     * 
     * This function is used to accept a reservation
     * 
     * @param int $id
     * @return void
     */
    public static function accept(int $id) :void {
        COwner::checkIfOwner();
        $PM = FPersistentManager::getInstance();
        $reservation = $PM->load('EReservation', $id);
        $accommodationOwner = $PM->load('EAccommodation', $reservation->getAccomodationId())->getIdOwner();
        if ($accommodationOwner !== USession::getInstance()->getSessionElement('id')) {
            $view= new VError();
            $view->error(403);
            exit();
        }
        $reservation->setStatus(true);
        $reservation->setMade(new DateTime('now'));
        $res = $PM->update($reservation);
        if ($res) {
            header('Location:/UniRent/Reservation/showOwner/success');
        } else {
            header('Location:/UniRent/Reservation/showOwner/error');
        }
    }

    /**
     * Method deny
     * 
     * This function is used to deny a reservation
     * 
     * @param int $id
     * @return void
     */
    public static function deny(int $id) :void{
        COwner::checkIfOwner();
        $PM = FPersistentManager::getInstance();
        $reservation = $PM->load('EReservation', $id);
        $accommodationOwner = $PM->load('EAccommodation', $reservation->getAccomodationId())->getIdOwner();
        if ($accommodationOwner !== USession::getInstance()->getSessionElement('id')) {
            $view= new VError();
            $view->error(403);
            exit();
        }
        $res = $PM->delete('EReservation', $id); 
        if ($res) {
            header('Location:/UniRent/Reservation/showOwner/success');
        } else {
            header('Location:/UniRent/Reservation/showOwner/error');
        }
    }

    /**
     * Method delete
     * 
     * This function is used to delete a reservation
     * @param int $id
     * @return void
     */
    public static function delete(int $id) :void {
        CStudent::checkIfStudent();
        $PM = FPersistentManager::getInstance();
        $reservation = $PM->load('EReservation', $id);
        $student = $PM->load('EStudent', $reservation->getIdStudent());
        if ($student->getId() !== USession::getInstance()->getSessionElement('id')) {
            $view= new VError();
            $view->error(403);
            exit();
        }
        $res = $PM->delete('EReservation', $id);
        if ($res) {
            header('Location:'+ USuperGlobalAccess::getCookie('current_page')+'success');
        } else {
            header('Location:'+ USuperGlobalAccess::getCookie('current_page')+'/error');
        }
    }

}