<?php

#[Attribute]
class When
{
    public $commands = [];

    public function __construct(...$commands)
    {
        $this->commands = $commands;
    }
}

#[Attribute]
class WhenRegex
{
    public $regex;

    public function __construct($regex)
    {
        $this->regex = $regex;
    }
}

#[Attribute]
class WhenHistory
{
    public $historyId;

    public function __construct($historyId)
    {
        $this->historyId = $historyId;
    }
}
