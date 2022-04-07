<?php

class CLI {
  public static function check() {
    return (php_sapi_name() === 'cli');
  }
}
