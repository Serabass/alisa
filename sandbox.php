<?php

// clear the console
echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J  

include_once 'response.php';

var_dump(
  response()
    ->text('Привет! Я Алиса!')
    ->button('помощь')
);
