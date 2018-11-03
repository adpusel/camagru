<?php

namespace Core\Router;


/**
 * Class Route
 */
class Route
{
  protected $action;
  protected $module;
  protected $regUrl;
  /**
   * @var array contient toutes les var en regex pour chaque var
   *             en $_GET de la route
   */
  protected $tabVars = [];

  /**
   * Route constructor.
   *
   * @param mixed $xml_element
   */
  public function __construct($xml_element)
  {
	$this->setRegUrl($xml_element->getAttribute('url'));
	$this->setModule($xml_element->getAttribute('module'));
	$this->setAction($xml_element->getAttribute('action'));
	if ($xml_element->hasAttribute('vars'))
	  $this->setVarsNames($xml_element->getAttribute('vars'));
  }

  /**
   * @param $url
   *
   * @return bool
   */
  public function match($url)
  {
	if (preg_match('#^' . $this->regUrl . '$#', $url, $matches))
	{
	  if ($this->hasVars())
	  {
		$tmpTab = [];
	    foreach ($matches as $index => $match)
		{
		  if ($index > 0)
			$tmpTab[$this->tabVars[$index - 1]] = $matches[$index];
		}
		$this->tabVars = $tmpTab;
	  }
	  return true;
	}
	else
	  return false;
  }

  /**
   * @param string $strAllVarsNames
   * creer un tab avec toutes les key de $_GET en key
   * les values a null
   */
  public function setVarsNames(string $strAllVarsNames)
  {
	$this->tabVars =
	  explode(',', $strAllVarsNames);
  }

  /*------------------------------------*\
      utils
  \*------------------------------------*/
  public function hasVars()
  {
	return !empty($this->tabVars);
  }

  /*------------------------------------*\
      Setter
  \*------------------------------------*/
  public function setAction(string $action)
  {
	$this->action = $action;
  }

  public function setModule(string $module)
  {
	$this->module = $module;
  }

  public function setRegUrl(string $regUrl)
  {
	$this->regUrl = $regUrl;
  }

  /*------------------------------------*\
      Getter
  \*------------------------------------*/

  /**
   * @return string
   */
  public function getAction()
  {
	return $this->action;
  }

  /**
   * @return string
   */
  public function getModule()
  {
	return $this->module;
  }

  /**
   * @return string
   */
  public function getRegUrl()
  {
	return $this->regUrl;
  }

  /**
   * @return array K
   */
  public function getTabVars(): array
  {
	return $this->tabVars;
  }

}