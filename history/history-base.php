<?php

abstract class HistoryBase {
  public $history = [];

  public function __construct() {
    $this->history = $this->read();
  }

  public abstract function read();
  public abstract function write();

  public function all() {
    return $this->read();
  }

  public function put($value) {
    $this->history[] = $value;
    $this->write();

    return $this;
  }
}

class JSONHistory extends HistoryBase {
  public function read() {
    return json_decode(file_get_contents(__DIR__ . '/history.json')) ?? [];
  }

  public function write() {
    file_put_contents(__DIR__ . '/history.json', json_encode($this->history, JSON_PRETTY_PRINT));
    return $this;
  }
}
