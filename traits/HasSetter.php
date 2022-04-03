<?php

trait HasSetters
{
  public function __call($name, $arguments)
  {
    $reflection = new ReflectionClass($this);
    $props = $reflection->getProperties();

    foreach ($props as $prop) {
      if ($prop->getName() !== $name) {
        continue;
      }

      $attrs = $prop->getAttributes(Setter::class);
      foreach ($attrs as $attr) {
        $instance = $attr->newInstance();

        if (count($arguments) === 0) {
          $prop->setValue($this, $instance->defaultValue);
        } else {
          [$value] = $arguments;
          $prop->setValue($this, $value);
        }
      }
    }

    return $this;
  }
}
