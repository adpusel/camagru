<?php
/**
 * User: adpusel
 * Date: 20/10/2018
 * Time: 15:36
 */

use App\App;
use App\Table\Article;
use App\Table\Categorie;

// ici je protege contre les movaise id
$item = Categorie::find($_GET['id']);
if ($item === false)
  App::NotFound();

$article = Article::lastByCategory($_GET['id']);
$cats = Categorie::getAll();

var_dump($item);

?>

<h1><?= $item->titre ?></h1>
<div class="row">
    <div class="col-sm-8">
        <ul>
		  <?php foreach ($article as $article): ?>
              <!--	--><?php //var_dump($article); ?>

              <h2>
                  <a href="<?= $article->url ?>"><?= $article->titre ?></a>
              </h2>
              <p> <?= $article->cat ?></p>

              <p><?= $article->extrait ?></p>

		  <?php endforeach; ?>
        </ul>
    </div>

    <div class="col-sm-4">
        <ul>
		  <?php foreach ($cats as $item): ?>
              <li>
                  <a href="<?= $item->url ?>">
					<?= $item->titre ?>
                  </a>
              </li>

		  <?php endforeach; ?>
        </ul>

    </div>


</div>



