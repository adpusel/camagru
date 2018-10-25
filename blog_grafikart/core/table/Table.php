<?php
/**
 * User: adpusel
 * Date: 19/10/2018
 * Time: 17:25
 */

namespace Core\Table;

use Core\Database\mySqlDatabase;

// TODO : regarder le tuto grafik sur comment manipuler les objs en php

class Table
{
  protected $_table;
  protected $_db;

  public function __construct(MySqlDatabase $db)
  {
	$this->_db = $db;
	$this->_table = (new \ReflectionClass($this))->getShortName();
	$this->_table = str_replace('Table', '', $this->_table);
  }


  public function extract($key, $value)
  {
	$records = $this->all();
	$return = array();

	foreach ($records as $v)
	{
	  $return[$v->$key] = $v->$value;
	}
	return $return;
  }

  public function all()
  {
	return $this->query('SELECT * FROM ' . $this->_table);
  }

  public function one($id)
  {
	return $this->query('SELECT * FROM ' . $this->_table
	  . ' WHERE id =' . $id, null, true);
  }

  public function update($id, $field)
  {
	$sql_parse = array();
	$attributes = array();

	foreach ($field as $index => $item)
	{
	  $sql_parse[] = "$index = ?";
	  $attributes[] = $item;
	}
	$attributes[] = $id;
	$sql_parse = implode(', ', $sql_parse);

	return $this->query("
		UPDATE $this->_table
		SET $sql_parse 
		WHERE id = ?",
	  $attributes,
	  true);
  }


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

  public function delete($id)
  {
	return $this->query("
		DELETE FROM $this->_table
		WHERE id = ?",
	  [$id],
	  true);
  }

  public function query($statement, $attributes = null, $one = false)
  {
	if ($attributes)
	{
	  $name_table = str_replace('Table', 'Entity', static::class);

	  return $this->_db::prepare(
		$statement,
		$attributes,
		$name_table,
		$one);
	}
	else
	{
	  $name_table = str_replace('Table', 'Entity', static::class);

	  return $this->_db::query(
		$statement,
		$name_table,
		$one);
	}
  }
}