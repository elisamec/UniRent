<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';

use StartSmarty;

class VUser{
    private $smarty;

    public function __construct(){

        $this->smarty = StartSmarty::configuration();
    }

    public function home(){

        $this->smarty->display('User/home.tpl');
    }

    public function login(){

        $this->smarty->display('User/login.tpl');
    }
    public function about(){

        $this->smarty->display('User/about.tpl');
    }
    public function contact(){

        $this->smarty->display('User/contact.tpl');
    }
    public function findAccommodation(){

        $this->smarty->display('User/search.tpl');
    }

    public function register(){

        $this->smarty->display('User/register.tpl');
    }

    //Deve rimandare alla home dicendo che c'è stato un errore
    //L'errore potrebbe essere dovuto a username o email già presenti nel database
    public function registrationError(bool $mailDuplicateError, bool $userDuplicateError, bool $studentMailError, bool $passwordFormatError){
        $this->smarty->assign('userDuplicateError', $userDuplicateError);
        $this->smarty->assign('studentMailError', $studentMailError);
        $this->smarty->assign('passwordFormatError', $passwordFormatError);
        $this->smarty->assign('mailDuplicateError', $mailDuplicateError);
        $this->smarty->display('User/register.tpl');
    }

    //Deve rimandare alla home dicendo che c'è stato un errore nel login
    //"Username or password not correct"
    public function loginError(bool $passwordError, bool $usernameError, bool $usernameBanned){
        $this->smarty->assign('usernameError', $usernameError);
        $this->smarty->assign('passwordError', $passwordError);
        $this->smarty->assign('usernameBanned', $usernameBanned);
        $this->smarty->display('User/login.tpl');
    }
}