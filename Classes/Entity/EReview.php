<?php
namespace Classes\Entity;
require __DIR__ . '../../../vendor/autoload.php';

use Classes\Foundation\FPersistentManager;
use Classes\Tools\TType;
use Classes\Tools\TStatusUser;
use DateTime;
/**
 * EReview
 * 
 * This class depicts a Review
 * 
 * @author Elisabetta Mecozzi <elisabetta.mecozzi0@gmail.com>
 * @package Entity
 */
class EReview 
{
    /**
     * @var ?int $idReview          The unique identifier of the review itself. 
     *                              It is given by the DB when stored.
     * @var string $title           The title of the review.
     * @var int $valutation         The valutation of the review. 
     *                              Needs to be between 1 and 5: achieved through the GUI.
     * @var ?string $description    The description of the review. Can be empty.
     * @var TType $recipientType    The type of the recipient. Needed for storing in the DB.
     * @var DateTime $creationDate  The date it was created. Given from the DB.
     * @var TType $authorType       Type of author. Needed for student reviews storing.
     * @var int $idAuthor           Identifier of the author.
     * @var int $idRecipient        Identifier of the recipient.
     * @var $entity                 The class.
     */
    private ?int $idReview;
    private string $title;
    private int $valutation;
    private ?string $description;
    private TType $recipientType;
    private DateTime $creationDate;
    private TType $authorType;
    private int $idAuthor;
    private int $idRecipient;
    private bool $reported;
    private bool $banned;
    private static $entity = EReview::class;

    /**
     * __construct
     *
     * @param integer|null $idReview
     * @param string $title
     * @param integer $valutation
     * @param string|null $description
     * @param TType $type
     * @param DateTime $creationDate
     * @param TType $authorType
     * @param integer $idAuthor
     * @param integer $idRecipient
     * @param boolean $reported
     * @param boolean $banned
     */
    public function __construct(?int $idReview, string $title, int $valutation, ?string $description, TType $type, DateTime $creationDate, TType $authorType, int $idAuthor, int $idRecipient, bool $reported=false, bool $banned=false) 
    {
        $this->idReview=$idReview;
        $this->title=$title;
        $this->valutation=$valutation;
        $this->description=$description;
        $this->recipientType=$type;
        $this->creationDate=$creationDate;
        $this->authorType=$authorType;
        $this->idAuthor=$idAuthor;
        $this->idRecipient=$idRecipient;
        $this->reported=$reported;
        $this->banned=$banned;
    }
    /**
     * getEntity()
     *
     * @return string
     */
    public function getEntity():string {
        return $this->entity;
    }
    /**
     * getId()
     *
     * @return integer|null
     */
    public function getId():?int 
    {
        return $this->idReview;
    }

    /**
     * getTitle()
     *
     * @return string
     */
    public function getTitle():string 
    {
        return $this->title;
    }
    /**
     * getValutation()
     *
     * @return integer
     */
    public function getValutation():int
    {
        return $this->valutation;
    }
    /**
     * getDescription()
     *
     * @return string|null
     */
    public function getDescription():string | null
    {
        return $this->description;
    }
    /**
     * getRecipientType()
     *
     * @return TType
     */
    public function getRecipientType():TType
    {
        return $this->recipientType;
    }
    /**
     * getAuthorType()
     *
     * @return TType
     */
    public function getAuthorType():TType {
        return $this->authorType;
    }
    /**
     * getIDAuthor()
     *
     * @return integer
     */
    public function getIDAuthor():int {
        return $this->idAuthor;
    }
    /**
     * getIDRecipient()
     *
     * @return integer
     */
    public function getIDRecipient():int {
        return $this->idRecipient;
    }
    /**
     * getCreationDate()
     *
     * @return DateTime
     */
    public function getCreationDate():DateTime
    {
        return $this->creationDate;
    }
    /**
     * isReported()
     *
     * @return boolean
     */
    public function isReported():bool
    {
        return $this->reported;
    }
    /**
     * isBanned()
     *
     * @return boolean
     */
    public function isBanned():bool
    {
        return $this->banned;
    }
    /**
     * setTitle()
     *
     * @param string $newTitle
     * @return void
     */
    public function setTitle(string $newTitle):void
    {
        $this->title=$newTitle;
    }
    /**
     * setValutation()
     *
     * @param integer $valutation
     * @return void
     */
    public function setValutation(int $valutation):void
    {
        $this->valutation = $valutation;
    }
    /**
     * setDescription()
     *
     * @param string $newDesc
     * @return void
     */
    public function setDescription(string $newDesc):void
    {
        $this->description=$newDesc;
    }
    /**
     * setRecipientType()
     *
     * @param TType $type
     * @return void
     */
    public function setId(int $id):void {
        if ($this->idReview===null) {
            $this->idReview=$id;
        }
    }
    /**
     * setRecipientType()
     *
     * @param TType $type
     * @return void
     */
   public function report():void
    {
        $this->reported=true;
    }
    /**
     * setRecipientType()
     *
     * @param TType $type
     * @return void
     */
    public function ban():void
    {
        $this->banned=true;
    }
    /**
     * setRecipientType()
     *
     * @param TType $type
     * @return void
     */
    public function unban():void
    {
        $this->banned=false;
    }
    /**
     * setRecipientType()
     *
     * @param TType $type
     * @return void
     */
    public function unreport():void
    {
        $this->reported=false;
    }
    /**
     * setRecipientType()
     *
     * @param TType $type
     * @return void
     */
    public function __toString():string
    {
        $description = ($this->description !== null) ? $this->description : 'No additional details were provided by the author. set';
        return 'ID: '. $this->idReview. ', Title: '. $this->title. ', Valutation: '. $this->valutation. ', Description: '. $description. ', RecipientType: '. $this->recipientType->value. ', AuthorType: '.$this->authorType->value.', IDAuthor: '. $this->idAuthor. ', IDRecipient: '.$this->idRecipient;
    }
    
      
    /**
     * Method remainingReviewStudentToStudent
     *
     * this method return the number of review that a Student can make about another Student
     * @param int $id1 [student 1 who makes the reviews]
     * @param int $id2 [student 2]
     *
     * @return int
     */
    public static function remainingReviewStudentToStudent(int $id1, int $id2):int
    {
        $result=FPersistentManager::getInstance()->remainingReviewStudentToStudent($id1,$id2);
        return $result;
    }
    
    /**
     * Method remainingReviewStudentToOwner
     *
     * this method return the number of review that a Student can make about an Owner
     * @param int $id1 [student , the one who makes the reviews]
     * @param int $id2 [owner]
     *
     * @return int
     */
    public static function remainingReviewStudentToOwner(int $id1, int $id2):int
    {
        $result=FPersistentManager::getInstance()->remainingReviewStudentToOwner($id1,$id2);
        return $result;
    }
    
    
        
}