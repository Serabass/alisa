<?php

#[Attribute]
class When {
    public $commands = [];

    public function __construct(...$commands) {
        $this->commands = $commands;
    }
}
