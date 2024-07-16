<?php 

namespace Classes\Control;

use Classes\Entity\EVisit;
use Classes\Foundation\FPersistentManager;
use Classes\Utilities\USession;
use Classes\Utilities\USuperGlobalAccess;
use DateTime;

require __DIR__.'/../../vendor/autoload.php';

class CVisit
{
   public static function studentRequest(int $idAccommodation) {
    $session = USession::getInstance();
    $idStudent=$session->getSessionElement('id');
    $day=USuperGlobalAccess::getPost('day');
    $time=USuperGlobalAccess::getPost('time');
    $time=explode(":", $time);
    $hour=$time[0];
    $minutes=$time[1];
    $date=new DateTime();
    $date->modify('next '.$day);
    $date->setTime($hour, $minutes);
    $visit= new EVisit(null, $date, $idStudent, $idAccommodation);
    $PM= FPersistentManager::getInstance();
    $res=$PM->store($visit);
    if ($res) {
        header('Location:' . $_SERVER['HTTP_REFERER']);
        } else {
            http_response_code(500);
        }
   }
}