<?php

include_once 'alisa.php';
include_once 'attributes.php';

class TownsAlisa extends Alisa {
  public const TOWNS_HISTORY = 'towns_history';

  public $towns = [
    'Алматы',
    'Астана',
    'Москва',
    'Питер',
    'Рязань',
  ];

  public function hello() {
    $this->pushHistory(self::TOWNS_HISTORY);
    return response()
      ->text('Привет! Я Алиса! Играем в города')
      ->button('помощь');
  }

  public function otherwise() {
    return response()
      ->text('Я не знаю такой команды. Скажите помощь или попробуйте другую команду.');
  }
  
  #[When('помощь', 'хелп', 'помоги')]
  public function help() {
    return response()
      ->text('Вы называете город. Я называю другой на последнюю букву. Для выхода скажите "хватит"')
    ;
  }

  private function getNextTownByLastChar($town) {
    $town = mb_strtolower($town);
    $lastChar = mb_substr($town, -1);
    $result = null;
    
    foreach ($this->towns as $t) {
      if (mb_strtolower(mb_substr($t, 0, 1)) == mb_strtolower($lastChar)) {
        $result = $t;
        break;
      }
    }

    return $result;
  }

  #[WhenRegex('/^(\w+)$/iu')]
  public function towns($town) {
    $this->pushHistoryData(self::TOWNS_HISTORY, $town);
    return $this->getNextTownByLastChar($town);
  }
}
