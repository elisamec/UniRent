<?php
$array_banned = array('gmail.com','yahoo.com','ymail.com');
$array_white  = array('mrdoe@gmail.com');
$domain = substr(strrchr($email, "@"), 1); #strrchr : restituisce una stringa da quella word in poi, substr:restituisce la stringa da quell' indice indicato in poi ( toglie la @ in pratica)
if(!in_array($domain,$array_banned) || in_array($domain, $array_white)) 
{
  // OK, your code here
} 
else 
{
  echo 'Sorry, your email address has been excluded. If you want to use your email address please contact admin';
  // so you can insert requested mail in the white-listed array
}