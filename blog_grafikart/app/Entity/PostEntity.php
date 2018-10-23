<?php
/**
 * User: adpusel
 * Date: 22/10/2018
 * Time: 10:56
 */


namespace App\Entity;

use Core\Entity\Entity;

class PostEntity extends Entity
{
  public function getUrl()
  {
	return 'index.php?p=post.single&id=' . $this->id;
  }

  public function getExtrait()
  {
	$html = '<p>' . substr($this->contenu, 0, 100) . '...</p>';
	$html .= '<p><a href="' . $this->getUrl() . '"> Voir la suite </a>';
	return $html;
  }


}