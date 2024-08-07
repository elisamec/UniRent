<?php
namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Entity\ECreditCard;
use Classes\Entity\EPhoto;
use Classes\Foundation\FPersistentManager;
use Classes\Entity\EReview;
use Classes\Tools\TType;
use Classes\Utilities\USession;
use Classes\Utilities\USuperGlobalAccess;
use Classes\View\VStudent; 
use Classes\Control;
use DateTime;
use FCreditCard;
use Classes\View\VError;

class CReview {

    public static function delete(int $id) {
        $PM=FPersistentManager::getInstance();
        $res=$PM->delete('EReview', $id);
        if ($res) {
            header('Location:' . $_COOKIE['current_page'].'/success');
        } else {
            header('Location:' . $_COOKIE['current_page'].'/error');
        }
    }

    public static function edit(int $id) {
        $PM=FPersistentManager::getInstance();
        $review=$PM->load('EReview', $id);
        $review->setTitle(USuperGlobalAccess::getPost('title'));
        $review->setDescription(USuperGlobalAccess::getPost('content'));
        $review->setValutation(USuperGlobalAccess::getPost('rate'));
        $res=$PM->update($review);
        if ($res) {
            header('Location:' . $_COOKIE['current_page'].'/success');
        } else {
            header('Location:' . $_COOKIE['current_page'].'/error');
        }
    }
    public static function addReviewStudent(int $idStudent) {
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
            header('Location:' . $_COOKIE['current_page'].'/success');
        } else {
            header('Location:' . $_COOKIE['current_page'].'/error');
        }
    }
    public static function addReviewOwner(int $idOwner) {
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
            header('Location:' . $_COOKIE['current_page'].'/success');
        } else {
            header('Location:' . $_COOKIE['current_page'].'/error');
        }
    }
    public static function addReviewAccommodation(int $idAccommodation) {
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
            header('Location:' . $_COOKIE['current_page'].'/success');
        } else {
            header('Location:' . $_COOKIE['current_page'].'/error');
        }
    }

}