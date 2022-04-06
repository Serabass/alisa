<?php

include_once 'alisa.php';
include_once 'sova.php';
include_once 'attributes.php';
include_once 'monologue.php';

class SampleAlisa extends Alisa {
  public const TOWNS_HISTORY = 'towns_history';
 
  public function hello() {
    return response()
      ->text('Привет! Я Алиса!');
  }

  public function otherwise() {
    return response()
      ->text('Моя твоя не понимать.');
  }
  
  #[When('помощь', 'хелп', 'помоги')]
  public function help() {
    return response()
      ->text('Для выхода скажите "хватит"')
      ->buttons(
        'хватит',
        'стопэ',
        'хорош',
        'стоп',
        'харэ',
      );
  }

  #[When('расскажи историю')]
  public function history() {
    return response()
      ->text('Бежит как-то ёжик по травке и хохочет');
  }

  #[When('алиса хватит', 'хватит', 'выход', 'стоп', 'стопэ', 'харэ', 'хорош')]
  public function quit() {
    $this->clearHistory();
    return endSession('Приятного дня');
  }

  #[When('монолог')]
  public function monologue() {
    return monologue(true)->getRandomSentence();
  }

  #[When('монолог без цензуры')]
  public function monologueUncensored() {
    return monologue(false)->getRandomSentence();
  }

  #[WhenRegex('/^скажи ([\w\s]+) (\d+) раза?$/iu')]
  public function repeat(string $word, int $count) {
    return join(' ', array_fill(0, $count, $word));
  }

  #[WhenRegex('/^свистни (\d+) раза?$/iu')]
  public function whistle(int $count) {
    return join(' ', array_fill(0, $count, 'фью'));
  }

  #[WhenRegex('/^(до|ре|ми|фа|соль|ля|си)[\s-](\d+)[\s-]октавы\.?$/iu')]
  public function notesExplain(string $name, int $octave) {
    return "Нота $name на $octave октаве";
  }

  #[WhenRegex('/^сколько букв в слове (\w+)$/iu')]
  public function wordLength(string $word) {
    return "В слове $word " . mb_strlen($word) . " букв";
  }

  #[When('поздоровайся с володей')]
  public function helloVolodya() {
    return "Здравствуй дедушка володя, боже что тут происходит?";
  }

  #[When('поздоровайся с димой')]
  public function helloDima() {
    return "Здравствуй дедушка агрипп, ты похож на вялый гриб";
  }

  #[When('поздоровайся с фаргатом')]
  public function helloFargat() {
    return "Здравствуй дедушка фаргат, самогонный аппарат";
  }

  #[When('поздоровайся с максом')]
  public function helloMax() {
    return "Здравствуйте о великий максим красно солнышко";
  }

  #[When('поздоровайся с костей')]
  public function helloKostya() {
    return "О, великий константин, в мире вы такой один. Ни мышонок, ни лягушка, а неведома зверушка";
  }

  #[When('сова')]
  public function sova() {
    return sova()->random();
  }

  // #[WhenRegex('/^скажи (?P<word>\w+) (?P<count>\d+) раза?$/iu')]
  // public function regexTest(string $word, int $count) {
  //   return join(' ', array_fill(0, $count, $word));
  // }
}
