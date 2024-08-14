<?php 

namespace Classes\Control;

use Classes\Foundation\FPersistentManager;
use Classes\Utilities\USuperGlobalAccess;
use Classes\Utilities\USession;
use Classes\Entity\EReport;
use Classes\Tools\TType;
use Classes\View\VError;
use DateTime;

require __DIR__.'/../../vendor/autoload.php';

class CReport
{
    /**
     * Method report
     * 
     * this method permits the users to report a Review, a Student or an Owner
     *
     * @param int $id [Student/Owner/Review id]
     * @param string $type [Student/Owner/Review]
     *
     * @return void
     */
    public static function makeReport(int $id, string $type):void
    {
        $PM = FPersistentManager::getInstance();
        $session = USession::getInstance();
        $userType  = $session->getSessionElement('userType');
        $reportReason=USuperGlobalAccess::getPost('reportReason');
        $subject = $PM->load('E' . $type, $id);
        
        if ($type == 'Student' | $type == 'Owner')
        {
            $subject->setStatus('reported');
            $location = ucfirst($userType) . '/publicProfile/' . $subject->getUsername();
        } else if ($type == 'Review') {
            $subject->report();
            $location = USuperGlobalAccess::getCookie('current_page');
        }
            $res=$PM->update($subject);
            $report= new EReport(null, $reportReason, new DateTime('today'), null, $id, TType::tryFrom(strtolower($type)));
            $res2=$PM->store($report);
            if ($res && $res2)
            {
                header('Location:/UniRent/' . $location . '/success');
            }
            else
            {
                header('Location:/UniRent/' . $location . '/error');
            }
    }
}