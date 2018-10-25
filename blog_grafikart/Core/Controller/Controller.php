<?php
/**
 * User: adpusel
 * Date: 25/10/2018
 * Time: 11:20
 */


namespace Core\Controller;


class Controller
{
  protected $_viewPath;
  protected $_template;

  protected function render(string $view, $variables = [])
  {
	ob_start();
	extract($variables);
	require $this->_viewPath . str_replace('.', '/', $view) . '.php';

	$content = ob_get_clean();
	require $this->_viewPath . 'templates/' . $this->_template . '.php';
  }
}