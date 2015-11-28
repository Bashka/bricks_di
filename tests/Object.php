<?php
namespace Bricks\Di;

class Object{
  public $depA;

  public $depB;

  public $depC;

  public function __construct($depA){
    $this->depA = $depA;
  }

  public function methodA($depB){
    $this->depB = $depB;
  }

  public function methodB($depC){
    $this->depC = $depC;
  }
}
