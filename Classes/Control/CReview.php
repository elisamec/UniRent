<?php
namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Foundation\FPersistentManager;
use Classes\Entity\EReview;
use Classes\Tools\TType;
use Classes\Utilities\USession;
use Classes\Utilities\USuperGlobalAccess;
use DateTime;
use Classes\View\VError;
use Classes\Utilities\UFormat;
use Classes\Control\CStudent;
use Classes\View\VOwner;
use Classes\View\VStudent;

/**
 * This class is responsible for managing reviews.
 * 
 * @package Classes\Control
 */
class CReview {

    /**
     * Method delete
     * 
     * This method is used to delete a review
     * @param int $id
     * @return void
     */
    public static function delete(int $id) {
        $session = USession::getInstance();
        $userType=$session->getSessionElement('userType');
        if ( $userType === null) {
            $view= new VError();
            $view->error(403);
            exit();
        }
        $PM=FPersistentManager::getInstance();
        $review=$PM->load('EReview', $id);
        if ($review->getAuthorType() !== $userType) {
            $view= new VError();
            $view->error(403);
            exit();
        } else if ($review->getAuthorId() !== $session->getSessionElement('id')) {
            $view= new VError();
            $view->error(403);
            exit();
        }
        $res=$PM->delete('EReview', $id);
        if ($res) {
            header('Location:' . USuperGlobalAccess::getCookie('current_page').'/success');
        } else {
            header('Location:' . USuperGlobalAccess::getCookie('current_page').'/error');
        }
    }

    /**
     * Method edit
     * 
     * This method is used to edit a review
     * @param int $id
     * @return void
     */
    public static function edit(int $id) {
        $session = USession::getInstance();
        $PM=FPersistentManager::getInstance();
        $review=$PM->load('EReview', $id);
        if ($review->getAuthorType() !== $session->getSessionElement('userType')) {
            $view= new VError();
            $view->error(403);
            exit();
        } else if ($review->getAuthorId() !== $session->getSessionElement('id')) {
            $view= new VError();
            $view->error(403);
            exit();
        }
        $review->setTitle(USuperGlobalAccess::getPost('title'));
        $review->setDescription(USuperGlobalAccess::getPost('content'));
        $review->setValutation(USuperGlobalAccess::getPost('rate'));
        $res=$PM->update($review);
        if ($res) {
            header('Location:' . USuperGlobalAccess::getCookie('current_page').'/success');
        } else {
            header('Location:' . USuperGlobalAccess::getCookie('current_page').'/error');
        }
    }

    /**
     * Method addReviewStudent
     * 
     * This method is used to add a review to a student
     * @param int $idStudent
     * @return void
     */
    public static function addReviewStudent(int $idStudent) {
        $session = USession::getInstance();
        if ($session->getSessionElement('userType') === null) {
            $view= new VError();
            $view->error(403);
            exit();
        }
        $idAuthor=USession::getInstance()->getSessionElement('id');
        $PM=FPersistentManager::getInstance();
        $authorType = $PM->getUserType($idAuthor);
        $type=TType::STUDENT;
        $title=USuperGlobalAccess::getPost('title');
        $description=USuperGlobalAccess::getPost('content');
        $valutation=USuperGlobalAccess::getPost('rate');
        $date=new DateTime();
        $review=new EReview(null, $title, $valutation, $description, $type, $date, $authorType, $idAuthor, $idStudent);
        $res=$PM->store($review);
        if ($res) {
            header('Location:' . USuperGlobalAccess::getCookie('current_page').'/success');
        } else {
            header('Location:' . USuperGlobalAccess::getCookie('current_page').'/error');
        }
    }

    /**
     * Method addReviewOwner
     * 
     * This method is used to add a review to an owner
     * @param int $idOwner
     * @return void
     */
    public static function addReviewOwner(int $idOwner) {
        CStudent::checkIfStudent();
        $author=USession::getInstance()->getSessionElement('username');
        $PM=FPersistentManager::getInstance();
        $idAuthor = $PM->getStudentIdByUsername($author);
        $authorType=TType::STUDENT;
        $type=TType::OWNER;
        $title=USuperGlobalAccess::getPost('title');
        $description=USuperGlobalAccess::getPost('content');
        $valutation=USuperGlobalAccess::getPost('rate');
        $date=new DateTime();
        $review=new EReview(null, $title, $valutation, $description, $type, $date, $authorType, $idAuthor, $idOwner);
        $res=$PM->store($review);
        if ($res) {
            header('Location:' . USuperGlobalAccess::getCookie('current_page').'/success');
        } else {
            header('Location:' . USuperGlobalAccess::getCookie('current_page').'/error');
        }
    }

    /**
     * Method addReviewAccommodation
     * 
     * This method is used to add a review to an accommodation
     * @param int $idAccommodation
     * @return void
     */
    public static function addReviewAccommodation(int $idAccommodation) {
        CStudent::checkIfStudent();
        $PM=FPersistentManager::getInstance();
        $session=USession::getInstance();
        $idAuthor = $session->getSessionElement('id');
        $authorType=TType::STUDENT;
        $type=TType::ACCOMMODATION;
        $title=USuperGlobalAccess::getPost('title');
        $description=USuperGlobalAccess::getPost('content');
        $valutation=USuperGlobalAccess::getPost('rate');
        $date=new DateTime();
        $review=new EReview(null, $title, $valutation, $description, $type, $date, $authorType, $idAuthor, $idAccommodation);
        $res=$PM->store($review);
        if ($res) {
            header('Location:' . USuperGlobalAccess::getCookie('current_page').'/success');
        } else {
            header('Location:' . USuperGlobalAccess::getCookie('current_page').'/error');
        }
    }
    /**
     * Method getProfileReviews
     * 
     * This method is used to get the reviews of a user
     * @param int $userId
     * @param \Classes\Tools\TType|string $userType
     * @return array[]
     */
    public static function getProfileReviews(int $userId, TType | string $userType) {
        $PM=FPersistentManager::getInstance();
        $session = USession::getInstance();
        $userType = is_string($userType) ? TType::tryFrom(strtolower($userType)) : $userType;
        $reviews = $PM->loadByRecipient($userId, $userType);
        $reviewsData = [];
        foreach ($reviews as $review) {
            $author = $PM->load( 'E' . ucfirst($review->getAuthorType()->value), $review->getIdAuthor());
            $isAdmin = $session->getSessionElement('userType') === 'Admin';
            if ($review->isBanned() &&  !$isAdmin) {
                continue;
            }
            $reviewsData[] = $isAdmin ? UFormat::reviewsFormatAdmin($author, $review) : UFormat::reviewsFormatUser($author, $review);
        }
        return $reviewsData;
    }
     /**
      * Method reviews
        *
        * This method is used to show the reviews of the user
      * @param mixed $modalSuccess
      * @return void
      */
     public static function reviews(?string $modalSuccess=null) :void {
        $session = USession::getInstance();
        if($session->getSessionElement('userType') === 'Owner') {
           $view = new VOwner();
           $type = TType::OWNER;
        } else if ($session->getSessionElement('userType') === 'Student') {
            $view = new VStudent();
            $type = TType::STUDENT;
        } else {
            $view = new VError();
            $view->error(403);
            exit();
        }
        $id=$session->getSessionElement('id');
        $reviewsData = CReview::getProfileReviews($id, $type);
        $view->reviews($reviewsData, $modalSuccess);
    }

    public static function postedReview(?string $modalSuccess=null) {
        $session = USession::getInstance();
        if($session->getSessionElement('userType') === 'Owner') {
           $view = new VOwner();
           $type = TType::OWNER;
        } else if ($session->getSessionElement('userType') === 'Student') {
            $view = new VStudent();
            $type = TType::STUDENT;
        } else {
            $view = new VError();
            $view->error(403);
            exit();
        }
        $authorId=$session->getSessionElement('id');
        $PM=FPersistentManager::getInstance();
        $reviews = $PM->loadReviewsByAuthor($authorId, $type);
        $reviewsData = [];
        foreach ($reviews as $review) {
            $recipient = $PM->load( 'E' . ucfirst($review->getRecipientType()->value), $review->getIdRecipient());
            if ($review->isBanned()) {
                continue;
            }
            $reviewsData[] = UFormat::reviewsFormatUserPosted($recipient, $review);
        }
        $view->postedReview($reviewsData, $modalSuccess);
    }
}