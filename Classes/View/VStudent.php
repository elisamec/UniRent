<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';

use StartSmarty;
use Classes\Entity\EStudent;

class VStudent{
    private $smarty;

    public function __construct(){

        $this->smarty = StartSmarty::configuration();
    }

    public function home(){

        $this->smarty->display('homeStudent.tpl');
    }
    public function profile(EStudent $student){
        $this->smarty->assign('student', $student);
        $this->smarty->display('personalProfileStudent.tpl');
    }
    public function editProfile(EStudent $student){
        $this->smarty->assign('student', $student);
        $this->smarty->display('editPersonalProfileStudent.tpl');
    }

    //Mostra la seconda parte della registrazione studente
    public function showStudentRegistration(){
        $this->smarty->display('registerStudent.tpl');
    }

}
