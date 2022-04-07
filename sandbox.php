<?php

include_once 'common.php';
include_once 'instance.php';
include_once 'clear.php';
include_once 'toobject.php';

$array = [
  'session' => [
      'message_id' => 0,
      'session_id' => '4170d545-d0af-4b3b-b579-6bbbcb40d47e',
      'user_id' => 'DF8BD106EA0BDD8F45EF8CE67D5168C5E6B9FC3769B0B3C277F6FCF018632767'
  ],
  'request' => [
      'command' => 'сова'
  ]
];

$result = SampleAlisa::instance(ToObject($array))
  ->init();

var_dump($result);
