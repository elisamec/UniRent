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
                $replies[] = [
                    'id' => $reply->getId(),
                    'message' => $reply->getMessage(),
                    'supportReply' => $reply->getSupportReply(),
                    'topic' => $topic,
                    'statusRead' => $reply->getStatusRead()
                ];
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
                $replies[$count][] = [
                    'id' => $reply->getId(),
                    'message' => $reply->getMessage(),
                    'supportReply' => $reply->getSupportReply(),
                    'topic' => $topic,
                    'statusRead' => $reply->getStatusRead()
                ];
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
}
