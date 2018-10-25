<?php
/**
 * User: adpusel
 * Date: 25/10/2018
 * Time: 11:22
 */


namespace App\Controller;

use App;

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
	$post = $this->Post->last();
	$cats = $this->Category->all();

	$this->render('post.home', compact('post', 'cats'));
  }

  public function category()
  {
	$item = $this->Category->one($_GET['id']);

	if ($item === false)
	  $this->NotFound();

	$cats = $this->Category->all();
	$articles = $this->Post->getLastByCategory($_GET['id']);

	$this->render('post.category', compact('cats', 'articles'));
  }

  public function single()
  {
	$post = $this->Post->one($_GET['id']);
	$this->render('post.single', compact('post'));
  }

}