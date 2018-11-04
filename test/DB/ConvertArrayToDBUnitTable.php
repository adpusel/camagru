<?php

namespace DatabaseTesting;

use PHPUnit\DbUnit\DataSet\AbstractDataSet;
use PHPUnit\DbUnit\DataSet\DefaultTableMetadata;
use PHPUnit\DbUnit\DataSet\DefaultTable;
use PHPUnit\DbUnit\DataSet\DefaultTableIterator;

class ConvertArrayToDBUnitTable extends AbstractDataSet
{
  /**
   * @var array
   */
  protected $tables = [];

  /**
   * @param array $data
   */
  public function __construct(array $data)
  {
	foreach ($data AS $tableName => $rows)
	{
	  $columns = [];
	  if (isset($rows[0]))
	  {
		$columns = array_keys($rows[0]);
	  }

	  $metaData = new DefaultTableMetaData($tableName, $columns);
	  $table = new DefaultTable($metaData);

	  foreach ($rows AS $row)
	  {
		$table->addRow($row);
	  }
	  $this->tables[$tableName] = $table;
	}
  }

  protected function createIterator($reverse = false)
  {
	return new DefaultTableIterator($this->tables, $reverse);
  }

  public function getTable($tableName)
  {
	if (!isset($this->tables[$tableName]))
	{
	  throw new InvalidArgumentException("$tableName is not a table in the current database.");
	}

	return $this->tables[$tableName];
  }
}