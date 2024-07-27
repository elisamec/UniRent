<?php
namespace Classes\Tools;
interface StatusContractEnum extends \BackedEnum {}

enum TStatusContract:string implements StatusContractEnum 
{
    //refers to contract
    case FUTURE = 'future';
    case ONGOING = 'onGoing';
    case FINISHED = 'finished';
}
