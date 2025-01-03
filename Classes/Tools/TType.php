<?php
namespace Classes\Tools;
interface TypeEnum extends \BackedEnum {}
enum TType: string implements TypeEnum
{
    case STUDENT = 'student';
    case ACCOMMODATION = 'accommodation';
    case OWNER = 'owner';
    case REVIEW = 'review';
}
