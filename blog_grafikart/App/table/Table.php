<?php
/**
 * User: adpusel
 * Date: 19/10/2018
 * Time: 17:25
 */

namespace App\Table;

class Table
{
  protected static $table;

  private static function getTable()
  {
    if (self::$table === null)
	{
	 	self::$table = __CLASS__;
	}
	return self::$table;
  }

  public static function getAll()
  {
	return App::getDb()->query(
	  "
		SELECT *
		FROM " . self::$table,
	  __CLASS__);
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