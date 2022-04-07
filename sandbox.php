<?php

include_once 'common.php';
include_once 'instance.php';
include_once 'clear.php';

$json = '{
  "meta": {
      "locale": "ru-RU",
      "timezone": "Asia/Almaty",
      "client_id": "aliced/1.0 (Yandex yandexmini; Linux 1.0)",
      "interfaces": {
          "payments": {},
          "account_linking": {}
      }
  },
  "session": {
      "message_id": 0,
      "session_id": "4170d545-d0af-4b3b-b579-6bbbcb40d47e",
      "skill_id": "2333aacc-2c69-4472-8540-6cfca23ef630",
      "user": {
          "user_id": "2A39887F6BE152F6D5A7E5C158ED9C431630C113F9EF120140E73D30BA4C6941"
      },
      "application": {
          "application_id": "DF8BD106EA0BDD8F45EF8CE67D5168C5E6B9FC3769B0B3C277F6FCF018632767"
      },
      "user_id": "DF8BD106EA0BDD8F45EF8CE67D5168C5E6B9FC3769B0B3C277F6FCF018632767",
      "new": true
  },
  "request": {
      "command": "сова",
      "original_utterance": "",
      "nlu": {
          "tokens": [],
          "entities": [],
          "intents": {}
      },
      "markup": {
          "dangerous_context": false
      },
      "type": "SimpleUtterance"
  },
  "version": "1.0"
}';

$array = json_decode($json, false);

$result = SampleAlisa::instance($array)
  ->init();

var_dump($result);
