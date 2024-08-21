<?php
namespace Classes\Utilities;

use Classes\Entity\EOwner;
use Classes\Entity\EPhoto;
use Classes\Entity\EStudent;
use Classes\Tools\TStatusUser;
use Classes\Entity\EReview;

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
        if ($review->getDescription()===null) {
            $content='No additional details were provided by the author.';
        }
        else
        {
            $content=$review->getDescription();
        }
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
}