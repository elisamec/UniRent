<?php
interface ErrorEnum extends \BackedEnum {}
enum TErrorDuplicate:string implements ErrorEnum
{
    case USERNAME = 'Username already exists';
    case EMAIL = 'Email already used';
    case IBAN  = 'IBAN already used';
    case PHONENUMBER  = 'Phone number already used';
}