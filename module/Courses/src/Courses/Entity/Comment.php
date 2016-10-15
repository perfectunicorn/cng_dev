<?php
//
namespace Courses\Entity;

class Comment
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $comment;

    /**
     * @var int
     */
    protected $created;
    
     /**
     * @var \User\Entity\Topic
     */
    protected $topic;

    /**
     * @var \User\Entity\User
     */
    protected $author;



    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }


    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

  /**
     * @param \Courses\Entity\Topic $topic
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
    }

    /**
     * @return \Courses\Entity\Topic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param int $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return int
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \User\Entity\User $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return \User\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }
}