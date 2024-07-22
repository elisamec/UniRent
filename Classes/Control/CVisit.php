<?php 

namespace Classes\Control;

use Classes\Entity\EPhoto;
use Classes\Entity\EVisit;
use Classes\Foundation\FPersistentManager;
use Classes\Tools\TType;
use Classes\Utilities\USession;
use Classes\Utilities\USuperGlobalAccess;
use Classes\View\VOwner;
use Classes\View\VStudent;
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
   public static function visits() {
    $session = USession::getInstance();
    $id=$session->getSessionElement('id');
    $PM= FPersistentManager::getInstance();
    $userType=$PM->getUserType($id);
    if ($userType===TType::STUDENT) {
        $view= new VStudent();
    } elseif ($userType===TType::OWNER) {
        $view= new VOwner();
    } else {
        http_response_code(403);
        exit();
    }
    $visits=$PM->loadVisitSchedule($id, $userType);
    $visitsData = [];

    foreach ($visits as $visit) {
        $student = $PM->load('EStudent', $visit->getIdStudent());
        $accommodation = $PM->load('EAccommodation', $visit->getIdAccommodation());
        $profilePic = $student->getPhoto();
    
        if ($profilePic === null) {
            $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
        } else {
            $profilePic = (EPhoto::toBase64(array($profilePic)))[0]->getPhoto();
        }
    
        $date = $visit->getDate();
        $day = (int) $date->format('d');
        $month = (int) $date->format('m');
        $year = (int) $date->format('Y');
        $time = $date->format('H:i') . '-' . $date->modify('+' . $accommodation->getVisitDuration() . ' minutes')->format('H:i');
        
        // Create the event array
        $event = [
            'photo' => $profilePic,
            'username' => $student->getUsername(),
            'accommodationTitle' => $accommodation->getTitle(),
            'time' => $time,
            'idVisit' => $visit->getIdVisit()
        ];
        
        // Find the index of the existing date entry, if it exists
        $key = array_search(true, array_map(function($item) use ($day, $month, $year) {
            return $item['day'] === $day && $item['month'] === $month && $item['year'] === $year;
        }, $visitsData));
        
        // If an entry for the date exists, add the event to the existing entry
        if ($key !== false) {
            $visitsData[$key]['events'][] = $event;
        } else {
            // Otherwise, create a new entry for the date
            $visitsData[] = [
                'day' => $day,
                'month' => $month,
                'year' => $year,
                'events' => [$event]
            ];
        }
    }
    
    // Sort the events within each date by start time
    foreach ($visitsData as &$data) {
        usort($data['events'], function($a, $b) {
            $startTimeA = substr($a['time'], 0, 5); // Extract the start time (HH:MM)
            $startTimeB = substr($b['time'], 0, 5); // Extract the start time (HH:MM)
            return strcmp($startTimeA, $startTimeB);
        });
    }
    unset($data); // Break the reference
    
    // Sort the data by date
    usort($visitsData, function($a, $b) {
        $dateA = sprintf('%04d-%02d-%02d', $a['year'], $a['month'], $a['day']);
        $dateB = sprintf('%04d-%02d-%02d', $b['year'], $b['month'], $b['day']);
        return strcmp($dateA, $dateB);
    });
    
    $view->visits($visitsData);
   }
}