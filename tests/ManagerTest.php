<?php
namespace Bricks\Di;
require_once('tests/Services.php');
require_once('Manager.php');
require_once('tests/Object.php');

/**
 * @author Artur Sh. Mamedbekov
 */
class ManagerTest extends \PHPUnit_Framework_TestCase{
  /**
   * @var Manager Менеджер зависимостей.
	 */
	private $manager;

	public function setUp(){
    $services = new Services;
    $services->set('depA', 'a');
    $services->set('depB', 'b');

    $this->manager = new Manager($services);
  }

  /**
   * Должен формировать список зависимостей метода.
   */
  public function testBuildDependency(){
    $this->assertEquals(['a'], $this->manager->buildDependency('Bricks\Di\Object', '__construct'));
    $this->assertEquals(['b'], $this->manager->buildDependency('Bricks\Di\Object', 'methodA'));
  }

  /**
   * Должен использовать null, если зависимость невозможно удовлетворить.
   */
  public function testBuildDependency_shouldSetNullIfDependencyNotFound(){
    $this->assertEquals([null], $this->manager->buildDependency('Bricks\Di\Object', 'methodB'));
  }

  /**
   * Должен инстанциировать класс разрешая зависимости конструктора.
   */
  public function testConstructInjection(){
    $obj = $this->manager->constructInjection('Bricks\Di\Object');
    $this->assertEquals('a', $obj->depA);
  }

  /**
   * Должен вызывать метод объекта разрешая его зависимости.
   */
  public function testMethodInjection(){
    $obj = new Object('test');
    $this->manager->methodInjection($obj, 'methodA');
    $this->assertEquals('b', $obj->depB);
  }
}
