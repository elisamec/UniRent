<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Entity\EAccommodation;
use Classes\Entity\EOwner;
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
        $ph = $student->getPicture()->getPhoto();

        $base64 = base64_encode($ph);
        $imageSrc = "data:" . 'image/jpeg' . ";base64," . $base64;
        $this->smarty->assign('student', $student);
        $this->smarty->assign('photo', $imageSrc);
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
    public function accommodation(EAccommodation $accomm, EOwner $owner, array $reviewsData){
        $photos=json_encode($accomm->getPhoto());
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('imagesJson', $photos);
        $this->smarty->assign('accommodation', $accomm);
        $this->smarty->assign('owner', $owner);
        $this->smarty->display('Student/accommodation.tpl');
    }
    public function reviews(array $reviewsData){
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Student/reviews.tpl');
    }
    public function publicProfileStudent(EStudent $student, array $reviewsData){
        $this->smarty->assign('student', $student);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Student/publicProfileStudent.tpl');
    }
    public function publicProfileOwner(EOwner $owner, array $reviewsData){
        $this->smarty->assign('owner', $owner);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Student/publicProfileOwner.tpl');
    }
    public function findAccommodation(){
        $this->smarty->display('Student/search.tpl');
    }
}
