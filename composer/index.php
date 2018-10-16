<?php
/**
 * Created by PhpStorm.
 * User: adpusel
 * Date: 10/16/18
 * Time: 11:00 AM
 */

require "vendor/autoload.php";
use \Michelf\Markdown;

// je me repete bcq car je dois faire les deux c'est relou
require "app/helper/form";
echo \app\helper\Form::input();


echo Markdown::defaultTransform("Salut tout le monde j'essaie le **markdown**");