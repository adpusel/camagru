<?php
/**
 * User: adpusel
 * Date: 11/4/18
 * Time: 23:14
 */

use Core\Database\MySqlDatabase;

// permet de charger les config de testage de la db
use Core\User\UserController;
use Core\User\UserEntity;

use DatabaseTesting\Generic_Tests_DatabaseTestCase;
use DatabaseTesting\ConvertArrayToDBUnitTable;

define('ROOT', 'Applications/mappstack-7.1.22-1/apache2/htdocs/42/camagru');

class UserTest extends Generic_Tests_DatabaseTestCase
{
  public function getDataSet()
  {
	MySqlDatabase::getInstance(
	  __DIR__ . "/../../resources/database.ini");
	return new ConvertArrayToDBUnitTable(
	  [
		'Users' => [
		  [
			'email' => 'adrien@gmail.com',
			'hash'  => 'DDeuauaou888'
		  ]
		],
	  ]
	);
  }

  public function testInscriptionNewUser()
  {
	$userController = new UserController();

	$user = new UserEntity([
	  'email'    => 'adrien@prairial.com',
	  'password' => 'DDeuauaou888'
	]);

	// premier test
	// il garde le lien de confimation est dans $emailLink
	$this->assertInternalType('string',
	  $userController->inscription($user));

	// test avec le meme mail
	$this->assertSame(false, $userController->inscription($user));

	// test bad email
	$this->assertSame(false,
	  $userController->inscription(new UserEntity([
		'email'    => 'naa@aeu',
		'password' => 'aoeuaeu343RRR'
	  ])));

	// test mdp
	$this->assertSame(false,
	  $userController->inscription(new UserEntity([
		'email'    => 'naa@aeu.com',
		'password' => 'aoeua'
	  ])));
  }

  public function testChangePassword()
  {
	// ajoute un nouvel user
    $userController = new UserController();
	$user = new UserEntity([
	  'email'    => 'adrien@prairial.com',
	  'password' => 'DDeuauaou888'
	]);

	$this->assertInternalType('string',
	  ($emailLink = $userController->inscription($user)));

	// je get son email de verification
	$emailLink = explode('.', $emailLink);
	$emailLinkTab = [
	  'id'    => $emailLink[0],
	  'token' => $emailLink[1],
	];

	// je le donne a la fonction check qui retourn true si link work
	$this->assertSame(true,
	  $userController->inscription_check($emailLinkTab));

	// je get l'user
	$user = $userController->getModel()->fetchOne($emailLink[0]);

	// est il bien valide
	$this->assertSame(true, $user->isCheck());

  }

}