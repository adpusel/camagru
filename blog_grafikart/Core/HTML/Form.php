<?php
/**
 * User: adpusel
 * Date: 14/10/2018
 * Time: 22:57
 */

namespace Core\HTML;

/**
 * Class Form
 *
 * permet de generer un form plus rapidement et plus proprement
 */
class Form
{

  /**
   * @var array
   */
  protected $_data;

  /**
   * @var string le tag utiliser pour entourer le tag
   */
  public $_surround = 'p';


  /**
   * Form constructor.
   *
   * @param array $data $_POST    pour hydrater le form
   */
  public function __construct($data = array())
  {
	$this->_data = $data;
  }


  /**
   * @param $html
   *
   * @return string
   */
  protected function _surround(string $html)
  {
	return "<{$this->_surround}>$html</{$this->_surround}>";
  }


  /**
   * @param $index
   *
   * @return mixed|null
   */
  protected function _getValue( $index)
  {
	if (is_object($this->_data))
	    return $this->_data->$index;
    return isset($this->_data[$index]) ? $this->_data[$index] : null;
  }


  /**
   * @param $name
   *
   * @return string
   */
  public function input(string $name, $label, $options = []): string
  {
	$type = isset($options['type']) ? $options['type'] : 'text';

    return $this->_surround("<input autocomplete='off' type='text' name='$name' value='{$this->_getValue($name)}'>");
  }

  /**
   * @param $name
   *
   * @return string
   */
  public function submit(string $name): string
  {
	return $this->_surround("<input type='submit' name='$name' value='ok'>");
  }


}