<?php

require_once ('EPhoto.php');
require_once('../utility/Type.php');

class EReview 
{
    private ?int $idReview;
    private string $title;
    private int $valutation;
    private ?string $description;
    private array $photo;
    private Type $recipientType;
    private DateTime $creationDate;
    private Type $authorType;
    private int $idAuthor;
    private int $idRecipient;

    private static $entity = EReview::class;

    public function __construct(?int $idReview, string $title, int $valutation, ?string $description, ?array $photo, Type $type, DateTime $creationDate, Type $authorType, int $idAuthor, int $idRecipient) 
    {
        $this->idReview=$idReview;
        $this->title=$title;
        $this->setValutation($valutation);
        $this->description=$description;
        $this->recipientType=$type;
        $this->creationDate=$creationDate;
        if ($photo===null) {
            $this->photo =[];
        }
        else {
            $this->photo = $photo;
        }
        $this->authorType=$authorType;
        $this->idAuthor=$idAuthor;
        $this->idRecipient=$idRecipient;
    }
    
    public function getEntity():string {
        return $this->entity;
    }

    public function getId():?int 
    {
        return $this->idReview;
    }

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
    public function getRecipientType():Type
    {
        return $this->recipientType;
    }
    public function getAuthorType():Type {
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
        if ($valutation < 1 || $valutation > 5) {
            throw new InvalidArgumentException('Valutation must be a number between 1 and 5.');
        }
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