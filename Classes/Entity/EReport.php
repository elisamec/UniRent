<?php
namespace Classes\Entity;
require __DIR__.'../../../vendor/autoload.php';

use Classes\Tools\TType;
use DateTime;

/**
 * EReport
 * 
 * This class represents a report
 * 
 * @package Classes\Entity
 */
class EReport {
    /**
     * 
     * @var ?int $id the id of the report
     * @var string $description the description of the report
     * @var DateTime $made the date when the report was made
     * @var ?DateTime $banDate the date when the subject was banned
     * @var int $idSubject the id of the subject of the report
     * @var TType $typeSubject the type of the subject of the report
     */
    private ?int $id;
    private string $description;
    private DateTime $made;
    private ?DateTime $banDate;
    private ?int $idSubject;
    private TType $typeSubject;

    /**
     * Constructor
     * 
     * @param ?int $id the id of the report
     * @param string $description the description of the report
     * @param DateTime $made the date when the report was made
     * @param ?DateTime $banDate the date when the subject was banned
     * @param int $idSubject the id of the subject of the report
     * @param TType|string $typeSubject the type of the subject of the report
     */
    public function __construct(?int $id=null, string $description, DateTime $made=new DateTime('today'), ?DateTime $banDate=null, int $idSubject, TType | string $typeSubject) {
        $this->id=$id;
        $this->description=$description;
        $this->made=$made;
        $this->banDate=$banDate;
        $this->idSubject=$idSubject;
        $this->typeSubject=$typeSubject instanceof TType ? $typeSubject : TType::from($typeSubject);
    }
    /**
     * getId
     * 
     * This method is used to get the id of the report
     * @return ?int
     */
    public function getId(): ?int {
        return $this->id;
    }
    /**
     * setId
     * 
     * This method is used to set the id of the report
     * @param int $id
     */
    public function setId(int $id): void {
        $this->id = $id;
    }
    /**
     * getDescription
     * 
     * This method is used to get the description of the report
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }
    /**
     * setDescription
     * 
     * This method is used to set the description of the report
     * @param string $description
     */
    public function setDescription(string $description): void {
        $this->description = $description;
    }
    /**
     * getMade
     * 
     * This method is used to get the date when the report was made
     * @return DateTime
     */
    public function getMade(): DateTime {
        return $this->made;
    }
    /**
     * setMade
     * 
     * This method is used to set the date when the report was made
     * @param DateTime $made
     */
    public function getBanDate(): ?DateTime {
        return $this->banDate;
    }
    /**
     * setBanDate
     * 
     * This method is used to set the date when the subject was banned
     * @param DateTime $banDate
     */
    public function setBanDate(?DateTime $banDate): void {
        $this->banDate = $banDate;
    }
    /**
     * getIdSubject
     * 
     * This method is used to get the id of the subject of the report
     * @return int
     */
    public function getIdSubject(): int {
        return $this->idSubject;
    }
    /**
     * setIdSubject
     * 
     * This method is used to set the id of the subject of the report
     * @param int $idSubject
     */
    public function setIdSubject(int $idSubject): void {
        $this->idSubject = $idSubject;
    }
    /**
     * getSubjectType
     * 
     * This method is used to get the type of the subject of the report
     * @return TType
     */
    public function getSubjectType(): TType {
        return $this->typeSubject;
    }
    /**
     * setSubjectType
     * 
     * This method is used to set the type of the subject of the report
     * @param TType|string $typeSubject
     */
    public function setSubjectType(TType | string $typeSubject): void {
        $this->typeSubject = $typeSubject instanceof TType ? $typeSubject : TType::from($typeSubject);
    }
}