<?php

require_once ('EPhoto.php');
enum Type 
{
    case student;
    case accommodation;
    case owner;
}

class EReview 
{
    private int $idReview;
    private string $title;
    private int $valutation;
    private string $description;
    private EPhoto $photo;
    private Type $type;
    private DateTime $creationDate;

    private static $entity = EReview::class;

    public function __construct($idReview, $title, $valutation, $description, $type, $creationDate) 
    {
        $this->idReview=$idReview;
        $this->title=$title;
        $this->valutation=$valutation;
        $this->description=$description;
        $this->type=$type;
        $this->creationDate=$creationDate;
    }

    public function getId() 
    {
        return $this->idReview;
    }

    public function getTitle() 
    {
        return $this->title;
    }

    public function getValutation()
    {
        return $this->valutation;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }



    public function setTitle($newTitle)
    {
        $this->title=$newTitle;
    }

    //public function uploadImage($Image) {}
}