<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';
use StartSmarty;

class VOwner {
    private $smarty;

    public function __construct() {
        $this->smarty = StartSmarty::configuration();
    }

    //Mostra la home del proprietario
    public function home() {
        $this->smarty->display('Owner/homeOwner.tpl');
    }

    //Mostra la seconda parte della registrazione proprietario
    public function showOwnerRegistration(){
        $this->smarty->display('Owner/registerOwner.tpl');
    }
    
}
