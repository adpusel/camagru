<?php
/**
 * User: adpusel
 * Date: 11/3/18
 * Time: 15:22
 */

namespace DatabaseTesting\Mappers;


use Core\Database\MySqlDatabase;
use DatabaseTesting\Generic_Tests_DatabaseTestCase;
use DatabaseTesting\ConvertArrayToDBUnitTable;

class MySqlMapperTest extends Generic_Tests_DatabaseTestCase
{
  public function getDataSet()
  {
	MySqlDatabase::getInstance(
	  __DIR__ . "/../../resources/database.ini");
	return new ConvertArrayToDBUnitTable(
	  [
		'Users' => [
		  [
			'id'       => 1,
			'email'    => 'joe',
			'password' => 'lalala'
		  ],
		  [
			'id'       => 2,
			'email'    => 'patrick',
			'password' => 'ololo'
		  ],
		],
	  ]
	);
  }

  public function testInsert()
  {
	$newArrayWanted = [
	  'Users' => [
		[
		  'id'       => 1,
		  'email'    => 'joe',
		  'password' => 'lalala'
		],
		[
		  'id'       => 2,
		  'email'    => 'patrick',
		  'password' => 'ololo'
		],
		[
		  'id'       => 3,
		  'email'    => 'sab',
		  'password' => 'toto'
		],
	  ]
	];

	MySqlDatabase::prepare(
	  'INSERT INTO `Users` SET
		email = :email,
		password = :password',
	  [
		'email'    => 'sab',
		'password' => 'toto'
	  ]);

	// check si j'ai bien ajoute
	$this->assertEquals(
	  3,
	  $this->getConnection()->getRowCount('Users'),
	  'inserte fail');

	// permet de get les info de la tables
	$queryTable = $this
	  ->getConnection()
	  ->createQueryTable(
		'Users', 'SELECT id, email, password FROM Users'
	  );

	$expectedTable =
	  $this->createArrayDataSet($newArrayWanted)->getTable('Users');
	$this->assertTablesEqual($expectedTable, $queryTable);
  }

  public function testModify()
  {
	$newArrayWanted = [
	  'Users' => [
		[
		  'id'       => 1,
		  'email'    => 'joe',
		  'password' => 'lalala'
		],
		[
		  'id'       => 2,
		  'email'    => 'sab',
		  'password' => 'toto'
		],
	  ]
	];

	MySqlDatabase::prepare(
	  'UPDATE Users SET
		email = :email,
		password = :password
		WHERE id = 2',
	  [
		'email'    => 'sab',
		'password' => 'toto'
	  ]);

	// permet de get les info de la tables
	$queryTable = $this
	  ->getConnection()
	  ->createQueryTable(
		'Users', 'SELECT id, email, password FROM Users'
	  );

	$expectedTable =
	  $this->createArrayDataSet($newArrayWanted)->getTable('Users');
	$this->assertTablesEqual($expectedTable, $queryTable);
  }

  public function testDelete()
  {
	$newArrayWanted = [
	  'Users' => [
		[
		  'id'       => 1,
		  'email'    => 'joe',
		  'password' => 'lalala'
		],
	  ]
	];

	MySqlDatabase::prepare(
	  'DELETE FROM Users
		WHERE id = 2',
	  []
	);

	// permet de get les info de la tables
	$queryTable = $this
	  ->getConnection()
	  ->createQueryTable(
		'Users', 'SELECT id, email, password FROM Users'
	  );

	// compare la table avec le reste
	$expectedTable =
	  $this->createArrayDataSet($newArrayWanted)->getTable('Users');
	$this->assertTablesEqual($expectedTable, $queryTable);
  }

  public function testFetch()
  {
	$query = MySqlDatabase::prepare(
	  'SELECT * FROM Users
		WHERE id = ?',
	  [1],
	  '',
	  true
	);

	$this->assertEquals('1', $query->id);
	$this->assertEquals('joe', $query->email);
	$this->assertEquals('lalala', $query->password);
  }

  public function testFetchAll()
  {
	$query = MySqlDatabase::prepare(
	  'SELECT * FROM Users',
	  [],
	  ''
	);

	// premier el
	$this->assertEquals('1', $query[0]->id);
	$this->assertEquals('joe', $query[0]->email);
	$this->assertEquals('lalala', $query[0]->password);

	// deuxieme el
	$this->assertEquals('2', $query[1]->id);
	$this->assertEquals('patrick', $query[1]->email);
	$this->assertEquals('ololo', $query[1]->password);
  }


  // TODO : faire un test avec fetch mode class
}