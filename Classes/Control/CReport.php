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
        $reportReason=USuperGlobalAccess::getPost('reportReason');
        $subject = $PM->load('E' . $type, $id);
        
        if ($type == 'Student' | $type == 'Owner')
        {
            $subject->setStatus('reported');
        } else if ($type == 'Review') {
            $subject->report();
        }
            $res=$PM->update($subject);
            $report= new EReport(null, $reportReason, new DateTime('today'), null, $id, TType::tryFrom(strtolower($type)));
            $res2=$PM->store($report);
            if ($res && $res2)
            {
                header('Location:/UniRent/' . USuperGlobalAccess::getCookie('current_page') . '/success');
            }
            else
            {
                header('Location:/UniRent/' . USuperGlobalAccess::getCookie('current_page') . '/error');
            }
    }
}