<?php

use App\Controllers\MainController;
use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Router;

$router = new Router();

//Main
$router->get('/', MainController::class, 'home');
$router->get('/home', MainController::class, 'home');
$router->get('/error', MainController::class, 'error');

// Users
$router->get('/users/signup', UserController::class, 'signup');
$router->post('/users/create', UserController::class, 'create');
$router->get('/users/login', UserController::class, 'login');
$router->post('/users/signin', UserController::class, 'signin');
$router->get('/users/logout', UserController::class, 'logout');

// Users: Admin only
$router->get('/users', UserController::class, 'index');

//Posts: Users only
$router->get('/posts', PostController::class, 'index');
$router->get('/posts/users/{id}', PostController::class, 'user');
$router->get('/posts/{id}', PostController::class, 'view');
$router->get('/posts/create', PostController::class, 'createForm');
$router->post('/posts/upload', PostController::class, 'create');
$router->get('/posts/{id}/edit', PostController::class, 'edit');
$router->post('/posts/{id}/update', PostController::class, 'update');
$router->get('/posts/{id}/delete', PostController::class, 'delete');

$router->dispatch();