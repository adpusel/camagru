<?php
/**
 * User: adpusel
 * Date: 11/6/18
 * Time: 22:41
 */


namespace Core\HTML\Form;

use Core\Html\Field\Field;
use Core\Model\Entity;

class Form
{
  protected $entity;
  protected $fields = [];


  public function __construct(Entity $entity)
  {
	$this->setEntity($entity);
  }


  public function add(Field $field)
  {
	$attr = $field->name(); // On récupère le nom du champ.
	$field->setValue($this->entity->$attr); // On assigne la valeur correspondante au champ.

	// On ajoute le champ passé en argument à la liste des champs.
	$this->fields[] = $field;
	return $this;
  }


  public function createView()
  {
	$view = '';

	// On génère un par un les champs du formulaire.
	foreach ($this->fields as $field)
	{
	  $view .= $field->buildWidget() . '<br />';
	}

	return $view;
  }


  public function isValid($selectField = false)
  {
	$valid = true;

	if ($selectField)
	{
	  foreach ($this->fields as $field)
	  {
		if (in_array($field->name(), $selectField) &&
		  $field->isValid() === false
		)
		  $valid = false;;
	  }
	}
	else
	{
	  foreach ($this->fields as $field)
	  {
		if (!$field->isValid())
		  $valid = false;
	  }
	}
	return $valid;
  }


  public
  function entity()
  {
	return $this->entity;
  }


  public
  function setEntity(Entity $entity)
  {
	$this->entity = $entity;
  }
}
