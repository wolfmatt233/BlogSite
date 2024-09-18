<?php

namespace App\Models\User;

use App\Models\Database;

class UserManager
{
    private $db, $connection;

    static private $instance = NULL;

    public function __construct()
    {
        $this->db = Database::getDatabase();
        $this->connection = $this->db->getConnection();

        //Escapes special characters in a string to stop SQL injection in POST vars.
        foreach ($_POST as $key => $value) {
            $_POST[$key] = $this->connection->real_escape_string($value);
        }

        //Escapes special characters in a string to stop SQL injection in GET vars
        foreach ($_GET as $key => $value) {
            $_GET[$key] = $this->connection->real_escape_string($value);
        }
    }

    public static function getInstance()
    {
        if (self::$instance == NULL) {
            self::$instance = new UserManager();
        }

        return self::$instance;

    }

    public function getUsers()
    {
        $statement = $this->connection->prepare("SELECT * FROM users");

        if ($statement === false) {
            return "sql-error";
        } else {
            $statement->execute();
            $results = $statement->get_result();

            $users = [];

            while ($obj = $results->fetch_object()) {
                $user = new User($obj->name, $obj->password, $obj->admin);
                $user->setId($obj->id);
                $users[] = $user;
            }

            $statement->close();

            return $users;


        }
    }

    public function nameCheck($name)
    {
        $statement = $this->connection->prepare("SELECT * FROM users WHERE name=?");

        if ($statement === false) {
            return "sql-error";
        }

        $statement->bind_param("s", $name);
        $statement->execute();
        $result = $statement->get_result();
        $statement->close();

        return $result->num_rows > 0 ? "name-error" : false;

    }

    public function create($name, $password)
    {
        $nameCheck = $this->nameCheck($name);

        if ($nameCheck !== false) {
            return $nameCheck;
        }

        if (empty($name)) {
            return "name-error-signup";
        }

        if (empty($password)) {
            return "password-error-signup";
        }

        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        $statement = $this->connection->prepare(
            "INSERT INTO users VALUES (NULL, ?, ?, 0)"
        );

        if ($statement === false) {
            return "sql-error";
        }

        $statement->bind_param("ss", $name, $hashPassword);
        $statement->execute();
        $statement->close();
        return true;
    }

    public function signin($name, $password)
    {
        $statement = $this->connection->prepare("SELECT * FROM users WHERE name=?");

        if ($statement === false) {
            return "sql-error";
        }

        $statement->bind_param("s", $name);
        $statement->execute();
        $result = $statement->get_result();

        $obj = $result->fetch_object();

        if ($result->num_rows === 0) {
            return "name-error-login";
        } else if (!password_verify($password, $obj->password)) {
            return "password-error-login";
        }

        session_start();
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $name;
        $_SESSION['admin'] = $obj->admin;
        $_SESSION['user_id'] = $obj->id;

        $statement->close();

        return true;
    }

    public function logout()
    {
        session_start();
        unset($_SESSION['loggedin']);
        unset($_SESSION['name']);
        unset($_SESSION['admin']);
        unset($_SESSION['user_id']);
        session_destroy();
    }
}