<?php

namespace App\Controllers;

use App\Controller;
use App\Models\Comment\CommentManager;

class CommentController extends Controller
{
    private $comment;

    public function __construct()
    {
        $loggedIn = $this->checkLogged();

        if ($loggedIn === true) {
            $this->comment = CommentManager::getInstance();
        } else {
            header("location: /users/login");
        }
    }

    public function getComments($id)
    {
        $comments = $this->comment->getComments($id);
        $error = $this->errorHandles($comments);

        if ($error === false) {
            $totalPages = ceil(count($comments) / 10);
            $posts = $this->paginate($comments);

            $this->render('posts', 'index', [
                'title' => 'Browse Posts',
                'posts' => $posts,
                'pages' => $totalPages
            ]);
        }
    }

    public function getComment($id)
    {
        $comment = $this->comment->getPost($id);
        $error = $this->errorHandles($comment);

        if ($error === false) {
            $this->render('posts', 'view', [
                'title' => 'View Post',
                'post' => $comment
            ]);
        }
    }

    public function create($post_id)
    {
        $comment = $this->comment->create($post_id, trim($_POST['content']));
        $error = $this->errorHandles($comment);

        if ($error === false) {
            header("location: /posts/$post_id");
        }
    }

    public function update($post_id)
    {
        $comment = $this->comment->update($post_id, trim($_POST['content']));
        $error = $this->errorHandles($comment);

        if ($error === false) {
            header("location: /posts/$post_id");
        }
    }

    public function delete($post_id)
    {
        $comment = $this->comment->delete($post_id);
        $error = $this->errorHandles($comment);

        if ($error === false) {
            header("location: /posts/$post_id");
        }
    }
}