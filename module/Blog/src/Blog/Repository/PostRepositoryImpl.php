<?php

namespace Blog\Repository;
//

use Blog\Entity\Hydrator\AuthorHydrator;
use Blog\Entity\Hydrator\CategoryHydrator;
use Blog\Entity\Hydrator\PostHydrator;
use Blog\Entity\Hydrator\TagHydrator;
use Blog\Entity\Hydrator\SkillHydrator;
use Blog\Entity\Hydrator\CommentHydrator;
use Blog\Entity\Hydrator\ReplyHydrator;
use Blog\Entity\Hydrator\UserHydrator;
use Blog\Entity\Post; 
use Blog\Entity\Tag; 
use Blog\Entity\Skill; 
use Blog\Entity\Comment;

use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\Hydrator\Aggregate\AggregateHydrator;

class PostRepositoryImpl implements PostRepository
{
    use AdapterAwareTrait;

    public function save(Post $post,$slug, $authorId)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert()
            ->values(array(
                'title' => $post->getTitle(),
                'slug' => $slug,
                'content' => $post->getContent(),
                'category_id' => $post->getCategory()->getId(),
                'created' => time(),
                'author_id' => $authorId,
            ))
            ->into('post');

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
        
        $inserted_id =  $this->adapter->getDriver()->getLastGeneratedValue();
        return $inserted_id;
    }

    public function fetch($page)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select();
        $select->columns(array(
                'id',
                'title',
                'slug',
                'content',
                'created',
            ))
            ->from(array('p' => 'post'))
            ->join(
                array('c' => 'category'), // Table name
                'c.id = p.category_id', // Condition
                array('category_id' => 'id', 'name', 'category_slug' => 'slug'), // Columns
                $select::JOIN_INNER
            )
            ->join(
                array('a' => 'user'),
                'a.id = p.author_id',
                array(
                    'author_id' => 'id',
                    'author_first_name' => 'first_name',
                    'author_last_name' => 'last_name',
                    'author_email' => 'email',
                    'author_created' => 'created',
                    'author_user_group' => 'user_group',
                ),
                $select::JOIN_LEFT
            )
            ->order('p.id DESC');

        $hydrator = new AggregateHydrator();
        $hydrator->add(new PostHydrator());
        $hydrator->add(new CategoryHydrator());
        $hydrator->add(new AuthorHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Post());
        $paginatorAdapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->adapter, $resultSet);
        $paginator = new \Zend\Paginator\Paginator($paginatorAdapter);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(12);

        return $paginator;
    }

    /**
     * @param $categorySlug string
     * @param $postSlug string
     *
     * @return Post|null
     */
    public function find($categorySlug, $postSlug,$posted)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select();
        $select->columns(array(
                'id',
                'title',
                'slug',
                'content',
                'created',
            ))
            ->from(array('p' => 'post'))
            ->join(
                array('c' => 'category'), // Table name
                'c.id = p.category_id', // Condition
                array('category_id' => 'id', 'name', 'category_slug' => 'slug'), // Columns
                $select::JOIN_INNER
            )
            ->join(
                array('a' => 'user'),
                'a.id = p.author_id',
                array(
                    'author_id' => 'id',
                    'author_first_name' => 'first_name',
                    'author_last_name' => 'last_name',
                    'author_email' => 'email',
                    'author_created' => 'created',
                    'author_user_group' => 'user_group',
                    'nickname',
                ),
                $select::JOIN_LEFT
            )
            ->where(array(
                'c.slug' => $categorySlug,
                'p.slug' => $postSlug,
                'p.created'=>$posted,
            ));

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $hydrator = new AggregateHydrator();
        $hydrator->add(new PostHydrator());
        $hydrator->add(new CategoryHydrator());
        $hydrator->add(new AuthorHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Post());
        $resultSet->initialize($results);

        return ($resultSet->count() > 0 ? $resultSet->current() : null);
    }

    /**
     * @param $postId int
     *
     * @return Post|null
     */
    public function findById($postId)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select();
        $select->columns(array(
            'id',
            'title',
            'slug',
            'content',
            'created',
        ))
            ->from(array('p' => 'post'))
            ->join(
                array('c' => 'category'), // Table name
                'c.id = p.category_id', // Condition
                array('category_id' => 'id', 'name', 'category_slug' => 'slug'), // Columns
                $select::JOIN_INNER
            )
            ->join(
                array('a' => 'user'),
                'a.id = p.author_id',
                array(
                    'author_id' => 'id',
                    'author_first_name' => 'first_name',
                    'author_last_name' => 'last_name',
                    'author_email' => 'email',
                    'author_created' => 'created',
                    'author_user_group' => 'user_group',
                    'nickname',
                ),
                $select::JOIN_LEFT
            )
            ->where(array(
                'p.id' => $postId,
            ));

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $hydrator = new AggregateHydrator();
        $hydrator->add(new PostHydrator());
        $hydrator->add(new CategoryHydrator());
        $hydrator->add(new AuthorHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Post());
        $resultSet->initialize($results);

        return ($resultSet->count() > 0 ? $resultSet->current() : null);
    }
    
    public function findByUser($userId)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select();
        $select->columns(array(
            'id',
            'title',
            'slug',
            'content',
            'created',
        ))
            ->from(array('p' => 'post'))
            ->join(
                array('c' => 'category'), // Table name
                'c.id = p.category_id', // Condition
                array('category_id' => 'id', 'name', 'category_slug' => 'slug'), // Columns
                $select::JOIN_INNER
            )
            ->join(
                array('a' => 'user'),
                'a.id = p.author_id',
                array(
                    'author_id' => 'id',
                    'author_first_name' => 'first_name',
                    'author_last_name' => 'last_name',
                    'author_email' => 'email',
                    'author_created' => 'created',
                    'author_user_group' => 'user_group',
                    'nickname',
                ),
                $select::JOIN_LEFT
            )
            ->where(array(
                'a.id' => $userId,
            ))
            ->order('p.created DESC');;

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $hydrator = new AggregateHydrator();
        $hydrator->add(new PostHydrator());
        $hydrator->add(new CategoryHydrator());
        $hydrator->add(new AuthorHydrator());
        $page=1;
        $resultSet = new HydratingResultSet($hydrator, new Post());
        $paginatorAdapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->adapter, $resultSet);
        $paginator = new \Zend\Paginator\Paginator($paginatorAdapter);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(1000);

        return $paginator;
    }

    /**
     * @param Post $post
     *
     * @return void
     */
    public function update(Post $post,$slug)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->update('post')
            ->set(array(
                'title' => $post->getTitle(),
                'slug' => $slug,
                'content' => $post->getContent(),
                'category_id' => $post->getCategory()->getId(),
            ))
            ->where(array(
                'id' => $post->getId(),
            ));

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
    }

    /**
     * @param $postId int
     *
     * @return void
     */
    public function delete($postId)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $delete = $sql->delete()
            ->from('post')
            ->where(array(
                'id' => $postId,
            ));

        $statement = $sql->prepareStatementForSqlObject($delete);
        $statement->execute();
    }
    
     /*
     * Comments service
     * 
     */
    
    public function saveComment(Comment $comment, $authorId,$postId)
    {
        
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert()
            ->values(array(
                'comment' => $comment->getComment(),
                'post_id' => $postId,
                'created' => time(),
                'author_id' => $authorId,
            ))
            ->into('post_comments');

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
    }
    
    public function deleteComment($commentId)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $delete = $sql->delete()
            ->from('post_comments')
            ->where(array(
                'id' => $commentId,
            ));

        $statement = $sql->prepareStatementForSqlObject($delete);
        $statement->execute();
    }
    
    public function findCommentById($commentId)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select();
        $select->columns(array(
            'id',
            'comment',
            'created',
        ))
            ->from(array('p' => 'post_comments'))
            ->join(
                array('c' => 'post'), // Table name
                'c.id = p.post_id', // Condition
                array(
                    'post_id'=>'id', 
                    'title', 
                    'content',
                    'slug',
                    'created',
                    ), // Columns
                $select::JOIN_INNER
            )
            ->join(
                array('a' => 'user'),
                'a.id = p.author_id',
                array(
                    'author_id' => 'id',
                    'author_first_name' => 'first_name',
                    'author_last_name' => 'last_name',
                    'author_email' => 'email',
                    'author_created' => 'created',
                    'author_user_group' => 'user_group',
                    'nickname',
                ),
                $select::JOIN_LEFT
            )
            ->where(array(
                'p.id' => $commentId,
            ));

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $hydrator = new AggregateHydrator();
        $hydrator->add(new PostHydrator());
        $hydrator->add(new CommentHydrator());
        $hydrator->add(new ReplyHydrator());
        $hydrator->add(new AuthorHydrator());
        $hydrator->add(new UserHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Comment());
        $resultSet->initialize($results);

        
        return ($resultSet->count() > 0 ? $resultSet->current() : null);
    }
    
    public function findCommentsByPost($postId,$page)
   {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select();
        $select->columns(array(
            'id',
            //'author_id',
            //'post_id',
            'comment',
            'created',
        ))
            ->from(array('p' => 'post_comments'))
            ->join(
                array('c' => 'post'), // Table name
                'c.id = p.post_id', // Condition
                array(
                    'post_id'=>'id', 
                    'title', 
                    'content',
                    'slug',
                    //'created',
                    ), // Columns
                $select::JOIN_INNER
            )
            ->join(
                array('a' => 'user'),
                'a.id = p.author_id',
                array(
                    'author_id'=>'id',
                    'author_first_name'=>'first_name',
                    'author_last_name'=>'last_name',
                    'author_email'=>'email',
                    'author_created'=>'created',
                    'author_user_group'=>'user_group',
                    'nickname',
                ),
                $select::JOIN_LEFT
            )
            ->order('p.id DESC')
            ->where(array(
                'p.post_id' => $postId,
            ));


        $hydrator = new AggregateHydrator();
        $hydrator->add(new CommentHydrator());
        $hydrator->add(new ReplyHydrator());
        $hydrator->add(new UserHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Comment());
        $paginatorAdapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->adapter, $resultSet);
        $paginator = new \Zend\Paginator\Paginator($paginatorAdapter);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(1000);
        
        return $paginator;

    }
    
    /*
     * 
     * Tags
     * 
     * 
     */
    
    public function saveTag(Tag $tag)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert()
            ->values(array(
                'name' => $tag->getName(),
            ))
            ->into('tag');

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
        
        $inserted_id =  $this->adapter->getDriver()->getLastGeneratedValue();
        
        return $inserted_id;
    }
    
    
    public function findTag($tagName)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select();
        $select->columns(array(
            'id',
            'name'
        ))
            ->from(array('p' => 'tag'))
            ->where(array(
                'p.name' => $tagName,
            ));

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $hydrator = new AggregateHydrator();
        $hydrator->add(new TagHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Tag());
        $resultSet->initialize($results);
        
        return ($resultSet->count() > 0 ? $resultSet->current() : null);
    }
    
    public function addTagToPost(Tag $tag,$post)
    {

        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert()
            ->values(array(
                'post_id' => $post,
                'tag_id' => $tag->getId(),
            ))
            ->into('post_tag');

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
            
    }
 
    public function findTagsByPost($postId,$page)
   {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select();
        $select->columns(array(
            'id',
        ))
            ->from(array('p' => 'post_tag'))
            ->join(
                array('c' => 'tag'), // Table name
                'c.id = p.tag_id', // Condition
                array(
                    'tag_id'=>'id', 
                    'tag_name'=>'name', 
                    //'created',
                    ), // Columns
                $select::JOIN_INNER
            )
            ->join(
                array('a' => 'post'),
                'a.id = p.post_id',
                array(
                    'post_id'=>'id',
                    'title',
                    'slug',
                    'content',
                    'created',
                ),
                $select::JOIN_LEFT
            )
            ->where(array(
                'p.post_id' => $postId,
            ));


        $hydrator = new AggregateHydrator();
        $hydrator->add(new TagHydrator());
        $hydrator->add(new SkillHydrator());
        $hydrator->add(new ReplyHydrator());
        $hydrator->add(new PostHydrator());
        $hydrator->add(new CategoryHydrator());
        $hydrator->add(new UserHydrator());
  
        
        $resultSet = new HydratingResultSet($hydrator, new Skill());
        $paginatorAdapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->adapter, $resultSet);
        $paginator = new \Zend\Paginator\Paginator($paginatorAdapter);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(1000);
        
        return $paginator;

    }
    
    
    public function findSlug($tagsStr)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select();
        $select->columns(array(
                'id', 
                'title', 
                'content',
                'slug',
                'created',
        ))
            ->from(array('p' => 'post'))
            ->where(array(
                'p.slug' => $slugStr,
            ));

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $hydrator = new AggregateHydrator();
        $hydrator->add(new PostHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Post());
        $resultSet->initialize($results);
        
        return ($resultSet->count() > 0 ? $resultSet->current() : null);
    }
  
}