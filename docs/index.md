# Инъекция зависимостей

Класс _Manager_ реализует механизм разрешения зависимостей конструктора класса 
при его инстанциации, или метода объекта при его вызове. В этих случаях 
зависимостями являются аргументы методов. Имена аргументов используются для 
поиска сервиса в локаторе служб, который (сервис) и будет передан в качестве 
параметра методу. Если же сервис отсутствует, будет передан `null`.

При инстанциации данного класса, конструктору необходимо передать массив 
сервисов или экземпляр класса, реализующего интерфейс _ArrayAccess_ стандартной 
библиотеки классов PHP. К примеру, это может быть экземпляр класса 
`Bricks\ServiceLocator\Manager`.

## Зависимости при инстанциации

Для разрешения зависимостей конструктора при инстанциации класса, используется 
метод `constructInjection`, который принимает имя инстанциируемого класса, и 
возвращает его экземпляр, вызывая конструктор и передавая ему зависимости.

```php
use Bricks\Di\Manager;
use Bricks\ServiceLocator\Manager as Services;

class MyClass{
  public function __construct($service){
    ...
  }
}

$services = new Services;
$services->set('service', new Service);

$manager = new Manager($services);
// Конструктору будет передан объект из $services->get('service').
$obj = $manager->constructInjection('MyClass');
```

## Зависимости вызываемого метода

Для разрешения зависимостей при вызове метода, используется метод 
`methodInjection`, который принимает вызываемый объект и имя метода, который 
необходимо вызвать. Метод возвращает то, что было возвращено вызываемым методом 
объекта.

```php
use Bricks\Di\Manager;
use Bricks\ServiceLocator\Manager as Services;

class MyClass{
  public function method($service){
    ...
    return 'Hello world';
  }
}

$services = new Services;
$services->set('service', new Service);

$manager = new Manager($services);
$obj = new MyClass;
// Методу будет передан объект из $services->get('service').
$result = $manager->methodInjection($obj, 'method');
var_dump($result); // 'Hello world'
```

## Частичная инъекция

Если метод принимает в качестве параметров не только службы, но и данные, 
разрешить такие зависимости можно с помощью метода `buildDependency`, который 
принимает имя класса и метода, а возвращает массив с зависимостями, которые 
удалось разрешить. Массив можно дополнить недостающими данными и вызвать целевой 
метод передав ему полученный массив.

```php
use Bricks\Di\Manager;
use Bricks\ServiceLocator\Manager as Services;

class MyClass{
  public function method($data, $service){
    ...
  }
}

$manager = new Manager;
$manager->set('service', new Service);

$manager = new Manager($services);
$dependency = $manager->buildDependency('MyClass', 'method');
var_dump($dependency); // [null, new Service]
$dependency[0] = 'data'; // Добавление данных.

$obj = new MyClass;
$result = call_user_func_array([$obj, 'method'], $dependency); // Вызов метода.
```
