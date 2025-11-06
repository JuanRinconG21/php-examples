<?php
// Front controller de prueba para comprobar que la app muestra posts
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Controller/PostController.php';

$ctrl = new PostController();
$ctrl->index();
