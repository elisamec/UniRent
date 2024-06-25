<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Entity\EAccommodation;
use StartSmarty;
use Classes\Entity\EStudent;

class VStudent{
    private $smarty;

    public function __construct(){

        $this->smarty = StartSmarty::configuration();
    }

    public function home(){

        $this->smarty->display('Student/home.tpl');
    }
    public function profile(EStudent $student){
        $this->smarty->assign('student', $student);
        $this->smarty->display('Student/personalProfile.tpl');
    }
    public function editProfile(EStudent $student){
        $this->smarty->assign('student', $student);
        $this->smarty->display('Student/editPersonalProfile.tpl');
    }

    //Mostra la seconda parte della registrazione studente
    public function showStudentRegistration(){
        $this->smarty->display('Student/register.tpl');
    }
    public function contact(){
        $this->smarty->display('Student/contact.tpl');
    }
    public function about(){
        $this->smarty->display('Student/about.tpl');
    }
    public function search(){
        $this->smarty->display('Student/search.tpl');
    }
    public function accommodation(EAccommodation $accomm){
        $photos=json_encode($accomm->getPhoto());
        $this->smarty->assign('images', $photos);
        $this->smarty->assign('accommodation', $accomm);
        $this->smarty->display('Student/accommodation.tpl');
    }

}
