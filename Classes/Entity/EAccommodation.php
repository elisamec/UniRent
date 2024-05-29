<?php

require __DIR__ . '/../../vendor/autoload.php';

use CommerceGuys\Addressing\Address;
use CommerceGuys\Addressing\Formatter\DefaultFormatter;
use CommerceGuys\Addressing\Repository\AddressFormatRepository;
use CommerceGuys\Addressing\Repository\SubdivisionRepository;
use CommerceGuys\Addressing\Repository\CountryRepository;

class EAccommodation
{
    private ?int $idAccommodation;
    private array $photo;
    private string $title;
    private Address $address;
    private float $price;
    private DateTime $start;    //Per aggiungere un oggetto: new DateInterval('PT2H30M');
                                    //Per stampare: echo $interval->format('%H:%I:%S');
    private ?String $description;
    private float $deposit;
    private array $visit;
    private bool $man;
    private bool $woman;
    private bool $pets;
    private bool $smokers;
    private int $idOwner;

    private static $entity = EVisit::class;

    public function __construct(?int $idAccommodation, array $photo, string $title, Address $address, float $price,
                                DateTime $start, ?String $description, float $deposit, array $visit, bool $man,
                                bool $woman, bool $pets, bool $smokers, int $idOwner){

        $this->idAccommodation = $idAccommodation;
        $this->photo = $photo;
        $this->title = $title;
        $this->address = $address;
        $this->price = $price;
        $this->start = $start;
        $this->description = $description;
        $this->deposit = $deposit;
        $this->visit = $visit;
        $this->man = $man;
        $this->woman = $woman;
        $this->pets = $pets;
        $this->smokers = $smokers;
        $this->idOwner = $idOwner;
    }

    public function getIdAccommodation(): ?int{
        return $this->idAccommodation;
    }

    public function getPhoto(): array{
        return $this->photo;
    }

    public function getTitle(): string{
        return $this->title;
    }   

    public function getAddress(): Address{
        return $this->address;
    }

    public function getPrice(): float{
        return $this->price;
    }

    public function getStart(): DateTime{
        return $this->start;
    }

    public function getDescription(): ?String{
        return $this->description;
    }   

    public function getDeposit(): float{
        return $this->deposit;
    }   

    public function getVisit(): array{
        return $this->visit;
    }

    public function getMan(): bool{
        return $this->man;
        
    }

    public function getWoman(): bool{
        return $this->woman;
    }

    public function getPets(): bool{
        return $this->pets;
    }

    public function getSmokers(): bool{
        return $this->smokers;
    }

    public function getIdOwner(): int{
        return $this->idOwner;
    }

    public function setIdAccommodation(int $idAccommodation): void{
        $this->idAccommodation = $idAccommodation;
    }

    public function setPhoto(array $photo): void{
        $this->photo = $photo;
    }

    public function setTitle(string $title): void{
        $this->title = $title;
    }

    public function setAddress(Address $address): void{
        $this->address = $address;
    }

    public function setPrice(float $price): void{
        $this->price = $price;
    }

    public function setStart(DateTime $start): void{
        $this->start = $start;
    }

    public function setDescription(String $description): void{
        $this->description = $description;
    }

    public function setDeposit(float $deposit): void{
        $this->deposit = $deposit;
    }

    public function setVisit(array $visit): void{
        $this->visit = $visit;
    }

    public function setMan(bool $man): void{
        $this->man = $man;
    }

    public function setWoman(bool $woman): void{
        $this->woman = $woman;
    }

    public function setPets(bool $pets): void{
        $this->pets = $pets;
    }       

    public function setSmokers(bool $smokers): void{
        $this->smokers = $smokers;
    }

    public function setIdOwner(int $idOwner): void{
        $this->idOwner = $idOwner;
    }


    public function __toString():string{
        
        $address = $this->address->getAddressLine1() . " " . $this->address->getLocality() . " " . $this->address->getAdministrativeArea() . " " . $this->address->getCountryCode() . " " . $this->address->getPostalCode();
        $start = $this->start->format('Y-m-d');
        return "ID: $this->idAccommodation  \n".
                "Photo: $this->photo  \n".
                "Title: $this->title  \n".
                "Address: $address  \n".
                "Price: $this->price  \n".
                "Start: $start  \n".
                "Description: $this->description  \n".
                "Deposit: $this->deposit  \n".
                "Visit: $this->visit  \n".
                "Man: $this->man \n".
                "Woman: $this->woman \n".
                "Pets: $this->pets \n".
                "Smokers: $this->smokers \n".
                "ID Owner: $this->idOwner \n";
    }
  
}