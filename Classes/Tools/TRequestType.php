<?php
interface RequestType extends BackedEnum {}

enum TRequestType:string implements RequestType 
{
    case REGISTRATION = 'registration';
    case USAGE = 'appUse';
    case BUG = 'bug'; 
}