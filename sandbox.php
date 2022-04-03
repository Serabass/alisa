<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$commands = ['1', '2', '3', []];

$callback = array_pop($commands);

var_dump(compact('commands', 'callback')); die;

$commands[] = compact('commands', 'callback');
