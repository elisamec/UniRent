<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';

use StartSmarty;

class VOwner {
    private $smarty;

    public function __construct() {
        $this->smarty = StartSmarty::configuration();
    }

    //Mostra la seconda parte della registrazione proprietario
    public function showOwnerRegistration(){
    
    }
    
}
