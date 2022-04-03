<?php

#[Attribute]
class When {
    public $commands = [];

    public function __construct(...$commands) {
        $this->commands = $commands;
    }
}

#[Attribute]
class WhenHistory {
    public $historyId;

    public function __construct($historyId) {
        $this->historyId = $historyId;
    }
}
