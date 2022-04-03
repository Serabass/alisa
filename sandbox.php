<?php

echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J  

include_once 'response.php';

var_dump(
    response()
        ->text('Для выхода скажите "хватит"')
        ->button('хватит')
        ->button('стопэ')
        ->button('хорош')
        ->button('стоп')
        ->button('харэ')
        ->endSession()
);
