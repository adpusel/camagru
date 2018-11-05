<?php

namespace DatabaseTesting\Test;


use DatabaseTesting\Generic_Tests_DatabaseTestCase;
use DatabaseTesting\ConvertArrayToDBUnitTable;

class Guestbook
{

  private $pdo;

  public function __construct()
  {
	$this->pdo = new \PDO("mysql:dbname=phpunitTest;host=127.0.0.1",
	  'root', 'hamhamham');
	$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  }

  public function addEntry($name, $content)
  {
	$this->pdo->query("
	 INSERT INTO `guestbook` (`content`, `user`, `created`) 
	 VALUES ('$content', '$name', NOW());"
	);
  }
}


class BookMapperTest extends Generic_Tests_DatabaseTestCase
{
  /**
   * Prepare data set for database tests -> return un array vide
   * ici je vais faire le test avec un array
   * mais dans la fonction d'apres par exemple je le get en YAML
   *
   * cette fontion va directement set ces donnes dans ma database
   *
//   * @return Generic_Tests_DatabaseTestCase.php
   */
  public function getDataSet()
  {
	return new ConvertArrayToDBUnitTable(
	  [
		'guestbook' => [
		  [
			'id'      => 1,
			'content' => 'Hello buddy!',
			'user'    => 'joe',
			'created' => '2010-04-24 17:15:23'
		  ],
		  [
			'id'      => 2,
			'content' => 'I like it!',
			'user'    => null,
			'created' => '2010-04-26 12:14:20'
		  ],
		],
	  ]
	);
  }


  // je get comme ca car il n'y a pas de constructeur propre
//  protected function getDataSet()
//  {
//	return new YamlDataSet(dirname(__FILE__)."/_files/guestbook.yml");
//  }

  public function testNewLine()
  {
	$this->assertSame(2,
	  $this->getConnection()->getRowCount('guestbook'));

	$guestbook = new Guestbook();
	$guestbook->addEntry("suzy", "Hello world!");

	$this->assertSame(
	  3,
	  $this->getConnection()->getRowCount('guestbook'),
	  'ajout fail');
  }

  public function testComplexQuery()
  {
	// il n'y a pas les date car chiant a emuler
	$newTable =
	  [
		'guestbook' => [
		  [
			'id'      => 1,
			'content' => 'Hello buddy!',
			'user'    => 'joe',
		  ],
		  [
			'id'      => 2,
			'content' => 'I like it!',
			'user'    => null,
		  ],
		  [
			'id'      => 3,
			'content' => 'Hello world!',
			'user'    => 'suzy',
		  ],
		],
	  ];

	$guestbook = new Guestbook();
	$guestbook->addEntry("suzy", "Hello world!");

	$queryTable = $this
	  ->getConnection()
	  ->createQueryTable(
		'guestbook', 'SELECT id, content, user FROM guestbook'
	  );

	$expectedTable =
	  $this->createArrayDataSet($newTable)->getTable('guestbook');
	$this->assertTablesEqual($expectedTable, $queryTable);
  }

//  public function testFetchByISBN()
//  {
//	$this->markTestIncomplete('Not written yet.');
//  }
//
//  public function testInsert()
//  {
//	$this->markTestIncomplete('Not written yet.');
//  }
//
//  public function testUpdate()
//  {
//	$this->markTestIncomplete('Not written yet.');
//  }
//
//  public function testDelete()
//  {
//	$this->markTestIncomplete('Not written yet.');
//  }
}