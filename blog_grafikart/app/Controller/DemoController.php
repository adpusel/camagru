<?php
/**
 * User: adpusel
 * Date: 25/10/2018
 * Time: 21:20
 */


namespace App\Controller;


use Core\Database\QueryBulder;

class DemoController extends AppController
{
  public function index()
  {
    require ROOT . '/Query.php';
//    $query = new QueryBulder();
    echo \Query::
	select('id','titre','contenu')
	  ->from('article',' Post')
	  ->where('Post.category_id = 1')
	  ->where('Post.date > NOW()');
  }
}