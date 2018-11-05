<?php
/**
 * User: adpusel
 * Date: 11/5/18
 * Time: 19:51
 */

//namespace Core\Model;

class Model
{
  protected $table;
  protected $entity;
  protected $attributes = [];
  protected $columns = [];
  protected $strColumn;

  public function __construct()
  {
	$this->table = (new \ReflectionClass($this))->getShortName();
	$this->table = str_replace('Model', '', $this->table);
	$this->entity = $this->table . 'Entity';
  }

  private function getColumnsAndAttributes(array $fields)
  {
	foreach ($fields as $name => $value)
	{
	  $this->columns[] = "$name = ?";
	  $this->attributes[] = $value;
	}
	 $this->strColumn = implode(', ', $this->columns);
  }

  // TODO : la fonction n'est pas bonne il faut la linker avec un le validator
  // ou faire les request a la mains
  public function create($field)
  {
	$sql_parse = array();
	$attributes = array();

	foreach ($field as $index => $item)
	{
	  $sql_parse[] = "$index = ?";
	  $attributes[] = $item;
	}
	$sql_parse = implode(', ', $sql_parse);

	return $this->query("
		INSERT INTO $this->_table
		SET $sql_parse",
	  $attributes,
	  true);
  }

  public function fetchAll()
  {
	return $this->query('
		SELECT * FROM ' . $this->table
	);
  }

  public function fetchOne($id)
  {
	return $this->query('
		SELECT * FROM ' . $this->table
	  . ' WHERE id =' . $id, null, true
	);
  }

  public function delete($id)
  {
	return $this->query("
		DELETE FROM $this->_table
		WHERE id = ?",
	  [
		$id
	  ]);
  }

  // TODO : modify

}

class MModel extends Model
{

}

new MModel;