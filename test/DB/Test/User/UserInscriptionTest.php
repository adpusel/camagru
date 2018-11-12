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

use Core\User\UserModel;
use DatabaseTesting\Generic_Tests_DatabaseTestCase;
use DatabaseTesting\ConvertArrayToDBUnitTable;

define('ROOT', 'Applications/mappstack-7.1.22-1/apache2/htdocs/42/camagru');
session_start();

class UserInscriptionTest extends Generic_Tests_DatabaseTestCase
{
  protected $userController;
  protected $request;
  protected $link_inscription;
  protected $form;
  protected $page;
  protected $userEntity;
  protected $app;


  public function getDataSet()
  {
	MySqlDatabase::getInstance(
	  __DIR__ . "/../../../resources/database.ini");

	// init mes objets
	$this->app = new Core\App\App("a", "a");

	$this->userController = new UserController($this->app, "a", "a");
	$this->request = $this->app->getHttpRequest();
	$this->page = $this->userController->getPage();

	$this->userEntity = new UserEntity([
	  'email'    => 'adrien@gmail.com',
	  'password' => 'DDeuauaou888',
	  'login'    => 'toto'
	]);

	$this->userEntity
	  ->generateEmailCheck()
	  ->generateHash();
	$this->link_inscription = [
	  'id'    => 1,
	  'token' => $this->userEntity->getEmailCheck(),
	];

	return new ConvertArrayToDBUnitTable(
	  [
		'Users' => [
		  [
			'email'       => $this->userEntity->email,
			'hash'        => $this->userEntity->hash,
			'email_check' => $this->userEntity->getEmailCheck(),
			'login'       => $this->userEntity->getLogin()
		  ]
		],
	  ]
	);
  }

  private function setDataRequest($method, $data = [], $dataSession = [])
  {
	if ($method === 'POST')
	  $_POST = $data;
	$_SESSION = $dataSession;
	$_SERVER['REQUEST_METHOD'] = $method;
  }

  private function _get_form_fill_render($formName, bool $do_check = false)
  {
	$builderName = 'Core\User\HTML\\' . $formName . 'FormBuilder';
	$form = (new $builderName(new UserEntity($_POST)))->build();
	if ($do_check)
	  $form->isValid();
	return $form->createView();
  }

  public function testGetIncriptionPage()
  {
	$this->setDataRequest('GET');

	// new form str
	$formStr = $this->_get_form_fill_render('InfoUser');

	$this->assertSame(
	  true,
	  $this
		->userController
		->inscription($this->request)
	);

	$this->assertSame(
	  $formStr,
	  $this->page->getVars('form')
	);


  }

  public function testInscriptionNewUser()
  {
	$this->setDataRequest('POST', [
	  'email'    => 'adrien@prairial.com',
	  'password' => 'DDeuauaou888',
	  'login'    => 'tata'
	]);

	// il garde le lien de confimation est dans $link_inscription
	$this->assertInternalType('string',
	  $this->userController->inscription($this->request));

  }

  // TODO : need testing if the form is filled with last tapping
  public function testInscriptionExisting()
  {
	// test same mail
	$this->setDataRequest('POST', [
	  'email'    => $this->userEntity->email,
	  'password' => 'DDeuauaou888',
	  'login'    => 'auau'
	]);

	$this->assertSame(false,
	  $this->userController->inscription($this->request));

	$this->assertSame(UserModel::EXISTING_EMAIL,
	  $this->app->getUser()->getFlash());

	// test same login
	$this->setDataRequest('POST', [
	  'email'    => 'bob@ema.com',
	  'password' => 'DDeuauaou888',
	  'login'    => $this->userEntity->login
	]);

	$this->assertSame(false,
	  $this->userController->inscription($this->request));

	$this->assertSame(UserModel::EXISTING_LOGIN,
	  $this->app->getUser()->getFlash());

  }

  public function test_inscription_new_user_bad_password()
  {
	$this->setDataRequest('POST', [
	  'email'    => 'naa@aeu.com',
	  'password' => 'aoeua'
	]);

	$this->assertSame(
	  false,
	  $this->userController->inscription($this->request)
	);
	$this->assertSame(
	  $this->_get_form_fill_render('InfoUser', true),
	  $this->page->getVars('form')
	);
  }

  public function test_inscription_new_user_bad_email()
  {
	$this->setDataRequest('POST', [
	  'email'    => 'naa@aeucom',
	  'password' => 'aoeuauGGGHH009a',
	  'login'    => 'al'
	]);

	$this->assertSame(
	  false,
	  $this->userController->inscription($this->request)
	);

	$this->assertSame(
	  $this->_get_form_fill_render('InfoUser', true),
	  $this->page->getVars('form')
	);

	$this->assertSame(
	  $this->_get_form_fill_render('InfoUser', true),
	  $this->page->getVars('form')
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

  /**
   * @dataProvider deleteUserProvadier
   */
  public function test_delete_user($method, $dataSend, $dataSession,
								   $expect, $entry)
  {
	$this->setDataRequest($method, $dataSend, $dataSession);

	$this->assertSame($expect, $this->userController->delete($this->request));
	$this->assertSame($entry,
	  $this->getConnection()->getRowCount('Users'));

  }

  public function deleteUserProvadier()
  {
	return [
	  ['GET', ['id' => 1], [], false, 1],
	  ['GET', [], ['id' => 1], false, 1],
	  ['POST', [], ['id' => 1], false, 1],
	  ['POST', ['id' => 1], ['id' => 1], false, 1],
	  ['POST', ['id' => 1], ['id' => 1, 'auth' => true], true, 0],
	];
  }


  public function ModifyDataUserProvider()
  {
	return [
	  ['GET', [], [], false, false, false],
	  //	  ['POST', [], [], false, false, false],
	];
  }


//  /**
//   * @dataProvider ModifyDataUserProvider
//   */
//  public function testModifyDataUser($method, $dataSend, $dataSession, $expect,
//									 $sqlRes, $checkForm)
//  {
//	$this->setDataRequest($method, $dataSend, $dataSession);
//
//	// res Controller function
//	$this->assertSame($expect, $this->userController->modify($this->request));
//
//	// compare table MySql
//	if ($sqlRes !== false)
//	  $this->assertSame($sqlRes,
//		$this->getConnection()->getRowCount('Users'));
//
//	// compare page
//	$this->assertSame($this->_get_form_fill_render('InfoUser',$checkForm),
//	  $this->page->getVars('form'));
//
//	// tester s`il est connecte
//
//	//
//	// changer pass
//
//	// changer le mail
//
//	// changer le pseudo --> is libre
//
//	// si a
//  }
//

  public function LoginUserProvider()
  {
	return [
	  ['GET', [], [], true, false, false],
	  //	  ['POST', [], [], false, false, false],
	];
  }

  /**
   * @dataProvider LoginUserProvider
   */
  public function testLoginUser($method, $dataSend, $dataSession, $expect,
								$sqlRes, $checkForm)
  {
	$this->setDataRequest($method, $dataSend, $dataSession);

	// res Controller function
	$this->assertSame($expect, $this->userController->login($this->request));

	// compare table MySql
	if ($sqlRes !== false)
	  $this->assertSame($sqlRes,
		$this->getConnection()->getRowCount('Users'));

	// compare page
	$this->assertSame($this->_get_form_fill_render('LoginUser', $checkForm),
	  $this->page->getVars('form'));

	// tester s`il est connecte

	//
	// changer pass

	// changer le mail

	// changer le pseudo --> is libre

	// si a
  }

}



















