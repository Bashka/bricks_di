<?php
namespace Bricks\Di;

/**
 * Минималистичная реализация локатора служб для тестирования Di.
 */
class Services implements \ArrayAccess{
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

	public function offsetExists($offset){
		return isset($this->services[$offset]);
	}

	public function offsetGet($offset){
		return $this->get($offset);
	}

	public function offsetSet($offset, $value){
		$this->set($offset, $value);
	}

	public function offsetUnset($offset){
	}
}
