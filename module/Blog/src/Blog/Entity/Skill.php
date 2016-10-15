<?php

namespace Blog\Entity;

use Blog\Entity\Post;
use Blog\Entity\Tag;

class Skill
{

    protected $id;

    protected $post;

    protected $tag;
    

    public function getId() 
    {
        return $this->id;
    }

    public function setId($id) 
    {
        $this->id = $id;
    }
    
    /**
     * @param \User\Entity\Post $post
     */
    public function setPost(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @return \User\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }
    
    /**
     * @param \User\Entity\Tag $tag
     */
    public function setTag(Tag $tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return \User\Entity\tag
     */
    public function getTag()
    {
        return $this->tag;
    }
}

