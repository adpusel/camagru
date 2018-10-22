<?php
/**
 * User: adpusel
 * Date: 21/10/2018
 * Time: 12:27
 */


namespace App\Table;

use Core\Table\Table;

class PostTable extends Table
{
  public function last()
  {
	return $this->query("
	SELECT Post.id, Post.titre, Post.contenu, Post.create_at, 
	Category.titre AS categorie
	FROM Post
	LEFT JOIN Category ON categorie_id = Category.id
	ORDER BY Post.create_at DESC 
	");
  }
}