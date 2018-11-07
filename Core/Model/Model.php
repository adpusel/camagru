<?php
/**
 * User: adpusel
 * Date: 11/5/18
 * Time: 19:51
 */

namespace Core\Model;

use Core\Database\Database;
use Core\Database\MySqlDatabase;
use function get_class;

class Model
{
  protected $table;
  protected $entity;
  protected $attributes = [];
  protected $columns = [];
  protected $strColumn;
  /**
   * @var Database
   */
  protected $database;

  public function __construct(Database $database = null)
  {

	$this->table = (new \ReflectionClass($this))->getShortName();
	$this->table = str_replace('Model', '', $this->table);

	$this->entity = str_replace('Model', 'Entity', get_class($this));

	$this->database =
	  $database === null ? MySqlDatabase::getInstance() : $database;

	$this->table .= 's';
  }

  private function getColumnsAndAttributes(array $fields)
  {

    $this->columns = [];
    foreach ($fields as $name => $value)
	{
	  if ($name !== 'id')
		$this->columns[] = "$name = :$name";
	}
	$this->strColumn = implode(', ', $this->columns);
	$this->attributes = $fields;
  }

  // TODO : la fonction n'est pas bonne il faut la linker avec un le validator
  // ou faire les request a la mains
  public function create($field)
  {
	$this->getColumnsAndAttributes($field);
	return
	  $this
		->database
		->query("INSERT INTO {$this->table}
		SET {$this->strColumn}",
		  $this->attributes);
  }

  public function fetchOne($id)
  {
	return $this
	  ->database
	  ->query('
		SELECT * FROM ' . $this->table
		. ' WHERE id = ?',
		[
		  $id
		],
		$this->entity,
		true
	  );
  }

  public function fetchAll()
  {
	return $this
	  ->database
	  ->query('
		SELECT * FROM ' . $this->table,
		[],
		$this->entity
	  );
  }

  public function modify($field)
  {
	$this->getColumnsAndAttributes($field);
	return $this
	  ->database
	  ->query("
		UPDATE {$this->table}
		SET {$this->strColumn}
		WHERE id = :id",
		$this->attributes,
		true);
  }

  public function delete($id)
  {
	return $this
	  ->database
	  ->query("
		DELETE FROM $this->table
		WHERE id = ?",
		[
		  $id
		]);
  }

  public function lastInsertId( )
  {
	return $this->database->lastInsertId();
  }
}