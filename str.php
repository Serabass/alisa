<?php

class Str {
  public static function toLower($str) {
    return function_exists('mb_strtolower') ? mb_strtolower($str) : strtolower($str);
  }
}