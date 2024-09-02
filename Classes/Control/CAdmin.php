<?php

namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';

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
use Classes\Tools\TStatusSupport;
use Classes\Utilities\UFormat;

class CAdmin
{
    /**
     * Method home
     * 
     * Shows Admin's dashboard
     * @param ?string $modalMessage
     * @return void
     */
    public static function home(?string $modalMessage = null):void{
        self::checkIfAdmin();
        $PM=FPersistentManager::getInstance();
        $stats=$PM->getStatistics();
        $banned=$PM->getBannedList();
        $view = new VAdmin();
        $view->home($stats, $banned, $modalMessage);
    }

    /**
     * Method login
     * 
     * Shows Admin's login page
     * @return void
     */
    public static function login():void{
        if(UCookie::isSet('PHPSESSID'))
        {
            setcookie('PHPSESSID','',time()-3600);
            $session = USession::getInstance();
        }
        $view = new VAdmin();
        $view->login();
    }

    /**
     * Checks if the login is correct for the admin
     * @return void
     */
    public static function checkLogin():void
    {

        $view = new VAdmin();
                $passwordIn=USuperGlobalAccess::getPost('password');

                if(password_verify($passwordIn, '$2y$10$oqDyOSQOyj8bBbbeq1UFfe5B.zB/HGenmr9IRQnzGSBI5eRrHRF5i'))
                {
                   
                    $session = USession::getInstance();
                    $session->setSessionElement("userType", 'Admin');
                    header('Location:/UniRent/Admin/home'); 
                }
                else  #password is not correct
                {
                    $view->loginError();
                }
    }

    /**
     * Method logout
     * 
     * Logs out the admin
     * @return void
     */
    public static function logout():void
    {
        $session = USession::getInstance();
        $session->destroySession();
        header('Location:/UniRent/User/home');
    }

    /**
     * Method ban
     * 
     * this method permits the administrator to ban a Student, an Owner or a Review
     *
     * @param int $id [Student/Owner/Review id]
     * @param string $type [Student/Owner/Review]
     * @param int $reportId [Report id]
     *
     * @return void
     */
    public static function ban(int $id, string $type, int $reportId):void
    {
        $PM = FPersistentManager::getInstance();
        $user=$PM->load('E'.ucfirst($type), $id);
        $report=$PM->load('EReport', $reportId);
        $report->setBanDate(new DateTime('today'));
        $res=$PM->update($report);
        if (ucfirst($type)==='Review') {
            $user->ban();
        } else {
            $user->setStatus(TStatusUser::BANNED);
            if (ucfirst($type)==='Owner')
            {
                $accommodationArray=$PM->loadAccommodationsByOwner($id);
                foreach ($accommodationArray as $accommodation) {
                    $accommodation->setStatus(false);
                    $PM->update($accommodation);
                }
            }
        }
        $res=$PM->update($user);
        $modalSuccess = $res ? 'success' : 'error';
        header('Location:'.USuperGlobalAccess::getCookie('current_page').'/'.$modalSuccess);
    }

    
    
    /**
     * Method activate
     * 
     * this method permits the administrator to remove a ban of a Owner or a Student
     *
     * @param string $type [Owner/Student]
     * @param int $id [Owner/Student id]
     *
     * @return void
     */
    public static function activate(string $type, int $id):void
    {
        $PM = FPersistentManager::getInstance();
        $user = $PM->load('E'.ucfirst($type), $id);
        $user->setStatus(TStatusUser::ACTIVE);
        $res = $PM->update($user);
        $modalSuccess = $res ? 'success' : 'error';
        header('Location:'.USuperGlobalAccess::getCookie('current_page').'/'.$modalSuccess);
    }

    /**
     * Method addToJSON
     * If an email isn't in the list of the universities, it will be added by admin
     * 
     * @param string $email [email to verify]
     * @param string $uniName [name of the university]
     * @param string $city [city of the university]
     * 
     * @return void
     */
    private static function addToJSON(string $email, string $uniName, string $city):void{

        $email = explode(".", str_replace("@", ".", $email));
        $domain = array_slice($email, -2);
        $domain = "www." . $domain[0] . "." . $domain[1];

        $AUF=UAccessUniversityFile::getInstance();
        $AUF->addElement($domain, $uniName, $city);
    }

    /**
     * Method profile
     * 
     * this method permits the administrator to see the profile of a Student or an Owner
     * 
     * @param string $username
     * @param mixed $reportId (needed to mark the curresponding report as solved)
     * @param mixed $modalMessage
     * @return void
     */
    public static function profile(string $username, ?int $reportId = null, ?string $modalMessage = null):void
    {
        self::checkIfAdmin();
        $PM=FPersistentManager::getInstance();
        $user=$PM->verifyUserUsername($username);
        $userType=$user['type'];
        $user=$PM->load('E'.ucfirst($userType), $user['id']);
        UFormat::photoFormatUser($user);
        $reviewsData = CReview::getProfileReviews($user->getId(), $userType);
        $view=new VAdmin();
        $view->profile($user, $userType, $reviewsData, $reportId, $modalMessage);
    }

    /**
     * Method getRequestAndReport
     *
     * this method is used to get Reports and SupportRequests by the administrator. It is called by the javascript therefore it prints a json
     * 
     * @return void
     */
    public static function getRequestAndReport():void
    {
        header('Content-Type: application/json');
        $PM=FPersistentManager::getInstance();

        $result=$PM->getRequestAndReport();
        $reportsArray=$result['Report'];
        $countReports=0;
        $reports=[];

        foreach ($reportsArray as $report) {

            if ($report->getBanDate()===null) {
                $countReports++;
            }

            $isReview = $report->getSubjectType()==TType::REVIEW;
            $subject = $isReview ? $PM->load('EReview', $report->getIdSubject()) : $report;
            $usernameSubject=$PM->getUsernameById($subject->getIdAuthor(), $subject->getAuthorType());
            $reports[]= UFormat::formatReports($report, $usernameSubject, $isReview ? $subject : null);
        }

        $requestsArray = $result['Request'];
        $requests = [];
        $countRequests = 0;

        foreach ($requestsArray as $request) {

            $author = $request->getAuthorID() != null ? $PM->getUsernameById($request->getAuthorID(), $request->getAuthorType()) : 'User';
            $requests[] = UFormat::formatRequests($request, $author);

            if ($request->getStatus()->value === 0) {
                $countRequests++;
            }
        }

        $response = ['Reports' => $reports, 'Requests' => $requests, 'countReports' => $countReports, 'countRequests' => $countRequests];
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
    public function supportReply(int $id):void
    {
        $answare=USuperGlobalAccess::getPost('answare');
        $PM=FPersistentManager::getInstance();
        $result=$PM->SupportReply($id,$answare);
        if ($result){header('Location:'.USuperGlobalAccess::getCookie('current_page').'/success');}
        else {header('Location:'.USuperGlobalAccess::getCookie('current_page').'/error');}
    }

    /**
     * Method readMoreSupportRequest
     * 
     * this method permits the administrator to see the page with all the support requests
     * 
     * @param ?string $modalMessage
     * @return void
     */
    public static function readMoreSupportRequest(?string $modalMessage = null):void
    {   
        self::checkIfAdmin();
        $PM=FPersistentManager::getInstance();

        $requests=[];
        $count=1;
        $requestArray=$PM->getRequestAndReport()['Request'];

        foreach ($requestArray as $request) {
            
            $author = $request->getAuthorID() != null ? $PM->getUsernameById($request->getAuthorID(), $request->getAuthorType()) : 'User';
            if (array_key_exists($count, $requests)) {
                if (count($requests[$count])==10) {
                    $count++;
                }
            }
            $requests[$count][]=UFormat::formatRequests($request, $author, true);
        }

        $view=new VAdmin();
        $view->readMoreSupportRequest($requests, $count, $modalMessage);
    }

    /**
     * Method verifyEmail
     * 
     * this method permits the administrator to add an email to the JSON file used for mail verification
     * 
     * @return void
     */
    public static function verifyEmail():void {
        $email = USuperGlobalAccess::getPost('email');
        $uniName = USuperGlobalAccess::getPost('university');
        $city = USuperGlobalAccess::getPost('city');
        self::addToJSON($email, $uniName, $city);
        $id=USuperGlobalAccess::getPost('requestId');
        $PM=FPersistentManager::getInstance();
        $request=$PM->load('ESupportRequest', $id);
        $request->setStatus(TStatusSupport::RESOLVED);
        $request->setSupportReply('The email has been added to the list of the universities');
        $res=$PM->update($request);
        $modalSuccess = $res ? 'success' : 'error';
        header('Location:'.USuperGlobalAccess::getCookie('current_page').'/'.$modalSuccess);
    }
    
    /**
     * Method deleteSupportRequest
     * 
     * this method permits the administrator to delete a support request. It can be called only for email verification requests
     * 
     * @param int $id [supportRequest ID]
     * 
     * @return void
     */
    public static function deleteSupportRequest(int $id):void
    {
        $PM=FPersistentManager::getInstance();
        $res=$PM->delete('ESupportRequest', $id);
        $modalSuccess = $res ? 'success' : 'error';
        header('Location:'.USuperGlobalAccess::getCookie('current_page').'/'.$modalSuccess);
    }

    /**
     * Method deleteReport
     * 
     * this method permits the administrator to delete a report in case a ban is not considered necessary
     * 
     * @param int $id [report ID]
     * 
     * @return void
     */
    public static function deleteReport(int $id):void
    {
        $PM=FPersistentManager::getInstance();
        $res=$PM->delete('EReport', $id);
        $modalSuccess = $res ? 'success' : 'error';
        header('Location:'.USuperGlobalAccess::getCookie('current_page').'/'.$modalSuccess);
    }

    /**
     * Method readMoreReports
     * 
     * this method permits the administrator to see the page with all the reports
     * 
     * @param ?string $modalMessage
     * @return void
     */
    public static function readMoreReports(?string $modalMessage = null):void {
        self::checkIfAdmin();
        $PM=FPersistentManager::getInstance();
        $reports=[];
        $count=1;
        $reportArray=$PM->getRequestAndReport()['Report'];
        foreach ($reportArray as $report) {
                if (array_key_exists($count, $reports)) {
                    if (count($reports[$count])==10) {
                        $count++;
                    }
                }
                $isReview = $report->getSubjectType()==TType::REVIEW;
                $subject =  $isReview ? $PM->load('EReview', $report->getIdSubject()) : $report;
                $review = $isReview ? $subject : null;
                $usernameSubject=$PM->getUsernameById($subject->getIdAuthor(), $subject->getAuthorType());
                $reports[$count][]=UFormat::formatReports($report, $usernameSubject, $review);
            }
        $view=new VAdmin();
        $view->readMoreReports($reports, $count, $modalMessage);
    }
    
    /**
     * Method checkIfAdmin
     * 
     * this method checks if the user is an admin. If not, it shows an error page
     * 
     * @return void
     */
    public static function checkIfAdmin():void {
        $session = USession::getInstance();
        if ($session->getSessionElement('userType') !== 'Admin') {
            $view= new VError();
            $view->error(403);
            exit();
        }
    }
}