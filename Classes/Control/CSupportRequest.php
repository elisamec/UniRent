<?php
namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';
use Classes\Foundation\FPersistentManager;
use Classes\Tools\TRequestType;
use Classes\Utilities\USession;
use Classes\View\VError;
use Classes\View\VOwner;
use Classes\View\VStudent;
use Classes\Utilities\USuperGlobalAccess;
use Classes\Entity\ESupportRequest;
use Classes\Tools\TType;
use Exception;

class CSupportRequest
{
    /**
     * Method getSupportReply
     * 
     * This method returns the support replies of the administrator
     *
     * @return void
     */
    public static function getSupportReply(): void
    {
        // Set the content type to JSON
        header('Content-Type: application/json');

        try {
            // Get the persistent manager instance and session
            $PM = FPersistentManager::getInstance();
            $session = USession::getInstance();
            // Fetch replies
            $result = $PM->getSupportReply($session->getSessionElement('id'), $session->getSessionElement('userType'));
            
            // Prepare response data
            $countReply = 0;
            $replies = [];
            
            foreach ($result as $reply) {
                if ($reply->getStatusRead() === false) {
                    $countReply++;
                }
                $replies[]=self::formatReply($reply);
            }
            
            // Prepare and output the JSON response
            $response = [
                'countReply' => $countReply,
                'replies' => $replies
            ];
            
            echo json_encode($response);
        } catch (Exception $e) {
            // Handle errors and output a JSON error message
            echo json_encode(['error' => 'An error occurred while fetching support replies.']);
        }
    }
    private static function formatReply(ESupportRequest $reply): array {
        switch ($reply->getTopic()) {
            case TRequestType::REGISTRATION:
                $topic = 'Registration';
                break;
            case TRequestType::USAGE:
                $topic = 'App Usage';
                break;
            case TRequestType::BUG:
                $topic = 'Bug';
                break;
            case TRequestType::REMOVEBAN:
                $topic = 'Remove Ban Request';
                break;
            default:
                $topic = 'Other';
                break;
        }
        $reply = [
            'id' => $reply->getId(),
            'message' => $reply->getMessage(),
            'supportReply' => $reply->getSupportReply(),
            'topic' => $topic,
            'statusRead' => $reply->getStatusRead()
        ];
        return $reply;
    }
    public static function readSupportReply(int |string $id)
    {
        $session = USession::getInstance();
        $userType = $session->getSessionElement('userType');
        if ($userType === null) {
            $viewError = new VError();
            $viewError->error(403);
            exit();
        }
        $PM=FPersistentManager::getInstance();
        $result=$PM->readSupportReply((int)$id);
        $location=USuperGlobalAccess::getCookie('current_page');
        if($result)
        {
            header('Location:'.$location);
        }
        else
        {
            $view=new VError();
            $view->error(500);
        }
    }
    public static function readMoreSupportReplies() {
        $PM = FPersistentManager::getInstance();
            $session = USession::getInstance();
            $userType = $session->getSessionElement('userType');
            if ($userType === null) {
                $view = new VError();
                $view->error(403);
                exit();
            }
            // Fetch replies
            $result = $PM->getSupportReply($session->getSessionElement('id'), $userType);
            
            // Prepare response data
            $count = 1;
            $replies = [];
            
            foreach ($result as $reply) {
                if (array_key_exists($count, $replies)) {
                    if (count($replies[$count])==10) {
                        $count++;
                    }
                }
                $replies[$count][] = self::formatReply($reply);
            }
            if ($userType == 'Student') {
                $view = new VStudent();
            } else if ($userType == 'Owner') {
                $view = new VOwner();
            } else {
                $view = new VError();
                $view->error(403);
            }
            $view->supportReplies($replies, $count);
    }
    /**
     * Method supportRequest
     * 
     * this method permits the users to send a support request
     *
     * @return void
     */
    public static function supportRequest():void
    {
        $session=USession::getInstance();
        if (!$session->isSetSessionElement('id'))
        {
            $topic=TRequestType::BUG;
            $idUser=null;
            $type=null;
            $location="/User/contact";
        } else {
            $topic=USuperGlobalAccess::getPost('Subject');
            $idUser=$session->getSessionElement('id');
            $type=TType::tryFrom(strtolower($session->getSessionElement('userType')));
            $location="/".ucfirst($type->value)."/contact";
        }
        $message=USuperGlobalAccess::getPost('Message');
        $supportRequest=new ESupportRequest(null,$message,$topic,$idUser,$type);
        $PM=FPersistentManager::getInstance();
        $res=$PM->store($supportRequest);
        $res ? header('Location:'.$location.'/sent') : header('Location:'.$location.'/fail');
    }
    /**
     * Method studentEmailIssue
     * 
     * this method permits the administrator to fix the registration problem of a student caused by the absence of the email in the JSON file
     *
     *
     * @return void
     */
    public static function studentEmailIssue():void {

        $mail = USuperGlobalAccess::getPost('emailIssue');
        $university = USuperGlobalAccess::getPost('university');
        $city = USuperGlobalAccess::getPost('city');
        $supportRequest= new ESupportRequest(null, 'A student is trying to register with the following email, which is not accepted by the system: '. $mail. '. This is the university: '. $university. ' of this city: '.$city, TRequestType::REGISTRATION, null, null);
        $PM=FPersistentManager::getInstance();
        $res=$PM->store($supportRequest);
        $res ? header('Location:/UniRent/User/showRegistration/success') : header('Location:/UniRent/User/showRegistration/error');
    }
    /**
     * Method removeBan
     * 
     * this method permits the Student to send a request to the administrator to remove the ban
     *
     * @param string $username [Student/Owner username]
     *
     * @return void
     */
    public static function removeBan(string $username):void {

        $PM=FPersistentManager::getInstance();
        $topic=TRequestType::REMOVEBAN;
        $user= $PM->verifyUserUsername($username);
        $message=USuperGlobalAccess::getPost('Message');
        $supportRequest=new ESupportRequest(null,$message, $topic, $user['id'], $user['type']);
        $res=$PM->store($supportRequest);
        $modalSuccess = $res ? 'success' : 'error';
        $view=new VError();
        $view->error(600, $username, $modalSuccess);
    }
}
