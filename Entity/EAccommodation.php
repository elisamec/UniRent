<?php

class EAccommodation
{
    private int $idAccommodation;
    // Array di foto
    private string $title;
    //Indirizzo di classe ?
    private int $price;
    private DateTime $start;
    private String $Description;
    private float $deposit;
    //Array con giorni e orari disponibili
    //durata di tipo time
    private bool $man;
    private bool $woman;
    private bool $pets;
    private bool $smokers;
    //Ci devo mettere i riferimento all'owner?
    //Penso che l'owner doverebbe avere un array con tutti gli appartamenti...

}