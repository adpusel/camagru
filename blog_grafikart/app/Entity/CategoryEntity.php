<?php
/**
 * User: adpusel
 * Date: 22/10/2018
 * Time: 10:56
 */


namespace App\Entity;

use Core\Entity\Entity;

class CategoryEntity extends Entity
{
  public function getUrl()
  {
	return 'index.php?p=post.category&id=' . $this->id;
  }
}