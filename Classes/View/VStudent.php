<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';

use StartSmarty;

class VStudent{
    private $smarty;

    public function __construct(){

        $this->smarty = StartSmarty::configuration();
    }

    public function home(){

        $this->smarty->display('homeStudent.tpl');
    }

    //Mostra la seconda parte della registrazione studente
    public function showStudentRegistration(){
    
    }

}
