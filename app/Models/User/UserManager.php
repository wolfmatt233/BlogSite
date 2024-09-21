<?php

namespace App\Models\User;

use App\Models\Database;
use Exception;

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
        try {
            $statement = $this->connection->prepare("SELECT * FROM users");
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
        } catch (Exception $e) {
            $statement->close();
            return ["type" => "sql-error", "message" => $e->getMessage()];
        }
    }

    public function getUser($name)
    {
        try {
            $statement = $this->connection->prepare("SELECT * FROM users WHERE name=?");
            $statement->bind_param("s", $name);
            $statement->execute();

            $result = $statement->get_result();
            $obj = $result->fetch_object();
            $statement->close();

            return ['result' => $result, 'object' => $obj];
        } catch (Exception $e) {
            $statement->close();
            return ["type" => "sql-error", "message" => $e->getMessage()];
        }
    }

    public function nameCheck($name)
    {
        try {
            $statement = $this->connection->prepare("SELECT * FROM users WHERE name=?");
            $statement->bind_param("s", $name);
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();

            return $result->num_rows > 0 ? true : false;
        } catch (Exception $e) {
            $statement->close();
            return ["type" => "error-page", "message" => $e->getMessage()];
        }
    }

    public function create($name, $password)
    {
        $nameCheck = $this->nameCheck($name);

        if ($nameCheck === true) {
            return ["type" => "signup-error", "message" => "Username taken"];
        }

        if (empty($name)) {
            return ["type" => "signup-error", "message" => "Enter a username"];
        }

        if (empty($password)) {
            return ["type" => "signup-error", "message" => "Enter a password"];
        }

        try {
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            $name = ucfirst($name);
            
            $statement = $this->connection->prepare(
                "INSERT INTO users VALUES (NULL, ?, ?, 0)"
            );
            $statement->bind_param("ss", $name, $hashPassword);
            $statement->execute();
            $statement->close();
        } catch (Exception $e) {
            $statement->close();
            return ["type" => "error-page", "message" => $e->getMessage()];
        }
    }

    public function signin($name, $password)
    {
        $user = $this->getUser($name);
        $obj = $user['object'];

        if ($user['result']->num_rows === 0) {
            return ["type" => "login-error", "message" => "Username invalid"];
        } else if (!password_verify($password, $obj->password)) {
            return ["type" => "login-error", "message" => "Password invalid"];
        }

        session_start();
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $name;
        $_SESSION['admin'] = $obj->admin;
        $_SESSION['user_id'] = $obj->id;
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