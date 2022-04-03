<?php

class Alisa {
  private static $instance;
  public static function instance() {
      return self::$instance ?? self::$instance = new Alisa();    
  }

  public $data;
  public $commands = [];
  public $otherwiseCallback;
  public $helloCallback;
  public $dumpToTG = true;

  public function findCommandByText($text) {
    foreach ($this->commands as $command) {
      if (in_array($text, $command['commands'])) {
        return $command;
      }
    }

    return null;
  }

  public function process() {
    if (!isset($this->data['request'], $this->data['request']['command'], $this->data['session'], $this->data['session']['session_id'], $this->data['session']['message_id'], $this->data['session']['user_id'])) {
        /**
         * Нет всех необходимых полей. Не понятно, что вернуть, поэтому возвращаем ничего.
         */
        return [];
    }
    /**
     * Получаем что конкретно спросил пользователь
     */
    $text = $this->data['request']['command'];

    session_id($this->data['session']['session_id']); // В Чате спрашивали неодногравтно как использовать сессии в навыке - показываю
    session_start();

    /**
     * Приводим на всякий случай запрос пользователя к нижнему регистру
     */
    $textToCheck = preg_replace('/\./i', '', $text);
    $textToCheck = mb_strtolower($textToCheck);

    if (empty($textToCheck)) {
        $cb = $this->helloCallback;
        return $cb();
    } else {
        $command = $this->findCommandByText($textToCheck);

        if (empty($command)) {
            $cb = $this->otherwiseCallback;
            $result = $cb();

            if ($result instanceof AlisaResponse) {
                $result = $result->toArray();
            }

            return $result;
        }

        return $command['callback']();
    }
  }

  public function when(...$commands) {
      $callback = array_pop($commands);
      $this->commands[] = compact('commands', 'callback');
      return $this;
  }

  public function hello($callback) {
      $this->helloCallback = $callback;
      return $this;
  }

  public function otherwise($callback) {
      $this->otherwiseCallback = $callback;
      return $this;
  }

  public function dumpToTG($value = true) {
      $this->dumpToTG = $value;
      return $this;
  }

  public function init() {
      $dataRow = file_get_contents('php://input');
      $this->data = json_decode($dataRow, true);

      file_put_contents('alisalog.txt', date('Y-m-d H:i:s') . PHP_EOL . $dataRow . PHP_EOL, FILE_APPEND);

      try {
          $data = [
              'version' => '1.0',
              'session' => [
                  'session_id' => $this->data['session']['session_id'],
                  'message_id' => $this->data['session']['message_id'],
                  'user_id' => $this->data['session']['user_id']
              ],
              'response' => $this->process()
          ];
          header('Content-Type: application/json');
          echo json_encode($data);
      } catch(\Exception $e) {
          header('Content-Type: application/json'); 
          echo '["Error occured"]';
      }
  }
}
