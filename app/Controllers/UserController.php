<?php

namespace App\Controllers;

use App\Controller;
use App\Models\User\UserManager;

class UserController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->user = UserManager::getInstance();
    }

    public function index()
    {
        $users = $this->user->getUsers();

        $this->render('users', 'index', ['users' => $users, 'title' => 'Users']);
    }

    public function signup()
    {
        $this->render('users', 'signup', ['title' => 'Sign Up']);
    }

    public function create()
    {
        $create = "";
        $name = trim($_POST['name']);
        $password = trim($_POST['password']);

        $create = $this->user->create($name, $password);

        $error = $this->errorHandles($create);

        if ($error === false) {
            $this->user->signin($name, $password);
            header('location: /home?m=Account creation successful');
        }
    }

    public function login()
    {
        $this->render('users', 'login', ['title' => 'Log In']);
    }

    public function signin()
    {
        $login = "";
        $name = trim($_POST['name']);
        $password = trim($_POST['password']);

        $login = $this->user->signin($name, $password);

        $error = $this->errorHandles($login);

        if ($error === false) {
            header('location: /home?m=Login successful');
        }
    }

    public function logout()
    {
        $this->user->logout();
        $this->errorHandles("Log out successful.");
        header('location: /');
    }
}