<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

abstract class Alisa {
  public abstract function hello();
}

class MyAlisa extends Alisa {
  public function attr() {
    function dumpAttributeData($reflection) {
      var_dump($reflection->getMethods()[1]->getAttributes()); die;
      $attributes = $reflection->getAttributes();
    
      foreach ($attributes as $attribute) {
        var_dump($attribute->getName());
        var_dump($attribute->getArguments());
        var_dump($attribute->newInstance());
      }
    }
    
    dumpAttributeData(new ReflectionClass(MyAlisa::class));
  }

  public function hello() {

  }
}

(new MyAlisa())->attr();