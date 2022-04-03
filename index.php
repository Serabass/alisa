<?php

include_once 'alisa.php';
include_once 'response.php';
include_once 'monologue.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

Alisa::instance()
    // ->dumpToTG()
    ->hello(fn () => response()
        ->text('Привет! Я Алиса!')
        ->button('помощь')
    )

    ->when('помощь', 'хелп', 'помоги', fn () => response()
        ->text('Для выхода скажите "хватит"')
        ->button('хватит')
        ->button('хватит1')
        ->button('хватит2')
    )

    ->when('расскажи историю', fn () => response()
        ->text('Бежит как-то ёжик по травке и хохочет')
    )

    ->when('алиса хватит', 'хватит', 'выход', 'стоп', 'стопэ', 'харэ', fn () => response()
        ->text('Приятного дня')
        ->endSession()
    )

    ->when('монолог', fn () =>  response()
        ->text(
            Monologue::instance(true)->getRandomSentence()
        )
    )

    ->when('монолог без цензуры', fn () => response()
        ->text(
            Monologue::instance(false)->getRandomSentence()
        )
    )

    ->otherwise(fn () => response()
        ->text('Я не знаю такой команды. Скажите помощь или попробуйте другую команду.')
    )
    ->init()
;
