<?php
namespace Classes\Tools;
interface StatusEnum extends \BackedEnum {}

enum TStatusSupport:int implements StatusEnum
{
    case WAITING = 1;
    case RESOLVED = 0;
}