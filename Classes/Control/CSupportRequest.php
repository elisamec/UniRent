<?php
namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';
use Classes\Foundation\FPersistentManager;
use Classes\Tools\TRequestType;
use Classes\Utilities\USession;
use Classes\View\VError;
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
            $result = $PM->getSupportReply($session::getSessionElement('id'), $session::getSessionElement('userType'));
            
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
        $PM=FPersistentManager::getInstance();
        $result=$PM->readSupportReply((int)$id);
        $location=$_COOKIE['current_page'];
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
}
