<?php
namespace Classes\Tools;
interface StatusUserEnum extends \BackedEnum {}

enum TStatusUser:string implements StatusUserEnum
{
    case ACTIVE = 'active';
    case BANNED = 'banned';
    case REPORTED = 'reported';
}