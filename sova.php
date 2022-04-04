<?php

class Sova {
  public $data;
  public function __construct() {
    $this->data = json_decode(file_get_contents(__DIR__ . '/sova.json'));
  }

  public function random() {
    return $this->data[array_rand($this->data)];
  }
}

function sova() {
  return new Sova();
}