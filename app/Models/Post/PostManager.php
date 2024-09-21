<?php

namespace App\Models\Post;

use App\Models\Comment\CommentManager;
use App\Models\Database;
use Exception;

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
        try {
            $statement = $this->connection->prepare(
                "SELECT posts.*, users.name FROM posts
            INNER JOIN users 
            ON users.id=posts.user_id 
            WHERE private=FALSE"
            );

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
        } catch (Exception $e) {
            $statement->close();
            return ["type" => "sql-error", "message" => $e->getMessage()];
        }
    }

    public function getUserPosts(int $id)
    {
        try {
            $statement = NULL;

            if ($_SESSION['user_id'] === $id) {
                $statement = $this->connection->prepare("SELECT posts.*, users.name FROM posts
                INNER JOIN users 
                ON users.id=posts.user_id 
                WHERE user_id=?"
                );
            } else {
                $statement = $this->connection->prepare("SELECT posts.*, users.name FROM posts
                INNER JOIN users 
                ON users.id=posts.user_id 
                WHERE user_id=? AND private=FALSE"
                );
            }

            $statement->bind_param("s", $id);
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
        } catch (Exception $e) {
            $statement->close();
            return ["type" => "sql-error", "message" => $e->getMessage()];
        }
    }

    public function getPost(int $id)
    {
        try {
            $statement = $this->connection->prepare("SELECT posts.*, users.name 
                FROM posts
                INNER JOIN users 
                ON posts.user_id=users.id 
                WHERE posts.id = ?"
            );

            $statement->bind_param("s", $id);
            $statement->execute();

            $result = $statement->get_result();

            if ($result->num_rows === 0) {
                throw new Exception("No such record found.");
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
        } catch (Exception $e) {
            $statement->close();
            return ["type" => "error-page", "message" => $e->getMessage()];
        }
    }

    public function getComments(int $id)
    {
        return CommentManager::getInstance()->getComments($id);
    }

    //CUD Methods

    public function create()
    {
        try {
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);
            $private = isset($_POST['private']);

            $statement = $this->connection->prepare("INSERT into posts VALUES (NULL, ?, ?, ?, ?, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");

            if (empty($title)) {
                return ["type" => "post-create-error", "message" => "Enter a title"];
            }

            if (empty($content)) {
                return ["type" => "post-create-error", "message" => "Enter some content"];
            }

            $statement->bind_param("ssss", $_SESSION['user_id'], $title, $content, $private);
            $statement->execute();
            $statement->close();
        } catch (Exception $e) {
            $statement->close();
            return ["type" => "sql-error", "message" => $e->getMessage()];
        }
    }

    public function update(int $id)
    {
        try {
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);
            $private = isset($_POST['private']);

            $statement = $this->connection->prepare("UPDATE posts 
                SET `title` = ?, `content` = ?, `private` = ?, `updated` = CURRENT_TIMESTAMP 
                WHERE posts.id = ? 
                AND posts.user_id = ?"
            );

            if (empty($title)) {
                return ["type" => "post-update-error", "message" => "Enter a title", "id" => $id];
            }

            if (empty($content)) {
                return ["type" => "post-update-error", "message" => "Enter some content", "id" => $id];
            }

            $statement->bind_param(
                "sssss",
                $title,
                $content,
                $private,
                $id,
                $_SESSION['user_id']
            );
            $statement->execute();

            $statement->close();
        } catch (Exception $e) {
            $statement->close();
            return ["type" => "sql-error", "message" => $e->getMessage()];
        }
    }

    public function delete(int $id)
    {
        try {
            $statement = $this->connection->prepare("DELETE From posts WHERE posts.id = ? AND posts.user_id = ?");
            $statement->bind_param("ss", $id, $_SESSION['user_id']);
            $statement->execute();

            $statement->close();
        } catch (Exception $e) {
            $statement->close();
            return ["type" => "sql-error", "message" => $e->getMessage()];
        }
    }
}