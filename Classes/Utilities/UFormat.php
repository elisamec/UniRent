<?php
namespace Classes\Utilities;

use Classes\Entity\EOwner;
use Classes\Entity\EPhoto;
use Classes\Entity\EReport;
use Classes\Entity\EStudent;
use Classes\Tools\TStatusUser;
use Classes\Entity\EReview;
use Classes\Entity\ESupportRequest;
use Classes\Tools\TRequestType;

/**
 * class to format inputs into the one requested from the specific JavaScript function
 */
class UFormat
{
    /**
     * Method photoFormatReview
     * 
     * This method is used to format the profile picture of the user in the review display in the correct way
     * @param \Classes\Entity\EPhoto|null  $photo
     * @param \Classes\Tools\TStatusUser $status
     * @return string
     */
    public static function photoFormatReview(?EPhoto $photo, TStatusUser $status):string {
        if($status === TStatusUser::BANNED){
            $profilePic = "/UniRent/Smarty/images/BannedUser.png";
        }
        elseif (!$photo) {
            $profilePic = "/UniRent/Smarty/images/ImageIcon.png";
        }
        else
        {
            $profilePic=(EPhoto::toBase64(array($photo)))[0]->getPhoto();
        }
        return $profilePic;
    }
    /**
     * Methof photoFormatUser
     * 
     * This method is used to format the profile picture of the user in the profile display in the correct way
     * @param \Classes\Entity\EOwner|\Classes\Entity\EStudent $user
     * @return void
     */
    public static function photoFormatUser(EOwner | EStudent $user):void {
        $user_photo=$user->getPhoto();
        if ($user->getStatus() === TStatusUser::BANNED) {
            $path = __DIR__ . "/../../Smarty/images/BannedUser.png";
            $user_photo = new EPhoto(null, file_get_contents($path), 'other', null);
            $user_photo_64=EPhoto::toBase64(array($user_photo));
            $user->setPhoto($user_photo_64[0]);
        }
        else if(is_null($user_photo)){}
        else
        {
            $user_photo_64=EPhoto::toBase64(array($user_photo));
            $user->setPhoto($user_photo_64[0]);
        }
    }
    /**
     * Method reviewsFormatAdmin
     * 
     * This method is used to format the reviews in the admin view of the user profile in the correct way
     * @param \Classes\Entity\EStudent|\Classes\Entity\EOwner $author
     * @param \Classes\Entity\EReview $review
     * @return array
     */
    public static function reviewsFormatAdmin(EStudent | EOwner $author, EReview $review):array {
        $status = $author->getStatus();
        $profilePic = $author->getPhoto();
        $profilePic = self::photoFormatReview($profilePic, $status);
        $content = $review->getDescription() === null ? 'No additional details were provided by the author.' : $review->getDescription();
        return [
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
    /**
     * Method reviewsFormatUser
     * 
     * This method is used to format the reviews in the user view of the user profile in the correct way
     * @param \Classes\Entity\EStudent|\Classes\Entity\EOwner $author
     * @param \Classes\Entity\EReview $review
     * @return array
     */
    public static function reviewsFormatUser(EStudent | EOwner $author, EReview $review):array {
        $status = $author->getStatus();
        $profilePic = $author->getPhoto();
        $profilePic = self::photoFormatReview($profilePic, $status);
        $content = $review->getDescription() === null ? 'No additional details were provided by the author.' : $review->getDescription();
        return [
            'id' => $review->getId(),
            'title' => $review->getTitle(),
            'username' => $author->getUsername(),
            'userStatus' => $author->getStatus()->value,
            'stars' => $review->getValutation(),
            'content' => $content,
            'userPicture' => $profilePic,
        ];
    }
    /**
     * Method formatReports
     * 
     * This method is used to format the reports in the admin dropdowns in the correct way
     * @param \Classes\Entity\EReport $report
     * @param string $usernameSubject
     * @param \Classes\Entity\EReview|null $review
     */
    public static function formatReports(EReport $report, string $usernameSubject, ?EReview $review):array {
        if ($review) {
            $reviewFormat = [
                'id' => $review->getId(),
                'title' => $review->getTitle(),
                'description' => $review->getDescription(),
            ];
        }
        else
        { 
            $review=null;
        }
        return [
            'id'=>$report->getId(),
            'description'=>$report->getDescription(),
            'made'=>$report->getMade()->format('Y-m-d'),
            'banDate'=>$report->getBanDate()? $report->getBanDate()->format('Y-m-d') : null,
            'type' => $report->getSubjectType()->value,
            'usernameSubject' =>$usernameSubject,
            'review' => $reviewFormat
        ];
    }
    /**
     * Method formatTopic
     * 
     * This method is used to format the topic of the request in the correct way
     * @param \Classes\Tools\TRequestType $topic
     * @return string
     */
    public static function formatTopic(TRequestType $topic):string {
        switch ($topic) {
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
        return $topic;
    }
    public static function formatRequests(ESupportRequest $request, string $author, bool $page = false):array {
        if (!$page) {
            $format = [
                'Request' => [
                    [
                        'id' => $request->getId(),
                        'message' => $request->getMessage(),
                        'topic' => self::formatTopic($request->getTopic()),
                        'status' => $request->getStatus()->value,
                        'reply' => $request->getSupportReply()
                    ]
                ],
                'author' => $author
            ];
        } else {
            $format = [
                'id'=>$request->getId(),
                'message'=>$request->getMessage(),
                'topic'=>self::formatTopic($request->getTopic()),
                'author'=>$author,
                'status'=>$request->getStatus()->value,
                'reply'=>$request->getSupportReply()
            ];
        }
        
        return $format;

    }
}