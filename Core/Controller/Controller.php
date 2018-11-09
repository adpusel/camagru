<?php

namespace Core\Controller;

use Core\App\App;
use Core\App\ApplicationComponent;
use Core\Model\Model;
use Core\Page\Page;

abstract class Controller extends ApplicationComponent
{
  protected $action = '';
  protected $module = '';
  protected $page = null;
  protected $view = '';
  protected $model = null;
  protected $entity;
  protected $form;

  public function __construct(App $app, $module, $action)
  {
	parent::__construct($app);

	$modelName = str_replace('Controller', 'Model', get_class($this));
	$this->model = new $modelName();
	$this->page = new Page($app);

	$this->setModule($module);
	$this->setAction($action);
	$this->setView($action);
  }

  /**
   * @return Page|null
   */
  public function getPage(): ?Page
  {
	return $this->page;
  }

  public function execute()
  {
	$method = 'execute' . ucfirst($this->action);

	if (!is_callable([$this, $method]))
	{
	  throw new \RuntimeException('L\'action "' . $this->action .
		'" n\'est pas définie sur ce module');
	}

	$this->$method($this->app->httpRequest());
  }

  public function page()
  {
	return $this->page;
  }

  public function setModule($module)
  {
	if (!is_string($module) || empty($module))
	{
	  throw new \InvalidArgumentException('Le module doit être une chaine de caractères valide');
	}

	$this->module = $module;
  }

  public function setAction($action)
  {
	if (!is_string($action) || empty($action))
	{
	  throw new \InvalidArgumentException('L\'action doit être une chaine de caractères valide');
	}

	$this->action = $action;
  }

  public function setView($view)
  {
	if (!is_string($view) || empty($view))
	{
	  throw new \InvalidArgumentException('La vue doit être une chaine de caractères valide');
	}

	$this->view = $view;

//    $this->page->setContentFile(__DIR__ . '/../../App/' .$this->app->name().'/Modules/'.$this->module.'/Views/'.$this->view.'.php');
  }

  public function addToPage(string $name,  $var)
  {
	$this->page->addVar($name, $var);
  }

  /**
   * @return Model
   */
  public function getModel(): Model
  {
	return $this->model;
  }

}