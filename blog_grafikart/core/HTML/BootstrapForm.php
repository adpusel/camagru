<?php
/**
 * User: adpusel
 * Date: 15/10/2018
 * Time: 10:08
 */

namespace Core\HTML;

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

  public function input(string $name, $label, $options = []): string
  {
	$type = isset($options['type']) ? $options['type'] : 'text';
	$label = '<label>' . $label . '</label>';
	if ($type === 'textarea')
	{
	  $input = '<textarea class="form-control" name="' . $name . '"' .
		">{$this->_getValue($name)}</textarea> ";
	}
	else
	  $input = '<input type="' . $type . '" ' . ' name=' . $name . ' value="' .
		$this->_getValue($name) . '" class="form-control">';

	return $this->_surround($label . $input);
  }

  public function select($name, $label, $option)
  {
	$label = '<label>' . $label . '</label>';
	$input = '<select class="form-control" name="' . $name . '">';
	$attribute = '';


	foreach ($option as $index => $item)
	{
	  if ($index == $this->_getValue($name))
		$attribute = 'selected';

	  $input .= "<option value='$index' $attribute> $item </option>";
	}
	$input .= '</select>';

	return $this->_surround($label . $input);
  }

  public function submit(string $name): string
  {

	return $this->_surround("<input type='submit' class='btn btn-primary' name='$name' value='ok'>");
  }

}