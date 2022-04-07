<?php

include_once 'cli.php';

class Session
{
    private static $instance;
    public static function instance($id)
    {
        return static::$instance ?? static::$instance = new static($id);
    }

    public $id;
    public function __construct($id)
    {
        $this->id = $id;
    }

    public function start()
    {

        if (!CLI::check()) {
            session_id($this->id);
            session_start();
        }
    }

    public function __get($name)
    {
        return $_SESSION[$name] ?? null;
    }

    public function __set($name, $value)
    {
        $_SESSION[$name] = $value;
    }
}
