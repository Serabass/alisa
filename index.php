<?php

include_once 'alisa.php';
include_once 'response.php';
include_once 'monologue.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

Alisa::instance()
    // ->dumpToTG()
    ->hello(function () {
        return response()
            ->text('Привет! Я Алиса!')
            ->button('помощь')
        ;
    })
    ->when('помощь', 'хелп', 'помоги', function () {
        return response()
            ->text('Для выхода скажите "Алиса хватит"')
            ->button('хватит')
        ;
    })
    ->when('расскажи историю', function () {
        return response()
            ->text('Бежит как-то ёжик по травке и хохочет')
        ;
    })
    ->when('хватит', 'выход', 'стоп', function () {
        return response()
            ->text('Приятного дня')
            ->endSession()
        ;
    })
    ->when('монолог', function () {
        $result = Monologue::instance(true)
            ->getRandomSentence();

        return response()
            ->text($result)
            ->endSession()
        ;
    })
    ->when('монолог без цензуры', function () {
        $result = Monologue::instance(false)
            ->getRandomSentence();

        return response()
            ->text($result)
            ->endSession()
        ;
    })
    ->otherwise(function () {
        return response()
            ->text('Я не знаю такой команды. Напишите мне помощь или попробуйте другую команду.')
        ;
    })
    ->init()
;
