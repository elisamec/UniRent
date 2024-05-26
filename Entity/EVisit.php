<?php

class EVisit
{
    private int $idVisit;
    private DateTime $day;
    //Perchè non includere time dentro day? (La classe DateTime contiene sia una data che un orario)
    //Dobbiamo avere un array dentro lo studente con le visite o manteniamo solo l'id dello studente?
    private int $idAccommodation;
}