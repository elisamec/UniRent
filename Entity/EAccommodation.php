<?php

class EAccommodation
{
    private int $idAccommodation;
    private array $photo;
    private string $title;
    private array $address;
    private int $price;
    private DateTime $start;
    private String $description;
    private float $deposit;
    private array $visit;
    private bool $man;
    private bool $woman;
    private bool $pets;
    private bool $smokers;
    private int $idOwner;

    private static $entity = EVisit::class;

    
}