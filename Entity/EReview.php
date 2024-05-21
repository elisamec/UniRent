<?php

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
    private DateTime $madeDate;

    private static $entity = EReview::class;

    public function __construct($idReview, $title, $valutation, $description, $type) 
    {
        $this->idReview=$idReview;
        $this->title=$title;
        $this->valutation=$valutation;
        $this->description=$description;
        $this->type=$type;
        $this->setTime();
    }

    private function setTime()
    {
        $this->madeDate= new DateTime("now");
    }

    public function getId() 
    {
        return $this->idReview;
    }

    public function getTitle() {

    }



}