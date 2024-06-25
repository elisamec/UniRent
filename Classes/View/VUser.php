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

        $this->smarty->display('home.tpl');
    }

    public function login(){

        $this->smarty->display('login.tpl');
    }
    public function about(){

        $this->smarty->display('about.tpl');
    }
    public function contact(){

        $this->smarty->display('contact.tpl');
    }

    public function register(){

        $this->smarty->display('register.tpl');
    }

    //Deve rimandare alla home dicendo che c'è stato un errore
    //L'errore potrebbe essere dovuto a username o email già presenti nel database
    public function reggistrationError(){
    }

    //Deve rimandare alla home dicendo che c'è stato un errore nel login
    //"Username or password not correct"
    public function loginError(){

    }
}