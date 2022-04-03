<?php

include_once 'instance.php';
include_once 'response.php';
include_once 'monologue.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

SampleAlisa::instance()->init();

    // ->when('помощь', 'хелп', 'помоги', fn () => response()
    //     ->text('Для выхода скажите "хватит"')
    //     ->button('хватит')
    //     ->button('стопэ')
    //     ->button('хорош')
    //     ->button('стоп')
    //     ->button('харэ')
    // )

    // ->when('расскажи историю', fn () => response()
    //     ->text('Бежит как-то ёжик по травке и хохочет')
    // )

    // ->when('алиса хватит', 'хватит', 'выход', 'стоп', 'стопэ', 'харэ', 'хорош', fn () => response()
    //     ->text('Приятного дня')
    //     ->endSession()
    // )

    // ->when('монолог', fn () => response()
    //     ->text(
    //         Monologue::instance(true)->getRandomSentence()
    //     )
    // )

    // ->when('монолог без цензуры', fn () => response()
    //     ->text(
    //         Monologue::instance(false)->getRandomSentence()
    //     )
    // )

    // ->otherwise(fn () => response()
    //     ->text('Я не знаю такой команды. Скажите помощь или попробуйте другую команду.')
    // )

    // ->init()
;
