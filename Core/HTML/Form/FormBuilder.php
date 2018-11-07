<?php

namespace Core\HTML\Form;


use Core\Model\Entity;

/**
 * Class FormBuilder va creer le form avec une entity
 *
 * @package Core\Html\Form
 */
abstract class FormBuilder
{
  protected $form;

  public function __construct(Entity $entity)
  {
	$this->setForm(new Form($entity));
  }

  abstract public function build();

  public function setForm(Form $form)
  {
	$this->form = $form;
  }

  public function getForm()
  {
	var_dump($this->form);
    return $this->form;
  }
}