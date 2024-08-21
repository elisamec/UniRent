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
use Classes\Entity\EAccommodation;
use Classes\Entity\EOwner;
use Classes\Entity\EStudent;

class CReview {

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
            $author = $PM->load( 'E' . $review->getAuthorType()->value, $review->getIdAuthor());
            $isAdmin = $session->getSessionElement('userType') === 'Admin';
            if ($review->isBanned() &&  !$isAdmin) {
                continue;
            }
            $reviewsData[] = $isAdmin ? UFormat::reviewsFormatAdmin($author, $review) : UFormat::reviewsFormatUser($author, $review);
        }
        return $reviewsData;
    }


}