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

class CReview {

    public static function delete(int $id) {
        $PM=FPersistentManager::getInstance();
        $PM->delete('EReview', $id);
        header('Refresh: 0');
    }

    public static function edit(int $id) {
        $PM=FPersistentManager::getInstance();
        $review=$PM->load('EReview', $id);
        $review->setTitle(USuperGlobalAccess::getPost('title'));
        $review->setDescription(USuperGlobalAccess::getPost('content'));
        $review->setValutation(USuperGlobalAccess::getPost('rate'));
        $PM->update($review);
        header('Refresh: 0');
    }
}