<?php

require __DIR__ . '/vendor/autoload.php';

use CommerceGuys\Addressing\Address;
use CommerceGuys\Addressing\AddressFormat\AddressFormatRepository;

// Creazione di un oggetto Address
$address = new Address();
$address = $address->withCountryCode('US')
                   ->withAdministrativeArea('CA')
                   ->withLocality('Los Angeles')
                   ->withPostalCode('90001')
                   ->withAddressLine1('123 Main St');

echo $address->getCountryCode(); // Output: US