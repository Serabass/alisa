<?php

trait HasSetters
{
  public function __call($name, $arguments)
  {
    $reflection = new ReflectionClass($this);
    $props = $reflection->getProperties();

    foreach ($props as $prop) {
      $attrs = $prop->getAttributes(Setter::class);
      foreach ($attrs as $attr) {
        $instance = $attr->newInstance();

        if ($instance->defaultValue !== null) {
          $prop->setValue($this, $instance->defaultValue);
        }

        continue;
      }
    }

    return $this;
  }
}
