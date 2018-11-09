<?php
/**
 * User: adpusel
 * Date: 11/5/18
 * Time: 09:12
 */

namespace Core\User;

use Core\App\App;
use Core\Controller\Controller;
use Core\Http\HTTPRequest;
use Core\Mail\PhpMail;
use Core\User\HTML\UserFormBuilder;
use const ROOT;

class UserController extends Controller
{
  private function _buildFormAndEntity(HTTPRequest $request)
  {
	$this->entity = new UserEntity();

	// j'hydrante mon entity si post
	if ($request->method() === 'POST')
	  $this->entity->hydrate($request->getAllPost());

	// build le form
	$formBuilder = new UserFormBuilder($this->entity);
	$this->form = $formBuilder->build();

  }

  private function _isGoodUser($id)
  {
	$user = $this->app->getUser();

	return
	  $user->isAuthenticated() === true &&
	  $user->getAttribute('id') === $id;


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
	$this->_buildFormAndEntity($request);

	// si get je stop et retourn un form tout beau
	if ($request->method() === 'GET')
	{
	  $view = $this->form->createView();
	  $this->addToPage('form', $view);
	  return true;
	}

	// check si l'user n'existe pas deja je fais un message flash
	// TODO : le set dans mon err
	$isNew = $this->model->userExist($this->entity->email);
	if ($isNew !== false)
	  return false;

	// TODO : return le form
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

	// ici je dois redirider
	return "{$this->entity->getId()}.{$this->entity->getEmailCheck()}";
  }

  public function modify(HTTPRequest $request)
  {

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

