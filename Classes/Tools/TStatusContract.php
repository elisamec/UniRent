<?php
namespace Classes\Tools;
interface StatusEnum extends \BackedEnum {}

enum TStatusContract:string implements StatusEnum 
{
    //refers to contract
    case FUTURE = 'future';
    case ONGOING = 'onGoing';
    case FINISHED = 'finished';
}
