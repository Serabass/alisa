<?php

abstract class Alisa {
  private static $instance;
  public static function instance() {
    return static::$instance ?? static::$instance = new static();      
  }

  public $data;
  public $commands = [];
  public $historyCommands = [];
  public $dumpToTG = true;
  private $reflectionClass;

  public $historyStack = [];

  public abstract function hello();
  public abstract function otherwise();

  private function updateHistory() {
      if (empty($_SESSION['history'])) {
        $_SESSION['history'] = [];
      }
      
      $_SESSION['history'] = $this->historyStack;
  }

  public function findCommandByText($text) {
    foreach ($this->commands as $command) {
      if (in_array($text, $command['commands'])) {
        return $command;
      }
    }

    return null;
  }

  private function fixCallbackResult($result) {
    if ($result instanceof AlisaResponse) {
        $result = $result->toArray();
    }

    if (is_string($result)) {
        $result = response()->text($result)->toArray();
    }

    return $result;
  }

  public function process() {
    /**
     * Получаем что конкретно спросил пользователь
     */
    $text = $this->data['request']['command'];

    /**
     * Приводим на всякий случай запрос пользователя к нижнему регистру
     */
    $textToCheck = preg_replace('/\./i', '', $text);
    $textToCheck = function_exists('mb_strtolower') ? mb_strtolower($textToCheck) : strtolower($textToCheck);

    if (empty($textToCheck)) {
        $hello = $this->reflectionClass->getMethod('hello');
        $result = $hello->invoke($this);
        return $this->fixCallbackResult($result);
    } else {
        $command = $this->findCommandByText($textToCheck);
        if (empty($command)) {
            $methods = $this->reflectionClass->getMethods();
            $methods = array_filter($methods, function ($method) use ($textToCheck) {
                return count($method->getAttributes(WhenHistory::class)) > 0;
            });

            $methods = array_values($methods);

            if (count($methods) > 0) {
                $first = $methods[0];
                $result = $first->invoke($this, $textToCheck);
                return $this->fixCallbackResult($result);
            }

            return $this->fixCallbackResult($this->otherwise());
        }

        $result = $command['callback']->invoke($this);
        return $this->fixCallbackResult($result);
    }
  }

    public function when(...$commands) {
        $callback = array_pop($commands);
        $this->commands[] = compact('commands', 'callback');
        return $this;
    }

    public function whenHistory($historyId, $callback) {
        $this->historyCommands[] = compact('historyId', 'callback');
        return $this;
    }

    public function dumpToTG($value = true) {
        $this->dumpToTG = $value;
        return $this;
    }

    public function pushHistory($historyId) {
        $this->historyStack[] = $historyId;
        $this->updateHistory();
        return $this;
    }

    public function checkHistory($historyId) {
        return in_array($historyId, $this->historyStack);
    }
    
    public function clearHistory() {
        $this->historyStack = [];
        $this->updateHistory();
    }
    
    public function popHistory($historyId) {
        $this->historyStack = array_filter(
            $this->historyStack,
            fn ($item) => $item != $historyId
        );
        $this->updateHistory();
        return $this;
    }
    
  public function init() {
    $dataRow = file_get_contents('php://input');
    $this->data = json_decode($dataRow, true);

    file_put_contents('alisalog.txt', date('Y-m-d H:i:s') . PHP_EOL . $dataRow . PHP_EOL, FILE_APPEND);

    if (!isset(
        $this->data['request'],
        $this->data['request']['command'],
        $this->data['session'],
        $this->data['session']['session_id'],
        $this->data['session']['message_id'],
        $this->data['session']['user_id']
    )
    ) {
        return [];
    }

    session_id($this->data['session']['session_id']);
    session_start();

    $this->historyStack = $_SESSION['history'];

    $this->reflectionClass = new ReflectionClass(static::class);
    $listeners = [];

    $whenMethod = $this->reflectionClass->getMethod('when');
    $whenHistoryMethod = $this->reflectionClass->getMethod('whenHistory');

    foreach ($this->reflectionClass->getMethods() as $method) {
      $whenAttributes = $method->getAttributes(When::class);

      if (count($whenAttributes) > 0) {
        foreach ($whenAttributes as $attribute) {
            $when = $attribute->newInstance();
            $args = [];
  
            foreach ($when->commands as $command) {
                $args[] = $command;
            }
  
            $whenMethod->invokeArgs($this, [...$args, $method]);
        }
      }

      $whenHistoryAttributes = $method->getAttributes(WhenHistory::class);
      if (count($whenHistoryAttributes) > 0) {
        foreach ($whenHistoryAttributes as $attribute) {
            $whenHistory = $attribute->newInstance();
            $whenHistoryMethod->invokeArgs($this, [$whenHistory->historyId, $method]);
        }
      }
    }

    try {
        $data = [
            'version' => '1.0',
            'session' => [
                'session_id' => $this->data['session']['session_id'],
                'message_id' => $this->data['session']['message_id'],
                'user_id' => $this->data['session']['user_id']
            ],
            'response' => $this->process(),
            'data' => [
                's' => $_SESSION
            ]
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch(\Exception $e) {
        header('Content-Type: application/json'); 
        echo '["Error occured"]';
    }
  }
}
