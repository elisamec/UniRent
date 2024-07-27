<?php
namespace Classes\Tools;
interface StatusSupportEnum extends \BackedEnum {}

enum TStatusSupport:int implements StatusSupportEnum
{
    case WAITING = 1;
    case RESOLVED = 0;
}