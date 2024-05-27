<?php

class VOwner {
    private $smarty;

    public function __construct() {
        $this->smarty = StartSmarty::configuration();
    }
    
}
