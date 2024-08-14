<?php
namespace Classes\Entity;
require __DIR__.'../../../vendor/autoload.php';

use Classes\Tools\TType;
use DateTime;

class EReport {
    private ?int $id;
    private string $description;
    private DateTime $made;
    private ?DateTime $banDate;
    private ?int $idSubject;
    private TType $typeSubject;

    public function __construct(?int $id=null, string $description, DateTime $made=new DateTime('today'), ?DateTime $banDate=null, int $idSubject, TType | string $typeSubject) {
        $this->id=$id;
        $this->description=$description;
        $this->made=$made;
        $this->banDate=$banDate;
        $this->idSubject=$idSubject;
        $this->typeSubject=$typeSubject instanceof TType ? $typeSubject : TType::from($typeSubject);
    }
    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function getMade(): DateTime {
        return $this->made;
    }

    public function getBanDate(): ?DateTime {
        return $this->banDate;
    }

    public function setBanDate(?DateTime $banDate): void {
        $this->banDate = $banDate;
    }

    public function getIdSubject(): int {
        return $this->idSubject;
    }

    public function setIdSubject(int $idSubject): void {
        $this->idSubject = $idSubject;
    }

    public function getSubjectType(): TType {
        return $this->typeSubject;
    }
    public function setSubjectType(TType | string $typeSubject): void {
        $this->typeSubject = $typeSubject instanceof TType ? $typeSubject : TType::from($typeSubject);
    }
    public function formatAdmin(?array $review, string $usernameSubject):array {
        return [
            'id'=>$this->getId(),
            'description'=>$this->getDescription(),
            'made'=>$this->getMade()->format('Y-m-d'),
            'banDate'=>$this->getBanDate()? $this->getBanDate()->format('Y-m-d') : null,
            'type' => $this->getSubjectType()->value,
            'usernameSubject' =>$usernameSubject,
            'review' => $review
        ];
    }
}