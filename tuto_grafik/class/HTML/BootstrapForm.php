<?php
/**
 * User: adpusel
 * Date: 15/10/2018
 * Time: 10:08
 */

namespace Tuto\HTML;

class BootstrapForm extends Form
{

  /**
   * @param $html
   *
   * @return string
   */
  protected function _surround(string $html)
  {
	return "<div class='form-group'>$html</div>";
  }

  public function input(string $name): string
  {
	return $this->_surround(
	  '<label>' . $name . '</label>' .
	  '<input type="text" ' .
	  ' name=' . $name .
	  ' value="' . $this->_getValue($name) .
	  '" class="form-control">'
	);
  }

  public function submit(string $name): string
  {

	return $this->_surround("<input type='submit' class='btn btn-primary' name='$name' value='ok'>");
  }

}