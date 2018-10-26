<?php
/**
 * User: adpusel
 * Date: 26/10/2018
 * Time: 14:37
 */

use Event\App;
use Event\Emitter;

define('ROOT', dirname(__DIR__));

require 'App.php';

App::load();

$emitter = Emitter::getIstance();

$emitter->on('Comment.created', function ($firstname, $name)
{
    echo $firstname . ' a post un nouveau commentaire';
    return;
});


$emitter->emit('Comment.created', 'John', 'Doe');