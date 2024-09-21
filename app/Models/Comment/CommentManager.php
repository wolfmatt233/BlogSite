<?php

namespace App\Models\Comment;

use App\Models\Database;
use Exception;

class CommentManager
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
            self::$instance = new CommentManager();
        }

        return self::$instance;
    }

    //Get Methods

    public function getComments($post_id)
    {
        try {
            $statement = $this->connection->prepare("SELECT comments.*, 
            users.name FROM comments
            INNER JOIN users 
            ON users.id=comments.user_id 
            WHERE post_id = ?"
            );

            $statement->bind_param("s", $post_id);
            $statement->execute();

            $result = $statement->get_result();

            $comments = [];

            while ($obj = $result->fetch_object()) {
                $comment = new Comment(
                    $obj->user_id,
                    $obj->post_id,
                    stripslashes($obj->content),
                    $obj->likes,
                    $obj->created,
                    $obj->updated
                );

                $comment->setId($obj->id);
                $comment->setName($obj->name);

                $comments[] = $comment;
            }

            $statement->close();

            return $comments;
        } catch (Exception $e) {
            $statement->close();
            return ["type" => "error-page", "message" => $e->getMessage()];
        }
    }

    public function getComment($id)
    {
        try {
            $statement = $this->connection->prepare("SELECT * FROM comments WHERE id = ?");

            $statement->bind_param("s", $id);
            $statement->execute();
            $result = $statement->get_result();

            if ($result->num_rows === 0) {
                throw new Exception("No such record found.");
            }

            $obj = $result->fetch_object();

            $comment = new Comment(
                $obj->user_id,
                $obj->post_id,
                stripslashes($obj->content),
                $obj->likes,
                $obj->created,
                $obj->updated
            );

            $comment->setId($obj->id);
            $comment->setName($obj->name);

            $statement->close();

            return $comment;
        } catch (Exception $e) {
            $statement->close();
            return ["type" => "error-page", "message" => $e->getMessage()];
        }
    }

    //CUD Methods

    public function create($post_id, $content)
    {
        try {
            $statement = $this->connection->prepare("INSERT into posts VALUES (NULL, ?, ?, ?, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");

            if (empty($content)) {
                return ["type" => "comment-create-error", "message" => "Enter some content"];
            }

            $statement->bind_param("sss", $_SESSION['user_id'], $post_id, $content);
            $statement->execute();

            $statement->close();
        } catch (Exception $e) {
            $statement->close();
            return ["type" => "error-page", "message" => $e->getMessage()];
        }
    }

    public function update($id, $title)
    {
        try {
            $statement = $this->connection->prepare("UPDATE comments SET `content` = ?, `updated` = CURRENT_TIMESTAMP WHERE comments.id = ? AND posts.user_id = ?");

            $statement->bind_param("sss", $title, $id, $_SESSION['user_id']);
            $statement->execute();

            $statement->close();
        } catch (Exception $e) {
            $statement->close();
            return ["type" => "error-page", "message" => $e->getMessage()];
        }

    }

    public function delete($id)
    {
        try {
            $statement = $this->connection->prepare("DELETE FROM comments WHERE comments.id = ? AND comments.user_id = ?");

            $statement->bind_param("ss", $id, $_SESSION['user_id']);
            $statement->execute();

            $statement->close();
        } catch (Exception $e) {
            $statement->close();
            return ["type" => "error-page", "message" => $e->getMessage()];
        }
    }
}