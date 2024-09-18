<?php

namespace App\Models\User;

class User
{
    private $id, $name, $password, $admin;

    public function __construct($name, $password, $admin)
    {
        $this->name = $name;
        $this->password = $password;
        $this->admin = $admin;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getAdmin()
    {
        return $this->admin;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
}