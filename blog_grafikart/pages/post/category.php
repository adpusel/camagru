<?php
/**
 * User: adpusel
 * Date: 20/10/2018
 * Time: 15:36
 */

namespace App;

use App;

// ici je protege contre les movaise id
// faire une reqest qui fait id et cat
$item = App::getTable("Category")->one($_GET['id']);
if ($item === false)
  App::NotFound();

$article = App::getTable("Post")->getLastByCategory($_GET['id']);


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
<!--              <p> --><?//= $article->category ?><!--</p>-->

              <p><?= $article->extrait ?></p>

		  <?php endforeach; ?>
        </ul>
    </div>

    <div class="col-sm-4">
        <ul>
		  <?php foreach (App::getTable('Category')->all() as $cat): ?>
              <li>
                  <a href="<?= $cat->url ?>">
					<?= $cat->titre ?>
                  </a>
              </li>

		  <?php endforeach; ?>
        </ul>

    </div>

</div>



