<?php
/**
 * User: adpusel
 * Date: 11/5/18
 * Time: 10:40
 */


namespace Core\Model;

use Core\Utils\Hydrator;

class Entity implements \ArrayAccess
{
  use Hydrator;

  protected $erreurs = [],
	$id;

  public function __construct(array $donnees = [])
  {
	if (!empty($donnees))
	{
	  $this->hydrate($donnees);
	}
  }

  public function isNew()
  {
	return empty($this->id);
  }

  public function erreurs()
  {
	return $this->erreurs;
  }

  public function getId()
  {
	return $this->id;
  }

  public function setId($id)
  {
	$this->id = (int)$id;
  }

  public function offsetGet($var)
  {
	if (isset($this->$var) && is_callable([$this, $var]))
	{
	  return $this->$var();
	}
  }

  public function offsetSet($var, $value)
  {
	$method = 'set' . ucfirst($var);

	if (isset($this->$var) && is_callable([$this, $method]))
	{
	  $this->$method($value);
	}
  }

  public function offsetExists($var)
  {
	return isset($this->$var) && is_callable([$this, $var]);
  }

  public function offsetUnset($var)
  {
	throw new \Exception('Impossible de supprimer une quelconque valeur');
  }

  public function __get($name)
  {
	$name_func = 'get' . ucfirst($name);
//	if (method_exists(true, $name_func))
	return $this->$name_func();
  }
}