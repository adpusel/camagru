<?php
/**
 * User: adpusel
 * Date: 19/10/2018
 * Time: 17:25
 */

namespace Core\Table;

use Core\Database\mySqlDatabase;

class Table
{
  protected $_table;
  protected $_db;

  public function __construct(MySqlDatabase $db)
  {
	$this->_db = $db;
	$this->_table = (new \ReflectionClass($this))->getShortName();
	$this->_table = str_replace('Table','',$this->_table);
  }

  public function all()
  {
	return $this->query('SELECT * FROM '. $this->_table);
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