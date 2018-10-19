<?php

namespace App;

use App\Table\Article;
use App\Table\Categorie;

?>

<div class="row">
    <div class="col-sm-8">
        <ul>
		  <?php foreach (Article::getLastArticles() as $article): ?>
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
		  <?php foreach (Categorie::getAll() as $cat): ?>
              <li>
                  <a href="<?= $cat->url ?>">
                      <?= $cat->titre ?>
                  </a>
              </li>

		  <?php endforeach; ?>
        </ul>

    </div>


</div>

