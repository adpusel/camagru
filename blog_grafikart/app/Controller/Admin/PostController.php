<?php
/**
 * User: adpusel
 * Date: 25/10/2018
 * Time: 15:42
 */


namespace App\Controller\Admin;

use App;
use Core\HTML\BootstrapForm;

class PostController extends AppController
{
  public function __construct()
  {
	parent::__construct();
	$this->loadModel('Post');
	$this->loadModel('Category');
  }

  public function index()
  {
	$posts = $this->Post->all();
	$this->render('admin.post.home', compact('posts'));
  }

  public function add()
  {
	if (!empty($_POST))
	{
	  $res = $this->Post->create(
		[
		  'titre'       => $_POST['titre'],
		  'contenu'     => $_POST['contenu'],
		  'category_id' => $_POST['category_id']
		]);
	  if ($res)
	  {
		header('Location: index.php?p=admin.post.edit&id=' .
		  App::getDb()->lastInsertId());
	  }
	  else
		var_dump($res);
	};

	$category = $this->Category->extract('id', 'titre');

// hydrate le form avec le data de $post
	$form = new BootstrapForm($_POST);
	$this->render('admin.post.manage_post', compact('category', 'form'));
  }

  public function edit()
  {
	if (!empty($_POST))
	{
	  $res = $this->Post->update($_GET['id'],
		[
		  'titre'       => $_POST['titre'],
		  'contenu'     => $_POST['contenu'],
		  'category_id' => $_POST['category_id']
		]);
	  if ($res)
		$this->index();
	  else
		var_dump($res);
	};
// get les category
	$category = $this->Category->extract('id', 'titre');
// get la table
	$post = $this->Post->one($_GET['id']);

// hydrate le form avec le data de $post
	$form = new BootstrapForm($post);

	$this->render('admin.post.manage_post', compact('form', 'category'));
  }

  public function delete()
  {
	$res = $this->Post->delete($_POST['id']);
	if ($res)
	  header('Location: ?p=admin.post.index');
	else
	  var_dump($res);
  }

}