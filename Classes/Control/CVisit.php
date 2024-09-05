<?php 

namespace Classes\Control;

use Classes\Entity\EPhoto;
use Classes\Entity\EVisit;
use Classes\Foundation\FPersistentManager;
use Classes\Tools\TType;
use Classes\Utilities\UFormat;
use Classes\Utilities\USession;
use Classes\Utilities\USort;
use Classes\Utilities\USuperGlobalAccess;
use Classes\View\VOwner;
use Classes\View\VStudent;
use DateTime;
use Classes\View\VError;

require __DIR__.'/../../vendor/autoload.php';

/**
 * This class is responsible for managing visits.
 * 
 * @package Classes\Control
 */
class CVisit
{
    /**
     * Method studentRequest
     * 
     * This method is used to create a visit request
     *
     * @param int $idAccommodation
     *
     * @return void
     */
   public static function studentRequest(int $idAccommodation) {
        $session = USession::getInstance();
        CStudent::checkIfStudent();
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
        $success = $res ?  '/true':'/false';
        header('Location:' . USuperGlobalAccess::getCookie('current_page'). $success);
   }


/**
 * Retrieves the visits.
 *
 * @return void
 */
   public static function visits() :void{
        $session = USession::getInstance();
        $id=$session->getSessionElement('id');
        $PM= FPersistentManager::getInstance();
        $userType=$session->getSessionElement('userType');
        if ($userType === null) {
            $viewError= new VError();
            $viewError->error(403);
            exit();
        } else {
            $userType = TType::tryFrom(strtolower($userType));
        }
        $view = $userType === TType::STUDENT ? new VStudent() : new VOwner();
        $visits=$PM->loadVisitSchedule($id, $userType);
        $visitsData = [];
        foreach ($visits as $visit) {
            $student = $PM->load('EStudent', $visit->getIdStudent());
            $accommodation = $PM->load('EAccommodation', $visit->getIdAccommodation());
            UFormat::photoFormatUser($student);
            $profilePic = $student->getPhoto();
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
            $data['events'] = USort::sortArray($data['events'], 'visitByStartTime');
        }
        unset($data); // Break the reference
        
        // Sort the data by date
        $visitsData = USort::sortArray($visitsData, 'visitByDate');
        
        $view->visits($visitsData);
    }

    /**
     * Method viewVisit
     * 
     * This method is used to view a visit
     * @param int $id
     * @param string $successEdit
     * @param string $successDelete
     * @return void
     */
    public static function viewVisit(int $id, string $successEdit="null", string $successDelete="null") :void {
        $session = USession::getInstance();
        $userType=$session->getSessionElement('userType');
        $PM= FPersistentManager::getInstance();
        $visit=$PM->load('EVisit', $id);
        $accommodation = $PM->load('EAccommodation', $visit->getIdAccommodation());
        if ($userType=='Student') {
            $view= new VStudent();
            $idUser=$accommodation->getIdOwner();
            $visitUserType='Owner';
        } elseif ($userType=='Owner') {
            $view= new VOwner();
            $idUser=$visit->getIdStudent();
            $visitUserType='Student';
        } else {
            $viewError= new VError();
                $viewError->error(403);
            exit();
        }
        $PM= FPersistentManager::getInstance();
        $visit=$PM->load('EVisit', $id);
        $user = $PM->load('E'.$visitUserType, $idUser);
        if (count($accommodation->getPhoto()) ==0) {
            $accommodationPhoto = "/UniRent/Smarty/images/NoPic.png";
        } else {
            $accommodationPhoto = (EPhoto::toBase64($accommodation->getPhoto()))[0]->getPhoto();
        }
        $visits=$accommodation->getVisit();
            $booked=$PM->loadVisitsByWeek();
            foreach ($visits as $day=>$time) {
                foreach ($time as $key=>$t) {
                    foreach ($booked as $b) {
                        if($b->getDayOfWeek()==$day && $b->getDate()->format('H:i')==$t && $b->getIdVisit()!==$id)
                        {
                            unset($visits[$day][$key]);
                        }
                    }
                }
            }
        if ($userType=='Student') {
            $view->viewVisit($visit, $user, $accommodation, $accommodationPhoto, $visits, $successEdit, $successDelete);
        } else if ($userType=='Owner') {
            $view->viewVisitOwner($visit, $user, $accommodation, $accommodationPhoto, $successEdit, $successDelete);
        }
   }

   
/**
 * Deletes a visit record from the database.
 *
 * @param int $id The ID of the visit to delete.
 * @return void
 */
   public static function delete(int $id) :void{
        $session=USession::getInstance();
        $userType=$session->getSessionElement('userType');
        if ($userType === null) {
            $viewError= new VError();
            $viewError->error(403);
            exit();
        }
        $PM= FPersistentManager::getInstance();
        $visit=$PM->load('EVisit', $id);
        if ($userType=='Student') {
            $idUser=$visit->getIdStudent();
        } elseif ($userType=='Owner') {
            $idUser=$PM->load('EAccommodation',$visit->getIdAccommodation())->getIdOwner();
        }
        if ($idUser!==$session->getSessionElement('id')) {
            $viewError= new VError();
            $viewError->error(403);
            exit();
        }
        $res=$PM->delete('EVisit', $id);
        if ($res) {
            header('Location:/UniRent/' . $userType. '/home');
        } else {
            header('Location:' . $_SERVER['HTTP_REFERER']. '/null/false');
        }
   }

   /**
    * Method edit
    * 
    * This method is used to edit a visit
    * @param int $id
    * @return void
    */
   public static function edit(int $id):void {
        $session=USession::getInstance();
        $userType=$session->getSessionElement('userType');
        if ($userType !== 'Student') {
            $viewError= new VError();
            $viewError->error(403);
            exit();
        }
        $day=USuperGlobalAccess::getPost('day');
        $time=USuperGlobalAccess::getPost('time');
        $time=explode(":", $time);
        $hour=$time[0];
        $minutes=$time[1];
        $date=new DateTime();
        $date->modify('next '.$day);
        $date->setTime($hour, $minutes);
        $PM= FPersistentManager::getInstance();
        $visit=$PM->load('EVisit', $id);
        if ($userType=='Student') {
            $idUser=$visit->getIdStudent();
        } elseif ($userType=='Owner') {
            $idUser=$PM->load('EAccommodation',$visit->getIdAccommodation())->getIdOwner();
        }
        if ($idUser!==$session->getSessionElement('id')) {
            $viewError= new VError();
            $viewError->error(403);
            exit();
        }
        $visit->setDate($date);
        $res=$PM->update($visit);
        if ($res) {
            header('Location:' . $_SERVER['HTTP_REFERER']. '/true');
        } else {
            header('Location:' . $_SERVER['HTTP_REFERER']. '/false');
        }
    }
}