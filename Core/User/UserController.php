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
use const ROOT;

class UserController extends Controller
{
  private function _buildFormAndEntity(HTTPRequest $request, string $formName)
  {
	$this->entity = new UserEntity();

	// j'hydrante mon entity si post
	if ($request->method() === 'POST')
	  $this->entity->hydrate($request->getAllPost());

	// build le form
	$builderName = 'Core\User\HTML\\' . $formName . 'FormBuilder';
	$formBuilder = new $builderName($this->entity);
	$this->form = $formBuilder->build();

  }

  // TODO : i think this need to be a trait
  private function _isGoodUser($id)
  {
	$user = $this->app->getUser();
	return
	  $user->isAuthenticated() === true &&
	  $user->getAttribute('id') === $id;
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
		'id'       => $user->id,
		'is_check' => true
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
	if ($this->_initFormAndCatchGet($request, "InfoUser") === true)
	  return true;

	if ($request->method() === 'GET')
	{
	  $view = $this->form->createView();
	  $this->addToPage('form', $view);
	  return true;
	}
  }

  public function modify(HTTPRequest $request)
  {
	if ($this->_initFormAndCatchGet($request, "ModifyUser") === true)
	  return true;

	if ($request->method() === 'GET')
	{
	  $view = $this->form->createView();
	  $this->addToPage('form', $view);
	  return true;
	}

	// TODO : redirection with flash
	if ($this->_isGoodUser($request->postData('id')) === false)
	{
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

