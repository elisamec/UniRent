<?php
namespace Classes\Tools;
interface StatusSupportEnum extends \BackedEnum {}

enum TStatusSupport:int implements StatusSupportEnum
{
    case WAITING = 0;
    case RESOLVED = 1;
}