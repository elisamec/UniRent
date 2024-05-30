<?php
//Da rivedere
class EPhoto {
    private int $id;
    private $photo;
    private string $relativeTo;
    private ?int $idAccommodation;
    private ?int $idReview;

    public function __construct(int $id, $photo, string $relativeTo, ?int $idAccommodation, ?int $idReview) {
        $this->id = $id;
        $this->photo = $photo;
        $this->relativeTo = $relativeTo;
        $this->idAccommodation = $idAccommodation;
        $this->idReview = $idReview;
    }

    public function getId():int {
        return $this->id;
    }

    public function getPhoto() {
        return $this->photo;
    }

    public function getRelativeTo():string {
        return $this->relativeTo;
    }

    public function getIdAccommodation():?int {
        return $this->idAccommodation;
    }

    public function getIdReview():?int {
        return $this->idReview;
    }

    public function setId(int $id):void {
        $this->id = $id;
    }

    public function setPhoto($photo):void {
        $this->photo = $photo;
    }

    public function setRelativeTo(string $relativeTo):void {
        $this->relativeTo = $relativeTo;
    }


    public function setIdAccommodation(?int $idAccommodation):void {
        $this->idAccommodation = $idAccommodation;
    }

    public function setIdReview(?int $idReview):void {
        $this->idReview = $idReview;
    }

        
    public function __toString():string{
        $photo = "photo";
        return "Id: $this->id, $photo, Relative To: $this->relativeTo, Id Accommodation: $this->idAccommodation, Id Review: $this->idReview";
    }
}
