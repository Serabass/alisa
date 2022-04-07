<?php


function ToObject(array $array) {
  $object = new stdClass();
  foreach ($array as $key => $value) {
      if (is_array($value)) {
          $value = ToObject($value);
      }
      $object->$key = $value;
  }
  return $object;
}
