<?php
interface StatusEnum extends \BackedEnum {}
enum TStatusContract:string implements StatusEnum 
{
    //refers to contract
    case FUTURE = 'future';
    case ONGOING = 'onGoing';
    case FINISHED = 'finished';
}
enum TStatusSupport:int implements StatusEnum
{
    case WAITING = 1;
    case RESOLVED = 0;
}