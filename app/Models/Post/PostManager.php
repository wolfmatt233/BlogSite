<?php

namespace App\Models\Post;

use App\Models\Comment\CommentManager;
use App\Models\Database;

class PostManager
{
    private $db, $connection;

    static private $instance = NULL;

    public function __construct()
    {
        $this->db = Database::getDatabase();
        $this->connection = $this->db->getConnection();

        foreach ($_POST as $key => $value) {
            $_POST[$key] = $this->connection->real_escape_string($value);
        }

        foreach ($_GET as $key => $value) {
            $_GET[$key] = $this->connection->real_escape_string($value);
        }
    }

    public static function getInstance()
    {
        if (self::$instance == NULL) {
            self::$instance = new PostManager();
        }

        return self::$instance;
    }

    //Get Methods

    public function getPosts()
    {
        $statement = $this->connection->prepare(
            "SELECT posts.*, users.name FROM posts
            INNER JOIN users 
            ON users.id=posts.user_id 
            WHERE private=FALSE"
        );

        if ($statement === false) {
            return "sql-error";
        }

        $statement->execute();
        $result = $statement->get_result();

        $posts = [];

        while ($obj = $result->fetch_object()) {
            $post = new Post(
                $obj->user_id,
                stripslashes($obj->title),
                stripslashes($obj->content),
                $obj->private,
                $obj->likes,
                $obj->created,
                $obj->updated
            );

            $post->setId($obj->id);
            $post->setName($obj->name);

            $posts[] = $post;
        }

        $statement->close();

        return array_reverse($posts, true);
    }

    public function getPersonalPosts()
    {
        $statement = $this->connection->prepare(
            "SELECT posts.*, users.name FROM posts
            INNER JOIN users 
            ON users.id=posts.user_id 
            WHERE user_id=?"
        );

        if ($statement === false) {
            return "sql-error";
        }

        $statement->bind_param("s", $_SESSION['user_id']);
        $statement->execute();
        $result = $statement->get_result();

        $posts = [];

        while ($obj = $result->fetch_object()) {
            $post = new Post(
                $obj->user_id,
                stripslashes($obj->title),
                stripslashes($obj->content),
                $obj->private,
                $obj->likes,
                $obj->created,
                $obj->updated
            );

            $post->setId($obj->id);
            $post->setName($obj->name);

            $posts[] = $post;
        }

        return array_reverse($posts, true);
    }

    public function getPost($id)
    {
        $statement = $this->connection->prepare("SELECT posts.*, users.name 
            FROM posts
            INNER JOIN users 
            ON posts.user_id=users.id 
            WHERE posts.id = ? AND user_id = ?"
        );

        if ($statement === false) {
            return "sql-error";
        }

        $statement->bind_param("ss", $id, $_SESSION['user_id']);
        $statement->execute();

        if (!$statement->execute()) {
            $statement->close();
            return "sql-error";
        }

        $result = $statement->get_result();

        if ($result->num_rows === 0) {
            if (!is_int($id)) {
                return "page-error";
            }
        }

        $obj = $result->fetch_object();

        $post = new Post(
            $obj->user_id,
            stripslashes($obj->title),
            stripslashes($obj->content),
            $obj->private,
            $obj->likes,
            $obj->created,
            $obj->updated
        );

        $post->setId($obj->id);
        $post->setName($obj->name);

        $statement->close();

        return $post;
    }

    public function getComments($id)
    {
        return CommentManager::getInstance()->getComments($id);
    }

    //CUD Methods

    public function create($title, $content, $private)
    {
        $statement = $this->connection->prepare("INSERT into posts VALUES (NULL, ?, ?, ?, ?, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");

        if ($statement === false) {
            return "sql-error";
        } else if (empty($title) || empty($content)) {
            return "empty-error";
        } else {
            $statement->bind_param("ssss", $_SESSION['user_id'], $title, $content, $private);
            $statement->execute();
            $statement->close();
        }
    }

    public function update($id, $title, $content, $private)
    {
        $statement = $this->connection->prepare("UPDATE posts SET `title` = ?, `content` = ?, `private` = ?, `updated` = CURRENT_TIMESTAMP WHERE posts.id = ? AND posts.user_id = ?");

        if ($statement === false) {
            return "sql-error";
        }

        $statement->bind_param("sssss", $title, $content, $private, $id, $_SESSION['user_id']);
        $statement->execute();

        if (!$statement->execute()) {
            $statement->close();
            return "sql-error";
        }

        $statement->close();
    }

    public function delete($id)
    {
        $statement = $this->connection->prepare("DELETE From posts WHERE posts.id = ? AND posts.user_id = ?");

        if ($statement === false) {
            return "sql-error";
        }

        $statement->bind_param("ss", $id, $_SESSION['user_id']);
        $statement->execute();

        if (!$statement->execute()) {
            $statement->close();
            return "sql-error";
        }

        $statement->close();
    }
}