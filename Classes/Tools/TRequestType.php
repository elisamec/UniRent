<?php
namespace Classes\Tools;
interface RequestType extends \BackedEnum {}

enum TRequestType:string implements RequestType 
{
    case REGISTRATION = 'register';
    case USAGE = 'appUse';
    case BUG = 'bug';
    case OTHER = 'other';
    case REMOVEBAN = 'removeBanRequest';
}