<?php
/**
 * User: adpusel
 * Date: 19/10/2018
 * Time: 15:28
 */

namespace App\Table;

class Categorie extends Table
{
  private static $table = 'categorie';

  public function getUrl(): string
  {
	return 'index.php?p=' . self::$table . '&id=' . $this->id;
  }

}