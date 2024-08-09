<?php

namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Entity\EReport;
use Classes\Entity\EStudent;
use Classes\Entity\ESupportRequest;
use Classes\Foundation\FPersistentManager;
use Classes\Tools\TRequestType;
use Classes\Tools\TStatusUser;
use Classes\Tools\TType;
use Classes\Utilities\USession;
use Classes\View\VAdmin;
use Classes\Utilities\USuperGlobalAccess;
use Classes\Utilities\UCookie;
use Classes\View\VError;
use Classes\Utilities\UAccessUniversityFile;
use DateTime;
use Classes\Entity\EPhoto;
use Classes\Tools\TStatusSupport;

class CAdmin
{
    public static function home(?string $modalMessage = null){
        self::checkIfAdmin();
        $PM=FPersistentManager::getInstance();
        $stats=$PM->getStatistics();
        $banned=$PM->getBannedList();
        $view = new VAdmin();
        $view->home($stats, $banned, $modalMessage);
    }

    public static function login(){
        if(UCookie::isSet('PHPSESSID'))
        {
            setcookie('PHPSESSID','',time()-3600);
            $session = USession::getInstance();
        }
        $view = new VAdmin();
        $view->login();
    }
    public static function checkLogin()
    {

        $view = new VAdmin();
                $passwordIn=USuperGlobalAccess::getPost('password');

                if(password_verify($passwordIn, '$2y$10$oqDyOSQOyj8bBbbeq1UFfe5B.zB/HGenmr9IRQnzGSBI5eRrHRF5i'))
                {
                   
                    $session = USession::getInstance();
                    $session->setSessionElement("userType", 'Admin');
                    $session->setSessionElement('password',$passwordIn);
                    header('Location:/UniRent/Admin/home'); 
                }
                else  #password is not correct
                {
                    $view->loginError();
                }
    }
    public static function logout()
    {
        $session = USession::getInstance();
        $session->destroySession();
        header('Location:/UniRent/User/home');
    }
    public static function report(int $id, string $type)
    {
        $PM = FPersistentManager::getInstance();
        $session = USession::getInstance();
        $userType  = $session->getSessionElement('userType');
        $reportReason=USuperGlobalAccess::getPost('reportReason');
        
        if ($type == 'Student')
        {
            $student = $PM->load('EStudent', $id);
            $student->setStatus('reported');
            $res=$PM->update($student);
            $report= new EReport(null, $reportReason, new DateTime('today'), null, $id, TType::STUDENT);
            $res2=$PM->store($report);
            if ($res && $res2)
            {
                header('Location:/UniRent/' . ucfirst($userType) . '/publicProfile/' . $student->getUsername() . '/success');
            }
            else
            {
                header('Location:/UniRent/' . ucfirst($userType) . '/publicProfile/' . $student->getUsername() . '/error');
            }
        }
        else if ($type == 'Owner')
        {
            $owner = $PM->load('EOwner', $id);
            $owner->setStatus('reported');
            $res=$PM->update($owner);
            $report= new EReport(null, $reportReason, new DateTime('today'), null, $id, TType::OWNER);
            $res2=$PM->store($report);
            if ($res && $res2)
            {
                header('Location:/UniRent/' . ucfirst($userType) . '/publicProfile/' . $owner->getUsername() . '/success');
            }
            else
            {
                header('Location:/UniRent/' . ucfirst($userType) . '/publicProfile/' . $owner->getUsername() . '/error');
            }
        } else if ($type == 'Review') {
            $review = $PM->load('EReview', $id);
            $review->report();
            $res=$PM->update($review);
            $report= new EReport(null, $reportReason, new DateTime('today'), null, $id, TType::REVIEW);
            $res2=$PM->store($report);
            if ($res && $res2)
            {
                header('Location:/UniRent/' .$_COOKIE['current_page'].'/success');
            }
            else
            {
                header('Location:/UniRent/' . $_COOKIE['current_page'] .'/error');
            }
        }
    }

    public static function supportRequest()
    {
        $session=USession::getInstance();
        if (!$session->isSetSessionElement('id'))
        {
            $topic=TRequestType::BUG;
            $message=USuperGlobalAccess::getPost('Message');
            $supportRequest=new ESupportRequest(0,$message,$topic,null,null);
            $PM=FPersistentManager::getInstance();
            $res=$PM->store($supportRequest);
            if ($res)
            {
                header('Location:/UniRent/User/contact/sent');
            }
            else
            {
                header('Location:/UniRent/User/contact/fail');
            }
        } else {
            $idUser=$session->getSessionElement('id');
            $type=$session->getSessionElement('userType');
            if ($type=='Student')
            {
                $type=TType::STUDENT;
            }
            else
            {
                $type=TType::OWNER;
            }
            $topic=USuperGlobalAccess::getPost('Subject');
            $message=USuperGlobalAccess::getPost('Message');
            $supportRequest=new ESupportRequest(0,$message,$topic,$idUser,$type);
            $PM=FPersistentManager::getInstance();
            $res=$PM->store($supportRequest);
            if ($res)
            {
                header('Location:/UniRent/'.ucfirst($type->value).'/contact/sent');
            }
            else
            {
                header('Location:/UniRent/'.ucfirst($type->value).'/contact/fail');
            }
        }
    }
    public static function ban(int $id, string $type, int $reportId)
    {
        $PM = FPersistentManager::getInstance();
        $user=$PM->load('E'.ucfirst($type), $id);
        $report=$PM->load('EReport', $reportId);
        $report->setBanDate(new DateTime('today'));
        $res=$PM->update($report);
        if (ucfirst($type)==='Student')
        {
            $user->setStatus(TStatusUser::BANNED);

        }
        else if (ucfirst($type)==='Owner')
        {
            $user->setStatus(TStatusUser::BANNED);
            $accommodationArray=$PM->loadAccommodationsByOwner($id);
            foreach ($accommodationArray as $accommodation) {
                $accommodation->setStatus(false);
                $PM->update($accommodation);
            }
        } else if (ucfirst($type)==='Review') {
            $user->ban();
        }
        $res=$PM->update($user);
        if ($res)
        {
            header('Location:'.$_COOKIE['current_page'].'/success');
        }
        else
        {
            header('Location:'.$_COOKIE['current_page'].'/error');
        }
    }
    public static function studentEmailIssue() {
        $mail = USuperGlobalAccess::getPost('emailIssue');
        $university = USuperGlobalAccess::getPost('university');
        $city = USuperGlobalAccess::getPost('city');
        $supportRequest= new ESupportRequest(null, 'A student is trying to register with the following email, which is not accepted by the system: '. $mail. '. This is the university: '. $university. ' of this city: '.$city, TRequestType::REGISTRATION, null, null);
        $PM=FPersistentManager::getInstance();
        $res=$PM->store($supportRequest);
        if ($res)
        {
            header('Location:/UniRent/User/showRegistration/success');
        }
        else
        {
            header('Location:/UniRent/User/showRegistration/error');
        }
    }
    public static function removeBanRequest(string $username) {
        $PM=FPersistentManager::getInstance();
        $topic=TRequestType::REMOVEBAN;
        $user= $PM->verifyUserUsername($username);
        $message=USuperGlobalAccess::getPost('Message');
        $supportRequest=new ESupportRequest(null,$message, $topic, $user['id'], $user['type']);
        $res=$PM->store($supportRequest);
        $res=true;
        if ($res)
        {
            $view=new VError();
            $view->error(600, $username, 'success');
        }
        else
        {
            $view=new VError();
            $view->error(600, $username, 'error');
        }
    }
    
    /**
     * Method active
     * 
     * this method permits the administrator to make active a Owner or a Student
     *
     * @param string $type [Owner/Student]
     * @param int $id [Owner/Student id]
     *
     * @return void
     */
    public static function active(string $type, int $id)
    {
        $PM = FPersistentManager::getInstance();
        $user = $PM->load('E'.ucfirst($type), $id);
        $user->setStatus(TStatusUser::ACTIVE);
        $res = $PM->update($user);
        if ($res)
        {
            header('Location:'.$_COOKIE['current_page'].'/success');
        }
        else
        {
            header('Location:'.$_COOKIE['current_page'].'/error');
        }
    }

    /**
     * Method verifyEmail
     * If an email isn't in the list of the universities, it will be added by admin
     * 
     * @param string $email [email to verify]
     * @param string $uniName [name of the university]
     * @param string $city [city of the university]
     * 
     * @return void
     */
    private static function verifyEmail(string $email, string $uniName, string $city):void{

        $email = explode(".", str_replace("@", ".", $email));
        $domain = array_slice($email, -2);
        $domain = "www." . $domain[0] . "." . $domain[1];

        $AUF=UAccessUniversityFile::getInstance();
        $AUF->addElement($domain, $uniName, $city);  

    }
        public static function profile(string $username, ?int $reportId = null, ?string $modalMessage = null)
    {
        self::checkIfAdmin();
        $PM=FPersistentManager::getInstance();
        $user=$PM->verifyUserUsername($username);
        $userType=$user['type'];
        $user=$PM->load('E'.ucfirst($userType), $user['id']);
        $view=new VAdmin();
        $user_photo=$user->getPhoto();
        if(is_null($user_photo)){}
        else
        {
            $user_photo_64=EPhoto::toBase64(array($user_photo));
            $user->setPhoto($user_photo_64[0]);
            #print_r($owner);
        }
        

        $reviews = $PM->loadByRecipient($user->getId(), TType::tryFrom(strtolower($userType)));
        $reviewsData = [];
        
        foreach ($reviews as $review) {
            $author = $PM->load( 'E' . $review->getAuthorType()->value, $review->getIdAuthor());
            $status = $author->getStatus();
            $profilePic = $author->getPhoto();
            if($status === TStatusUser::BANNED){
                $profilePic = "/UniRent/Smarty/images/BannedUser.png";
            }
            elseif ($profilePic === null) {
                $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
            }
            else
            {
                $profilePic=(EPhoto::toBase64(array($profilePic)))[0]->getPhoto();
            }
            if ($review->getDescription()===null) {
                $content='No additional details were provided by the author.';
            }
            else
            {
                $content=$review->getDescription();
            }
            $reviewsData[] = [
                'id' => $review->getId(),
                'title' => $review->getTitle(),
                'username' => $author->getUsername(),
                'userStatus' => $author->getStatus()->value,
                'stars' => $review->getValutation(),
                'content' => $content,
                'userPicture' => $profilePic,
                'statusBanned' => $review->isBanned(),
                'statusReported' => $review->isReported()
            ];
        }
        $view->profile($user, $userType, $reviewsData, $reportId, $modalMessage);
    }

    /**
     * Method get_Request_and_Report
     *
     * this method is used to get Reports and SupportRequests by the administrator. It is called by the jaascript therefore it prints a json
     * 
     */
    public static function get_Request_and_Report()
    {
        header('Content-Type: application/json');
        $PM=FPersistentManager::getInstance();
        $result=$PM->get_Request_and_Report();
        $reportsArray=$result['Report'];
        $countReports=0;
        $reports=[];
        foreach ($reportsArray as $report) {
            if ($report->getBanDate()===null) {
                $countReports++;
            }
            if ($report->getSubjectType()==TType::REVIEW) {
                $subject=$PM->load('EReview', $report->getIdSubject());
                $review = [
                    'id' => $subject->getId(),
                    'title' => $subject->getTitle(),
                    'description' => $subject->getDescription(),
                ];
                $usernameSubject=$PM->getUsernameById($subject->getIdAuthor(), $subject->getAuthorType());
            }
            else
            { 
                $review=null;
                $usernameSubject=$PM->getUsernameById($report->getIdSubject(), $report->getSubjectType());
            }
            $reports[]=[
                'id'=>$report->getId(),
                'description'=>$report->getDescription(),
                'made'=>$report->getMade()->format('Y-m-d'),
                'banDate'=>$report->getBanDate()? $report->getBanDate()->format('Y-m-d') : null,
                'type' => $report->getSubjectType()->value,
                'usernameSubject' =>$usernameSubject,
                'review' => $review
            ];
        }
        $requestsArray=$result['Request'];
        $requests=[];
        $res=[];
        $countRequests=0;
        $requestsArray = $result['Request'];
    $requests = [];
    $countRequests = 0;

    // Process requests
    foreach ($requestsArray as $request) {
        if ($request->getAuthorID() != null) {
            $author = $PM->getUsernameById($request->getAuthorID(), $request->getAuthorType());
        } else {
            $author = 'User';
        }

        switch ($request->getTopic()) {
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

        // Create a single request entry
        $requests[] = [
            'Request' => [
                [
                    'id' => $request->getId(),
                    'message' => $request->getMessage(),
                    'topic' => $topic,
                    'status' => $request->getStatus()->value,
                    'reply' => $request->getSupportReply()
                ]
            ],
            'author' => $author
        ];

        if ($request->getStatus()->value === 0) {
            $countRequests++;
        }
    }

    $response = [
        'Reports' => $reports,
        'Requests' => $requests,
        'countReports' => $countReports,
        'countRequests' => $countRequests
    ];
    echo json_encode($response);
    }
    
    /**
     * Method supportReply
     *
     * this method permits the administrator to answer to a supportRequest
     * @param int $id [supportRequest ID]
     *
     * @return void
     */
    public function supportReply(int $id)
    {
        $answare=USuperGlobalAccess::getPost('answare');
        $PM=FPersistentManager::getInstance();
        $result=$PM->SupportReply($id,$answare);
        if ($result){header('Location:'.$_COOKIE['current_page'].'/success');}
        else {header('Location:'.$_COOKIE['current_page'].'/error');}
    }
    public static function readMoreSupportRequest(?string $modalMessage = null)
    {   
        self::checkIfAdmin();
        $PM=FPersistentManager::getInstance();
        $requests=[];
        $count=1;
        $requestArray=$PM->get_Request_and_Report()['Request'];
        foreach ($requestArray as $request) {
            if ($request->getAuthorID() != null) {
                $author = $PM->getUsernameById($request->getAuthorID(), $request->getAuthorType());
            } else {
                $author = 'User';
            }
            switch ($request->getTopic()) {
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
            if (array_key_exists($count, $requests)) {
                if (count($requests[$count])==10) {
                    $count++;
                }
            }
            $requests[$count][]=[
                'id'=>$request->getId(),
                'message'=>$request->getMessage(),
                'topic'=>$topic,
                'author'=>$author,
                'status'=>$request->getStatus()->value,
                'reply'=>$request->getSupportReply()
            ];
        }
        $view=new VAdmin();
        $view->readMoreSupportRequest($requests, $count, $modalMessage);
    }
    public static function addToJson() {
        $email = USuperGlobalAccess::getPost('email');
        $uniName = USuperGlobalAccess::getPost('university');
        $city = USuperGlobalAccess::getPost('city');
        self::verifyEmail($email, $uniName, $city);
        $id=USuperGlobalAccess::getPost('requestId');
        $PM=FPersistentManager::getInstance();
        $request=$PM->load('ESupportRequest', $id);
        $request->setStatus(TStatusSupport::RESOLVED);
        $request->setSupportReply('The email has been added to the list of the universities');
        $res=$PM->update($request);
        if ($res)
        {
            header('Location:'.$_COOKIE['current_page'].'/success');
        }
        else
        {
            header('Location:'.$_COOKIE['current_page'].'/error');
        }
    }
    public static function deleteSupportRequest(int $id)
    {
        $PM=FPersistentManager::getInstance();
        $res=$PM->delete('ESupportRequest', $id);
        if ($res)
        {
            header('Location:'.$_COOKIE['current_page'].'/success');
        }
        else
        {
            header('Location:'.$_COOKIE['current_page'].'/error');
        }
    }
    public static function deleteReport(int $id)
    {
        $PM=FPersistentManager::getInstance();
        $res=$PM->delete('EReport', $id);
        if ($res)
        {
            header('Location:'.$_COOKIE['current_page'].'/success');
        }
        else
        {
            header('Location:'.$_COOKIE['current_page'].'/error');
        }
    }
    public static function readMoreReports(?string $modalMessage = null) {
        self::checkIfAdmin();
        $PM=FPersistentManager::getInstance();
        $reports=[];
        $count=1;
        $reportArray=$PM->get_Request_and_Report()['Report'];
        foreach ($reportArray as $report) {
                if (array_key_exists($count, $reports)) {
                    if (count($reports[$count])==10) {
                        $count++;
                    }
                }
                if ($report->getSubjectType()==TType::REVIEW) {
                    $subject=$PM->load('EReview', $report->getIdSubject());
                    $review = [
                        'id' => $subject->getId(),
                        'title' => $subject->getTitle(),
                        'description' => $subject->getDescription(),
                    ];
                    $usernameSubject=$PM->getUsernameById($subject->getIdAuthor(), $subject->getAuthorType());
                }
                else
                { 
                    $review=null;
                    $usernameSubject=$PM->getUsernameById($report->getIdSubject(), $report->getSubjectType());
                }
                $reports[$count][]=[
                    'id'=>$report->getId(),
                    'description'=>$report->getDescription(),
                    'made'=>$report->getMade()->format('Y-m-d'),
                    'banDate'=>$report->getBanDate()? $report->getBanDate()->format('Y-m-d') : null,
                    'type' => $report->getSubjectType()->value,
                    'usernameSubject' =>$usernameSubject,
                    'review' => $review
                ];
            }
        $view=new VAdmin();
        $view->readMoreReports($reports, $count, $modalMessage);
    }
    private static function checkIfAdmin() {
        $session = USession::getInstance();
        if ($session->getSessionElement('userType') !== 'Admin') {
            $view= new VError();
            $view->error(403);
        }
    }
}