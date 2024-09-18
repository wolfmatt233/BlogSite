<?php

namespace App\Models\Comment;

use App\Models\Database;

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
        $statement = $this->connection->prepare("SELECT comments.*, 
            users.name FROM comments
            INNER JOIN users 
            ON users.id=comments.user_id 
            WHERE post_id = ?"
        );

        if ($statement === false) {
            return "sql-error";
        }

        $statement->bind_param("s", $post_id);
        $statement->execute();

        if (!$statement->execute()) {
            $statement->close();
            return "sql-error";
        }

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
    }

    public function getComment($id)
    {
        $statement = $this->connection->prepare("SELECT * FROM comments WHERE id = ?");

        if ($statement === false) {
            return "sql-error";
        }

        $statement->bind_param("s", $id);
        $statement->execute();

        if (!$statement->execute()) {
            $statement->close();
            return "sql-error";
        }

        $statement->close();
    }

    //CUD Methods

    public function create($post_id, $content)
    {
        $statement = $this->connection->prepare("INSERT into posts VALUES (NULL, ?, ?, ?, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");

        if ($statement === false) {
            return "sql-error";
        }

        $statement->bind_param("sss", $_SESSION['user_id'], $post_id, $content);
        $statement->execute();

        if (!$statement->execute()) {
            $statement->close();
            return "sql-error";
        }
        $statement->close();
    }

    public function update($id, $title)
    {
        $statement = $this->connection->prepare("UPDATE comments SET `content` = ?, `updated` = CURRENT_TIMESTAMP WHERE comments.id = ? AND posts.user_id = ?");

        if ($statement === false) {
            return "sql-error";
        }

        $statement->bind_param("sss", $title, $id, $_SESSION['user_id']);
        $statement->execute();

        if (!$statement->execute()) {
            $statement->close();
            return "sql-error";
        }

        $statement->close();

    }

    public function delete($id)
    {
        $statement = $this->connection->prepare("DELETE FROM comments WHERE comments.id = ? AND comments.user_id = ?");

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