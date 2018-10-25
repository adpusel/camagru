<?php
/**
 * User: adpusel
 * Date: 21/10/2018
 * Time: 12:27
 */


namespace App\Table;

use Core\Entity\Entity;
use Core\Table\Table;

class PostTable extends Table
{
  public function last()
  {
	return $this->query("
	SELECT Post.id, Post.titre, Post.contenu, Post.create_at, 
	Category.titre AS categorie
	FROM Post
	LEFT JOIN Category ON category_id = Category.id
	ORDER BY Post.create_at DESC 
	");
  }

  public function getLastByCategory($id)
  {
	return $this->query(
	  'SELECT Post.*
	  FROM Post 
	  LEFT JOIN category 
	  ON category.id = Post.category_id
	  WHERE category_id = ?',
	  [$id]
	);
  }
}