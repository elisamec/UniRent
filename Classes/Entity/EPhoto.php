<?php
namespace Classes\Entity;
/**
 * EPhoto
 *  
 * This class represents a photo
 * @package Entity
 */
class EPhoto {
    /**
     * @var int $id The unique identifier of the photo.
     * @var string $photo The photo itself.
     * @var string $relativeTo The entity to which the photo is related.
     * @var int $idAccommodation The identifier of the accommodation to which the photo is related.
     * @var int $idReview The identifier of the review to which the photo is related.
     */
    private ?int $id;
    private $photo;
    private string $relativeTo;
    private ?int $idAccommodation;
    private ?int $idReview;

    /**
     * __construct
     * Constructor for the EPhoto class
     * @param int $id The unique identifier of the photo.
     * @param string $photo The photo itself.
     * @param string $relativeTo The entity to which the photo is related.
     * @param int $idAccommodation The identifier of the accommodation to which the photo is related.
     * @param int $idReview The identifier of the review to which the photo is related.
     */
    public function __construct(?int $id, $photo, string $relativeTo, ?int $idAccommodation, ?int $idReview) {
        $this->id = $id;
        $this->photo = $photo;
        $this->relativeTo = $relativeTo;
        $this->idAccommodation = $idAccommodation;
        $this->idReview = $idReview;
    }

    /**
     * getId
     * Returns the id of the photo
     * @return int
     */
    public function getId():int {
        return $this->id;
    }

    /**
     * getPhoto
     * Returns the photo itself
     * @return string
     */
    public function getPhoto() {
        return $this->photo;
    }

    /**
     * getRelativeTo
     * Returns the entity to which the photo is related
     * @return string
     */
    public function getRelativeTo():string {
        return $this->relativeTo;
    }

    /**
     * getIdAccommodation
     * Returns the id of the accommodation to which the photo is related
     * @return int
     */
    public function getIdAccommodation():?int {
        return $this->idAccommodation;
    }

    /**
     * getIdReview
     * Returns the id of the review to which the photo is related
     * @return int
     */
    public function getIdReview():?int {
        return $this->idReview;
    }

    /**
     * setId
     * Sets the id of the photo
     * @param int $id The unique identifier of the photo.
     * @return void
     */
    public function setId(int $id):void {
        $this->id = $id;
    }

    /**
     * setPhoto
     * Sets the photo itself
     * @param string $photo The photo itself.
     * @return void
     */
    public function setPhoto($photo):void {
        $this->photo = $photo;
    }

    /**
     * setRelativeTo
     * Sets the entity to which the photo is related
     * @param string $relativeTo The entity to which the photo is related.
     * @return void
     */
    public function setRelativeTo(string $relativeTo):void {
        $this->relativeTo = $relativeTo;
    }

    /**
     * setIdAccommodation
     * Sets the id of the accommodation to which the photo is related
     * @param int $idAccommodation The identifier of the accommodation to which the photo is related.
     * @return void
     */
    public function setIdAccommodation(?int $idAccommodation):void {
        $this->idAccommodation = $idAccommodation;
    }

    /**
     * setIdReview
     * Sets the id of the review to which the photo is related
     * @param int $idReview The identifier of the review to which the photo is related.
     * @return void
     */
    public function setIdReview(?int $idReview):void {
        $this->idReview = $idReview;
    }

    /**
     * __toString
     * Converts the object to string
     * @return string
     */
    public function __toString():string{
        $photo = "photo";
        return "Id: $this->id, $photo, Relative To: $this->relativeTo, Id Accommodation: $this->idAccommodation, Id Review: $this->idReview";
    }
    
    /**
     * Method fromJsonToPhotos
     *
     * this method is used by the owner to add an accommodation. JS send a JSON file where are presents
     * the photos, create an array of EPhoto  class and retrive it.
     * @param $array $array [explicite description]
     *
     * @return array
     */
    public static function fromJsonToPhotos($array):array
    {
        $result=array();
        foreach($array as $p)
        {
            if(is_null($p)){}
            else
            {
                $binaryPhoto=file_get_contents($p);
                $np= new EPhoto(null,$binaryPhoto,'accommodation',null,null);
                $result[]=$np;
            }
        }
        return $result;
    }
}
