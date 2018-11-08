<?php
/**
 * User: adpusel
 * Date: 11/4/18
 * Time: 23:14
 */

use Core\Database\MySqlDatabase;

// permet de charger les config de testage de la db
use Core\HTML\Form\Form;
use Core\Http\HTTPRequest;
use Core\User\HTML\UserFormBuilder;
use Core\User\UserController;
use Core\User\UserEntity;

use Core\User\UserModel;
use DatabaseTesting\Generic_Tests_DatabaseTestCase;
use DatabaseTesting\ConvertArrayToDBUnitTable;

define('ROOT', 'Applications/mappstack-7.1.22-1/apache2/htdocs/42/camagru');

class UserTest extends Generic_Tests_DatabaseTestCase
{
  protected $userController;
  protected $request;
  protected $link_inscription;
  protected $form;


  public function getDataSet()
  {
	MySqlDatabase::getInstance(
	  __DIR__ . "/../../resources/database.ini");

	// init mes objets
	$this->userController = new UserController();
	$this->request = new HTTPRequest();

	$userEntity = new UserEntity([
	  'email'    => 'adrien@gmail.com',
	  'password' => 'DDeuauaou888'
	]);

	$userEntity
	  ->generateEmailCheck()
	  ->generateHash();
	$this->link_inscription = [
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

  private function setDataGlobal($method, $arrayGet = [], $arrayPost = [])
  {
	$_GET = $arrayGet;
	$_POST = $arrayPost;
	$_SERVER['REQUEST_METHOD'] = $method;
  }

  private function get_form_fill_render(bool $do_check = false)
  {
	$form = (new UserFormBuilder(new UserEntity($_POST)))->build();
	if ($do_check)
	  $form->isValid();
	return $form->createView();
  }

  public function testGetIncriptionPage()
  {
	$this->setDataGlobal('GET');

	// new form str
	$formStr = $this->get_form_fill_render();

	$this->assertSame(
	  $formStr,
	  $this
		->userController
		->inscription($this->request)
	);

  }


  public function testInscriptionNewUser()
  {
	$this->setDataGlobal('POST', [], [
	  'email'    => 'adrien@prairial.com',
	  'password' => 'DDeuauaou888'
	]);

	// il garde le lien de confimation est dans $link_inscription
	$this->assertInternalType('string',
	  $this->userController->inscription($this->request));

	// test avec le meme mail
	$this->assertSame(false,
	  $this->userController->inscription($this->request));

	// test bad email
	$this->setDataGlobal('POST', [], [
	  'email'    => 'naa@aeu',
	  'password' => 'aoeuaeu343RRR'
	]);

	// TODO : tester avec le message flash
	//	$this->assertSame(false,
	//	  $this->userController->inscription($this->request));
  }

  public function test_inscription_new_user_bad_password()
  {
	$this->setDataGlobal('POST', [], [
	  'email'    => 'naa@aeu.com',
	  'password' => 'aoeua'
	]);

	$this->assertSame(
	  $this->get_form_fill_render(true),
	  $this->userController->inscription($this->request)
	);
  }

  public function test_inscription_new_user_bad_email()
  {
	$this->setDataGlobal('POST', [], [
	  'email'    => 'naa@aeucom',
	  'password' => 'aoeuauGGGHH009a'
	]);

	$this->assertSame(
	  $this->get_form_fill_render(true),
	  $this->userController->inscription($this->request)
	);
  }


  public function test_good_check_link()
  {
	// simule le click sur le lien du email
	$_GET = $this->link_inscription;

	// je le donne a la fonction check qui retourn true si link work
	$this->assertSame(true,
	  $this->userController->inscription_check($this->request));

	// je get l'user
	$user = $this->userController->getModel()
	  ->fetchOne($this->request->getData('id'));

	// est il bien valide
	$this->assertSame(true, $user->isCheck());
  }

  public function test_bad_check_link()
  {

	// simule le click sur le lien du email
	$_GET = $this->link_inscription;

	// je met un token pouri
	$_GET['token'] = 'auaouaou a uaoeuaohu aoeu ';

	// je le donne a la fonction check qui retourn true si link work
	$this->assertSame(false,
	  $this->userController->inscription_check($this->request));

	// je get l'user
	$user = $this->userController->getModel()
	  ->fetchOne($this->request->getData('id'));

	// check si check est toujours false
	$this->assertSame(false, $user->isCheck());
  }

}