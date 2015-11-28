<?php
namespace Bricks\ServiceLocator;

/**
 * Минималистичная реализация локатора служб для тестирования Di.
 */
class Manager{
  private $services = [];

  public function set($name, $service){
    $this->services[$name] = $service;
  }

  public function get($name){
    if(!isset($this->services[$name])){
      return null;
    }
    return $this->services[$name];
  }
}
