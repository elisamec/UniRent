<?php
namespace Classes\Tools;
interface StatusEnum extends \BackedEnum {}

enum TStatusUser:string implements StatusEnum
{
    case ACTIVE = 'active';
    case BANNED = 'banned';
    case REPORTED = 'reported';
}