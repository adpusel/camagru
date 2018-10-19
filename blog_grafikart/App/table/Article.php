<?php
/**
 * User: adpusel
 * Date: 18/10/2018
 * Time: 22:22
 */

namespace App\Table;

use App\App;

// la class est rempli directement avec les data c'est ouf
// je comprends pas vraiment comment ca marche, mais c'est ouf !
class Article extends Table
{
  private static $table = 'article';

  static function getLastArticles()
  {
	return App::getDb()->query(
	  '
		SELECT Article.id, Article.titre, Article.contenu, categorie.titre AS cat
		 FROM article
		LEFT JOIN Categorie 
			ON Article.categorie_id = Categorie.id',
	  __CLASS__);
  }

  public function getExtrait()
  {
	$html = '<p>' . substr($this->contenu, 0, 250) . '... </p>';

	return
	  "<p>" . $html . "</p>"
	  .
	  "<p> 
			<a href=' {$this->getUrl()} '>Voir la suite</a>
		</p>";
  }

  public function getUrl(): string
  {
	return 'index.php?p=' . self::$table . '&id=' . $this->id;
  }
}