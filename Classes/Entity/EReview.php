<?php

require_once ('EPhoto.php');
require_once('../Tools/TType.php');
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
     * @var array $photo            Multiple instances of EPhoto stored in an array. 
     *                              These are the photos linked to this specific review.
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
    private array $photo;
    private TType $recipientType;
    private DateTime $creationDate;
    private TType $authorType;
    private int $idAuthor;
    private int $idRecipient;
    private static $entity = EReview::class;

    /**
     * __construct
     *
     * @param integer|null $idReview
     * @param string $title
     * @param integer $valutation
     * @param string|null $description
     * @param array $photo
     * @param TType $type
     * @param DateTime $creationDate
     * @param TType $authorType
     * @param integer $idAuthor
     * @param integer $idRecipient
     */
    public function __construct(?int $idReview=null, string $title, int $valutation, ?string $description, array $photo, TType $type, DateTime $creationDate, TType $authorType, int $idAuthor, int $idRecipient) 
    {
        $this->idReview=$idReview;
        $this->title=$title;
        $this->$valutation;
        $this->description=$description;
        $this->recipientType=$type;
        $this->creationDate=$creationDate;
        $this->photo = $photo;
        $this->authorType=$authorType;
        $this->idAuthor=$idAuthor;
        $this->idRecipient=$idRecipient;
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

    public function getValutation():int
    {
        return $this->valutation;
    }

    public function getDescription():string | null
    {
        return $this->description;
    }
    public function getRecipientType():TType
    {
        return $this->recipientType;
    }
    public function getAuthorType():TType {
        return $this->authorType;
    }
    public function getIDAuthor():int {
        return $this->idAuthor;
    }
    public function getIDRecipient():int {
        return $this->idRecipient;
    }

    public function getCreationDate():DateTime
    {
        return $this->creationDate;
    }
    public function setTitle(string $newTitle):void
    {
        $this->title=$newTitle;
    }
    public function setValutation(int $valutation):void
    {
        $this->valutation = $valutation;
    }
    public function setDescription(string $newDesc):void
    {
        $this->description=$newDesc;
    }
    public function setId(int $id):void {
        if ($this->idReview===null) {
            $this->idReview=$id;
        }
    } 

    public function uploadPhoto(EPhoto $photo):void {
        $this->photo[]=$photo;
    }
    public function getPhotos():array|null
    {
        $values = array_values($this->photo);
        if (count($values)===0) {
            return null;
        }
        else {
            return array_values($this->photo);
        }
    }
}