<?php
/**
 * User: adpusel
 * Date: 19/10/2018
 * Time: 17:25
 */

namespace App\Table;

use App\App;

class Table
{
  protected static $table;

  public function query($statement, $one, $attributes = null)
  {
	if ($attributes)
	{
	  return App::getDb()->prepare($statement, $attributes, static::class, $one);
	}
	else
	{
	  return App::getDb()->query($statement, static::class, $one);
	}
  }


  public static function find(int $id)
  {
	return App::getDb()->prepare(
	  "
		SELECT *
		FROM " . static::getTable() .
	  " WHERE id = ?",
	  [$id],
	  static::class,
	  true
	);
  }

  protected static function getTable()
  {
	if (static::$table === null)
	{
	  $class_name = explode('\\', static::class);
	  static::$table = strtolower(end($class_name));
	}
	return static::$table;
  }

  public static function getAll()
  {
	return App::getDb()->query(
	  "
		SELECT *
		FROM " . static::getTable(),
	  static::class
	);
  }

  // des que j'essaie de get des trucs avec ma cet class, cette fonction
  // est appeler avec le nom de ma propriete
  public function __get($name)
  {
	$method = 'get' . ucfirst($name);
	// le trix de ouf ce truc, ca permet de call les method bcq plus simplement  !! :)
	// je return le getter tout de suite c'est vraiment genial

	// pour ne pas refaire une allocation a chaque fois :
	// je met dans ma class le content de la variable
	$this->$name = $this->$method();
	return $this->$name;
  }

}