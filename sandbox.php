<?php

// clear the console
echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J  

include_once 'response.php';

preg_match('/^скажи (?P<word>\w+) (?P<count>\d+) раза?$/iu', 'скажи фью 2 раза', $matches);

var_dump($matches);
