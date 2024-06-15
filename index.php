<?php


// inclusione per configurazione e creazione oggetto smarty
require('StartSmarty.php');

$smarty = StartSmarty::configuration();

$smarty->display('svuota.tpl');
