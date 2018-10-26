<?php
/**
 * User: adpusel
 * Date: 25/10/2018
 * Time: 21:10
 */


namespace Core\Database;


class QueryBulder
{
  private $_select = [];
  private $_conditions = [];
  private $_from = [];

  public function select()
  {
	$this->_select = func_get_args();
	return $this;
  }

  public function where()
  {
	foreach (func_get_args() as $item)
	{
	  $this->_conditions[] = $item;
	}
	return $this;
  }

  public function from($table, $alias = null)
  {
	if (is_null($alias))
	  $this->_from[] = $table;
	else
	  $this->_from[] = "$table AS $alias";
	return $this;
  }

  public function __toString()
  {
	return
	  'SELECT ' . implode(' ', $this->_select) .
	  ' FROM ' . implode(', ', $this->_from) .
	  ' WHERE ' . implode(' AND ', $this->_conditions);

  }
}