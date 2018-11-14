<?php
/**
 * User: adpusel
 * Date: 11/7/18
 * Time: 11:00
 */


namespace Core\User\HTML;

use Core\Html\Field\InputField;
use Core\Html\Field\TextField;
use Core\HTML\Form\Form;
use Core\HTML\Form\FormBuilder;
use Core\Model\Entity;
use Core\Validator\EmailValidator;
use Core\Validator\MaxLengthValidator;
use Core\Validator\NotNullValidator;
use Core\Validator\PasswordValidator;

class ModifyUserFormBuilder extends FormBuilder
{

  public function build(): Form
  {
	return $this->form
	  ->add(new InputField([
		'label'      => 'Email',
		'name'       => 'email',
		'maxLength'  => 50,
		'validators' => [
		  new MaxLengthValidator('trop long (50 caractères maximum',
			50),
		  new NotNullValidator('il faut en email'),
		  new EmailValidator("Merci de taper un email correct")
		]
	  ]))
	  ->add(new InputField([
		'label'      => 'ancien password',
		'name'       => 'oldPassword',
	  ]))
	  ->add(new InputField([
		'label'      => 'password',
		'name'       => 'password',
		'maxLength'  => 50,
		'validators' => [
		  new MaxLengthValidator('trop long (50 caractères maximum',
			50),
		  new NotNullValidator('il faut en email'),
		  new PasswordValidator('le mot de pass doit faire min 8 avec nb, et maj')
		]
	  ]))
	  ->add(new InputField([
		'label'      => 'Login',
		'name'       => 'login',
		'maxLength'  => 15,
		'validators' => [
		  new MaxLengthValidator('trop long (50 caractères maximum',
			50),
		  new NotNullValidator('il faut en email'),
		]
	  ]));
  }
}