<?php
error_reporting(E_ALL);
function increment(&$var)
{
    $var++;
}

$a = 0;
//call_user_func('increment', $a);
echo $a."\n";

call_user_func_array('increment', array(&$a)); // You can use this instead before PHP 5.3
echo $a."\n";
?> 