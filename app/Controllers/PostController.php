<?php

namespace App\Controllers;

use App\Controller;
use App\Models\Post\PostManager;

class PostController extends Controller
{
    private $post;

    public function __construct()
    {
        session_status() === PHP_SESSION_NONE ? session_start() : "";
        $this->post = PostManager::getInstance();
    }

    public function index()
    {
        $posts = $this->post->getPosts();
        $error = $this->errorHandles($posts);

        if ($error === false) {
            $totalPages = ceil(count($posts) / 10);
            $posts = $this->paginate($posts);

            $this->render('posts', 'index', [
                'title' => 'Browse Posts',
                'posts' => $posts,
                'pages' => $totalPages
            ]);
        }
    }

    public function personal()
    {
        $posts = $this->post->getPersonalPosts();
        $error = $this->errorHandles($posts);

        if ($error === false) {
            $totalPages = ceil(count($posts) / 10);
            $posts = $this->paginate($posts);

            $this->render('posts', 'index', [
                'title' => 'Your Posts',
                'posts' => $posts,
                'pages' => $totalPages
            ]);
        }
    }

    public function view($id)
    {
        $post = $this->post->getPost($id);
        $comments = $this->post->getComments($id);

        var_dump($post);

        $error = $this->errorHandles($post);
        $error = $this->errorHandles($comments);

        if ($error === false) {
            $totalPages = ceil(count($comments) / 10);
            $comments = $this->paginate($comments);

            $this->render('posts', 'view', [
                'title' => 'View Post',
                'post' => $post,
                'comments' => $comments,
                'pages' => $totalPages
            ]);
        }
    }

    public function createForm()
    {
        $this->render('posts', 'create', ['title' => 'Create Post']);
    }

    public function create()
    {
        $post = $this->post->create(
            trim($_POST['title']),
            trim($_POST['content']),
            isset($_POST['private'])
        );

        $error = $this->errorHandles($post);

        if ($error === false) {
            header("location: /posts");
        }
    }

    public function edit($id)
    {
        $post = $this->post->getPost($id);
        $error = $this->errorHandles($post);

        if ($error === false) {
            $this->render('posts', 'edit', ['title' => 'Edit Post', 'post' => $post]);
        }
    }

    public function update($id)
    {
        $post = $this->post->update(
            $id,
            trim($_POST['title']),
            trim($_POST['content']),
            isset($_POST['private'])
        );

        $error = $this->errorHandles($post);

        if ($error === false) {
            header("location: /posts/personal?page=1");
        }
    }

    public function delete($id)
    {
        $post = $this->post->delete($id);
        $error = $this->errorHandles($post);

        if ($error === false) {
            header("location: /posts/personal?page=1");
        }
    }
}