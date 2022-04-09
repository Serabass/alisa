<?php

include_once 'input.php';
include_once 'session.php';
include_once 'str.php';
include_once 'response.php';

abstract class Alisa
{
    protected static $instance;
    public static function instance($json = null)
    {
        return static::$instance ?? static::$instance = new static($json);
    }

    protected $data;
    protected $commands = [];
    protected $historyCommands = [];
    protected $regexes = [];
    protected $dumpToTG = true;
    protected $reflectionClass;

    protected $historyStack = [];

    public abstract function hello();
    public abstract function otherwise();

    public function __construct($json = null)
    {
        $this->data = $json ?? Input::json();
        $this->reflectionClass = new ReflectionClass($this);
    }

    protected function updateHistory()
    {
        $this->data['name'] = 1;
        if (empty($this->session->history)) {
            $this->session->history = [];
        }

        $this->session->history = $this->historyStack;
    }

    protected function findCommandByText($text)
    {
        foreach ($this->commands as $command) {
            if (in_array($text, $command['commands'])) {
                return $command;
            }
        }

        return null;
    }

    protected function findRegexByText($text)
    {
        foreach ($this->regexes as $regex) {
            if (preg_match($regex['regex'], $text)) {
                return $regex;
            }
        }

        return null;
    }

    protected function fixCallbackResult($result)
    {
        if ($result instanceof AlisaResponse) {
            $result = $result->toArray();
        }

        if (is_string($result)) {
            $result = response()
                ->text($result)
                ->tts($result)
                ->toArray();
        }

        return $result;
    }

    protected function process()
    {
        /**
         * Получаем что конкретно спросил пользователь
         */
        $text = $this->data->request->command;

        /**
         * Приводим на всякий случай запрос пользователя к нижнему регистру и сносим точку в конце (иногда она её ставит)
         */
        $textToCheck = preg_replace('/\./i', '', $text);
        $textToCheck = Str::toLower($textToCheck);

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

                $regex = $this->findRegexByText($textToCheck);

                if ($regex) {
                    if (preg_match($regex['regex'], $text, $matches)) {
                        array_shift($matches);
                        $result = $regex['callback']->invokeArgs($this, $matches);
                        return $this->fixCallbackResult($result);
                    }
                }

                return $this->fixCallbackResult($this->otherwise());
            }

            $result = $command['callback']->invoke($this);
            return $this->fixCallbackResult($result);
        }
    }

    public function when(...$commands)
    {
        $callback = array_pop($commands);
        $this->commands[] = compact('commands', 'callback');
        return $this;
    }

    public function whenHistory($historyId, $callback)
    {
        $this->historyCommands[] = compact('historyId', 'callback');
        return $this;
    }

    public function whenRegex($regex, $callback)
    {
        $this->regexes[] = compact('regex', 'callback');
        return $this;
    }

    public function dumpToTG($value = true)
    {
        $this->dumpToTG = $value;
        return $this;
    }

    public function pushHistory($historyId)
    {
        $this->historyStack[$historyId] = [];
        $this->updateHistory();
        return $this;
    }

    protected function pushHistoryData($historyId, $data)
    {
        $this->historyStack[$historyId][] = $data;
        $this->updateHistory();
        return $this;
    }

    protected function checkHistory($historyId)
    {
        return isset($this->historyStack[$historyId]);
    }

    protected function clearHistory()
    {
        $this->historyStack = [];
        $this->updateHistory();
    }

    protected function popHistory($historyId)
    {
        $this->historyStack = array_filter(
            $this->historyStack,
            fn ($item) => $item != $historyId
        );
        $this->updateHistory();
        return $this;
    }

    public function meta()
    {
        $result = [];

        foreach ($this->reflectionClass->getMethods() as $method) {
            $whenAttributes = $method->getAttributes(When::class);
            if (count($whenAttributes) > 0) {
                foreach ($whenAttributes as $attribute) {
                    $when = $attribute->newInstance();
                    $result[] = join(', ', $when->commands);
                }
            }
            $whenAttributes = $method->getAttributes(WhenRegex::class);
            if (count($whenAttributes) > 0) {
                foreach ($whenAttributes as $attribute) {
                    $when = $attribute->newInstance();
                    $result[] = $when->regex;
                }
            }
        };

        return $result;
    }

    public function init()
    {
        if (!isset(
            $this->data->request,
            $this->data->request->command,
            $this->data->session,
            $this->data->session->session_id,
            $this->data->session->message_id,
            $this->data->session->user_id
        )) {
            return [];
        }

        $sessionId = $this->data->session->session_id;

        $this->session = Session::instance($sessionId);
        $this->session->start();

        if (!isset($this->session->history[$sessionId])) {
            $this->session->{$sessionId} = [];
        }

        $this->historyStack = $this->session;

        $whenMethod = $this->reflectionClass->getMethod('when');
        $whenHistoryMethod = $this->reflectionClass->getMethod('whenHistory');
        $whenRegexMethod = $this->reflectionClass->getMethod('whenRegex');

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

                continue;
            }

            $whenHistoryAttributes = $method->getAttributes(WhenHistory::class);
            if (count($whenHistoryAttributes) > 0) {
                foreach ($whenHistoryAttributes as $attribute) {
                    $whenHistory = $attribute->newInstance();
                    $whenHistoryMethod->invokeArgs($this, [$whenHistory->historyId, $method]);
                }

                continue;
            }

            $whenRegexAttributes = $method->getAttributes(WhenRegex::class);
            if (count($whenRegexAttributes) > 0) {
                foreach ($whenRegexAttributes as $attribute) {
                    $whenRegex = $attribute->newInstance();
                    $whenRegexMethod->invokeArgs($this, [$whenRegex->regex, $method]);
                }

                continue;
            }
        }

        try {
            $data = [
                'version' => '1.0',
                'session' => [
                    'session_id' => $this->data->session->session_id,
                    'message_id' => $this->data->session->message_id,
                    'user_id' => $this->data->session->user_id
                ],
                'response' => $this->process(),
                'data' => [
                    'historyStack' => $this->historyStack
                ]
            ];

            if (CLI::check()) {
                return $data;
            }
            
            header('Content-Type: application/json');
            echo json_encode($data);
        } catch (\Exception $e) {
            var_dump($e);
            if (!CLI::check()) {
                header('Content-Type: application/json');
                echo '["Error occured"]';
            }
        }
    }
}
