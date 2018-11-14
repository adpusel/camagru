<?php
/**
 * User: adpusel
 * Date: 11/7/18
 * Time: 11:00
 */


namespace Core\User\HTML;

use Core\Html\Field\InputField;
use Core\HTML\Form\Form;
use Core\HTML\Form\FormBuilder;

class LoginUserFormBuilder extends FormBuilder
{

  public function build(): Form
  {
	return $this->form
	  ->add(new InputField([
		'label' => 'Login',
		'name'  => 'login',
	  ]))
	  ->add(new InputField([
		'label' => 'password',
		'name'  => 'password',
	  ]));
  }
}