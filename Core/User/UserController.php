<?php
/**
 * User: adpusel
 * Date: 11/5/18
 * Time: 09:12
 */

namespace Core\User;

use Core\Controller\Controller;
use Core\Http\HTTPRequest;
use Core\Mail\PhpMail;
use function hash;
use function print_r;
use const ROOT;

class UserController extends Controller
{
  // ok
  const CONNECTION = 'Vous etes connectez';
  const MODIF_OK = 'Modification effectuees';

  // err
  const BAD_CREDITIAL = 'Mauvais login || password';
  const NAMESPACE_FORM = 'Core\User\HTML\\';
  const EMAIL_NOT_CONFIRM = 'email non confime';


  // TODO : i think this need to be a trait
  private function _isGoodUser($id)
  {
	$user = $this->app->getUser();
	return
	  $user->isAuthenticated() === true &&
	  $user->getAttribute('id') === $id;
  }


  private function _buildFormAndEntity(HTTPRequest $request, string $formName)
  {
	if ($this->entity === null)
	  $this->entity = new UserEntity();

	// j'hydrante mon entity si post
	if ($request->method() === 'POST')
	  $this->entity->hydrate($request->getAllPost());

	// build le form
	$builderName = self::NAMESPACE_FORM . $formName . 'FormBuilder';
	$formBuilder = new $builderName($this->entity);
	$this->form = $formBuilder->build();

  }


  private function _initFormAndCatchGet(HTTPRequest $request, string $viewName)
  {
	$this->_buildFormAndEntity($request, $viewName);

	// si get je stop et retourn un form tout beau
	if ($request->method() === 'GET')
	{
	  $view = $this->form->createView();
	  $this->addToPage('form', $view);
	  return true;
	}
	else
	  return false;
  }


  private function _generateLink(UserEntity $entity, $action)
  {
	$href =
	  ROOT .
	  '/User' . '.' . $action . '?' .
	  'id=' . $entity->getId() .
	  '&token=' . $entity->getEmailCheck();
	return "<a href='$href'> CLICK </a>";
  }


  private function _sendInscriptionEmail(UserEntity $entity)
  {
	$mailer = new PhpMail(
	  $entity->getEmail(),
	  'Camagru',
	  $this->_generateLink($entity, 'inscription_check'),
	  'Confirm inscription'
	);
	return $mailer->sendEmail();
  }


  private function _isUniqueLogAndMail()
  {
	$res = true;
	$isNew = $this->model->userExist($this->entity->email);
	if ($isNew !== false)
	{
	  $this->app->getUser()->setFlash(UserModel::EXISTING_EMAIL);
	  $res = false;
	}

	$isNew = $this->model->loginExist($this->entity->login);
	if ($isNew !== false)
	{
	  $this->app->getUser()->setFlash(UserModel::EXISTING_LOGIN);
	  $res = false;
	}

	if ($res === false)
	{
	  $this->addToPage('form', $this->form->createView());
	  return false;
	}
	return true;
  }


  public function inscription_check(HTTPRequest $request)
  {
	$user = $this->model->fetchOne($request->getData('id'));

	// TODO : redirect si pas de user
	if ($user->sameCheck($request->getData('token')))
	{
	  $this->model->modify([
		'id'      => $user->id,
		'checked' => true
	  ]);
	  return true;
	}
	return false;
  }


  public function inscription(HTTPRequest $request)
  {
	if ($this->_initFormAndCatchGet($request, "InfoUser") === true)
	  return true;

	if ($this->_isUniqueLogAndMail() === false)
	  return false;

	if ($this->form->isValid() === false)
	{
	  $this->addToPage('form', $this->form->createView());
	  return false;
	}

	$this->entity
	  ->generateEmailCheck()
	  ->generateHash();

	$this->model->create(
	  $this->entity->getDataGiven(
		['email', 'hash', 'email_check', 'login']
	  )
	);

	$this->entity->setId($this->model->lastInsertId());

	// send le mail de verification
	if ($this->_sendInscriptionEmail($this->entity) === false)
	  return new \Exception('le mailer ne marche pas');

	// TODO : faire les redirections
	return "{$this->entity->getId()}.{$this->entity->getEmailCheck()}";
  }


  public function login(HTTPRequest $request)
  {
	// TODO : rediriger si l'user est connecte
	if ($this->_initFormAndCatchGet($request, "LoginUser") === true)
	  return true;

	$dbUser = $this->model->getUserByLogin($this->entity->login);
	if (
	  $dbUser &&
	  $this->entity->_comparePassword($dbUser->hash) &&
	  $dbUser->checked
	)
	{
	  // TODO : redirect ici
	  $this->app->getUser()->setFlash(self::CONNECTION);
	  return true;
	}

	else
	{
	  //flash message
	  if ($dbUser === false)
		$this->app->getUser()->setFlash(self::BAD_CREDITIAL);
	  else
		$this->app->getUser()->setFlash(self::EMAIL_NOT_CONFIRM);

	  // create new form
	  $builderName = self::NAMESPACE_FORM . 'LoginUser' . 'FormBuilder';
	  $form = (new $builderName(new UserEntity()))->build();
	  $view = $form->createView();

	  $this->addToPage('form', $view);
	  return false;
	}
  }


  /**
   * @param HTTPRequest $request
   * c'est du bricolage mais je n'avais pas du tout prevu ca donc bon ...
   *
   * @return bool
   */
  public function modify(HTTPRequest $request)
  {
	// TODO : make redirection to login
	if ($this->user->isAuthenticated() === false)
	{
	  $this->entity = null;
	  return $this->login($request);
	}

	// TODO : make protection if not work
	$this->entity = $this->model->fetchOne(
	  $this->user->getAttribute('id'));

	if ($this->_initFormAndCatchGet($request, "InfoUser") === true)
	  return true;

	if ($this->form->isValid(['email']) === false)
	{
	  $this->addToPage('form', $this->form->createView());
	  return false;
	}

  }


  public function delete(HTTPRequest $request)
  {
	if (
	  $request->method() === 'POST' &&
	  $this->_isGoodUser($request->postData('id')) === true
	)
	{
	  $this->model->delete($request->postData('id'));
	  return true;
	  // TODO : set une redirection
	  // TODO : faire et tester le flash message
	}
	return false;
  }


}



























