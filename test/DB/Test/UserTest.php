<?php
/**
 * User: adpusel
 * Date: 11/4/18
 * Time: 23:14
 */

use Core\Database\MySqlDatabase;

// permet de charger les config de testage de la db
use Core\Http\HTTPRequest;
use Core\User\UserController;
use Core\User\UserEntity;

use Core\User\UserModel;
use DatabaseTesting\Generic_Tests_DatabaseTestCase;
use DatabaseTesting\ConvertArrayToDBUnitTable;

define('ROOT', 'Applications/mappstack-7.1.22-1/apache2/htdocs/42/camagru');

class UserTest extends Generic_Tests_DatabaseTestCase
{
  public function getDataSet()
  {
	MySqlDatabase::getInstance(
	  __DIR__ . "/../../resources/database.ini");

	$userEntity = new UserEntity([
	  'email'    => 'adrien@gmail.com',
	  'password' => 'DDeuauaou888'
	]);

	$userEntity
	  ->generateEmailCheck()
	  ->generateHash();
	$GLOBALS["link_incription"] = [
	  'id'    => 1,
	  'token' => $userEntity->getEmailCheck(),
	];

	return new ConvertArrayToDBUnitTable(
	  [
		'Users' => [
		  [
			'email'       => 'adrien@gmail.com',
			'hash'        => $userEntity->hash,
			'email_check' => $userEntity->getEmailCheck()
		  ]
		],
	  ]
	);
  }

  private function setDataGlobal($method, $arrayGet, $arrayPost)
  {
	$_GET = $arrayGet;
	$_POST = $arrayPost;
	$_SERVER['REQUEST_METHOD'] = $method;
  }

  public function testInscriptionNewUser()
  {
	$this->setDataGlobal('POST', [], [
	  'email'    => 'adrien@prairial.com',
	  'password' => 'DDeuauaou888'
	]);

	$userController = new UserController();

	// init la request
	$request = new HTTPRequest();


	// premier test
	// il garde le lien de confimation est dans $emailLink
	$this->assertInternalType('string',
	  $userController->inscription($request));

	// test avec le meme mail
	$this->assertSame(false, $userController->inscription($request));

	// test bad email
	$this->setDataGlobal('POST', [], [
	  'email'    => 'naa@aeu',
	  'password' => 'aoeuaeu343RRR'
	]);

	$this->assertSame(false,
	  $userController->inscription($request));

	// test bad
	$this->setDataGlobal('POST', [], [
	  'email'    => 'naa@aeu.com',
	  'password' => 'aoeua']);

	$this->assertSame(false,
	  $userController->inscription($request));
  }

  public function testGoodCheckLink()
  {
	// ajoute un nouvel user
	$userController = new UserController();

	// init la request
	$request = new HTTPRequest();

	// simule le click sur le lien du email
	$_GET = $GLOBALS['link_incription'];

	// je le donne a la fonction check qui retourn true si link work
	$this->assertSame(true,
	  $userController->inscription_check($request));

	// je get l'user
	$user = $userController->getModel()->fetchOne($request->getData('id'));

	// est il bien valide
	$this->assertSame(true, $user->isCheck());
  }

  public function testCheckBadCheckLink()
  {
	// ajoute un nouvel user
	$userController = new UserController();

	// init la request
	$request = new HTTPRequest();

	// simule le click sur le lien du email
	$_GET = $GLOBALS['link_incription'];

	// je met un token pouri
	$_GET['token'] = 'auaouaou a uaoeuaohu aoeu ';

	// je le donne a la fonction check qui retourn true si link work
	$this->assertSame(false,
	  $userController->inscription_check($request));

	// je get l'user
	$user = $userController->getModel()->fetchOne($request->getData('id'));

	// check si check est toujours false
	$this->assertSame(false, $user->isCheck());
  }


}