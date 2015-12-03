<?php
namespace Bricks\Di;

/**
 * Реализация механизм внедрения зависимостей.
 *
 * @author Artur Sh. Mamedbekov
 */
class Manager{
  /**
   * @var array|\ArrayAccess Доступные сервисы.
   */
  private $services;

  /**
   * @param array|\ArrayAccess $services Доступные сервисы.
   */
  public function __construct($services){
    if(!(is_array($services) || $services instanceof \ArrayAccess)){
      throw new InvalidArgumentException('Expected array or ArrayAccess');
    }

		$this->services = $services;
  }

  /**
   * Формирует список зависимостей метода.
   *
   * @param string $class Имя целевого класса, для метода которого необходимо 
   * сформировать список зависимостей.
   * @param string $method Имя целевого метода класса или объекта, для которого 
   * необходимо сформировать список зависимостей.
   *
   * @return array Список зависимостей целевого метода.
   */
  public function buildDependency($class, $method){
    $params = (new \ReflectionMethod($class, $method))->getParameters();
    $dependency = [];
    foreach($params as $param){
      array_push($dependency, $this->services[$param->getName()]);
    }

    return $dependency;
  }

  /**
   * Инстанциирует класс, разрешая зависимости контроллера.
   *
   * @param string $class Имя целевого класса.
   *
   * @return object Экземпляр целевого класса.
   */
  public function constructInjection($class){
    $dependency = $this->buildDependency($class, '__construct');

    return (new \ReflectionClass($class))->newInstanceArgs($dependency);
  }

  /**
   * Вызывает метод объекта, разрешая зависимости аргументов.
   *
   * @param object $obj Целевой объект.
   * @param string $method Имя вызываемого метода.
   *
   * @return mixed Данные, возвращаемые целевым методом после вызова.
   */
  public function methodInjection($obj, $method){
    $dependency = $this->buildDependency(get_class($obj), $method);

    return call_user_func_array([$obj, $method], $dependency);
  }
}
