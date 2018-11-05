<?php
/**
 * User: adpusel
 * Date: 11/5/18
 * Time: 10:40
 */


namespace Core\Entity;

use Core\Utils\Hydrator;

abstract class Entity implements \ArrayAccess
{
  use Hydrator;

  const EXISTING_USER = -1;

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
}