<?php

class EAccommodation
{
    private int $idVisit;
    private DateTime $day;
    //Perchè non includere time dentro day? 
    //La classe DateTime contiene sia una data che un orario

    //Stessa cosa qui per le chiavi esterne..
    //Alla fine dobbiamo avere un array dentro lo studente con le visite o manteniamo solo l'id dello studente?
    private int $idAccommodation;
}