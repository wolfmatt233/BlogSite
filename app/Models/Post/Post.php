<?php

namespace App\Models\Post;

class Post
{
    private $id, $user_id, $title, $content, $private, $likes, $created, $updated, $name;

    public function __construct($user_id, $title, $content, $private, $likes, $created, $updated)
    {
        $this->user_id = $user_id;
        $this->title = $title;
        $this->content = $content;
        $this->private = $private;
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

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getPrivate()
    {
        return $this->private;
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