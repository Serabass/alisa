<?php

include_once 'common.php';
include_once 'clear.php';

include_once 'history/history-base.php';

$history = new JSONHistory();

$history->put('Привет! Я Алиса!');

var_dump($history->all());
