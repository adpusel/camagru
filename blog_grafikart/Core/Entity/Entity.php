<?php
/**
 * User: adpusel
 * Date: 22/10/2018
 * Time: 21:03
 */


namespace Core\Entity;


class Entity
{
	public function __get($name)
	{
	  $method = 'get' . ucfirst($name);
	  $this->$name = $this->$method();
	  return $this->$name;
	}
}