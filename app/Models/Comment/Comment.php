<?php

namespace App\Models\Comment;

class Comment
{
    private $id, $user_id, $post_id, $content, $likes, $created, $updated, $name;

    public function __construct($user_id, $post_id, $content, $likes, $created, $updated)
    {
        $this->user_id = $user_id;
        $this->post_id = $post_id;
        $this->content = $content;
        $this->likes = $likes;
        $this->created = $created;
        $this->updated = $updated;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getPostId()
    {
        return $this->post_id;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getLikes()
    {
        return $this->likes;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
}