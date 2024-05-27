<?php
interface TypeEnum extends \BackedEnum {}
enum Type: string implements TypeEnum
{
    case STUDENT = 'student';
    case ACCOMMODATION = 'accommodation';
    case OWNER = 'owner';
}
