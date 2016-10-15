<?php

namespace Blog\Entity;
use SplObjectStorage;
use Blog\Entity\Category;
use User\Entity\User;


class Post 
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var int
     */
    protected $created;

    /**
     * @var Category
     */
    protected $category;

    /**
     * @var \User\Entity\User
     */
    protected $author;
    
    protected $tags;


    public function __construct() 
    {       
        $this->tags = new SplObjectStorage();       
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
     * @param \Blog\Entity\Category $category
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    /**
     * @return \Blog\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
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
    public function setAuthor(User $author)
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
    
     /**
     * Returns tags for this post.
     * @return array
     */
    
    /*public function getTags() 
    {
        return $this->tags;
    }    
    
    public function setTags() 
    {
        $this->tags->attach($tag);        
    
    } 
    

    public function addTag($tag) 
    {
        $this->tags->attach($tag);        
    }
    
    public function removeTagAssociation($tag) 
    {
        $this->tags->detach($tag);
    }*/
}