<?php

namespace Courses\Repository;

use Courses\Entity\Hydrator\AuthorHydrator;
use Courses\Entity\Hydrator\UserHydrator;
use Courses\Entity\Hydrator\CategoryHydrator;
use Courses\Entity\Hydrator\CourseHydrator;
use Courses\Entity\Hydrator\TopicHydrator;
use Courses\Entity\Hydrator\CommentHydrator;
use Courses\Entity\Hydrator\ReplyHydrator;
use Courses\Entity\Hydrator\StudentHydrator;
use Blog\Entity\Hydrator\SkillHydrator;
use Blog\Entity\Hydrator\TagHydrator;

use Courses\Entity\Course;
use Courses\Entity\Topic;
use Courses\Entity\Comment;
use Blog\Entity\Tag; 
use Blog\Entity\Skill; 


use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\Hydrator\Aggregate\AggregateHydrator;

class CourseRepositoryImpl implements CourseRepository
{
    use AdapterAwareTrait;
 

    public function save(Course $course,$slug, $authorId)
    {
          
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert()
            ->values(array(
                'title' => $course->getTitle(),
                'slug' => $slug,
                'content' => $course->getContent(),
                'category_id' => $course->getCategory()->getId(),
                'created' => time(),
                'author_id' => $authorId,
            ))
            ->into('course');

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
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
            ->from(array('p' => 'course'))
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
                    'author_nickname' => 'nickname',
                    'author_age' => 'age',
                    'author_bio' => 'bio',
                    'author_gender' => 'gender',
                ),
                $select::JOIN_LEFT
            )
            ->order('p.id DESC');

        $hydrator = new AggregateHydrator();
        $hydrator->add(new CourseHydrator());
        $hydrator->add(new CategoryHydrator());
        $hydrator->add(new AuthorHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Course());
        $paginatorAdapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->adapter, $resultSet);
        $paginator = new \Zend\Paginator\Paginator($paginatorAdapter);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(6);

        return $paginator;
    }

    /**
     * @param $categorySlug string
     * @param $courseSlug string
     *
     * @return Course|null
     */
    public function find($categorySlug,$posted, $courseSlug)
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
            ->from(array('p' => 'course'))
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
                    'author_nickname' => 'nickname',
                    'author_age' => 'age',
                    'author_bio' => 'bio',
                    'author_gender' => 'gender',
                ),
                $select::JOIN_LEFT
            )
            ->where(array(
                'c.slug' => $categorySlug,
                'p.slug' => $courseSlug,
                'p.created' => $posted,
            ));

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $hydrator = new AggregateHydrator();
        $hydrator->add(new CourseHydrator());
        $hydrator->add(new CategoryHydrator());
        $hydrator->add(new AuthorHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Course());
        $resultSet->initialize($results);

        return ($resultSet->count() > 0 ? $resultSet->current() : null);
    }

    /**
     * @param $courseId int
     *
     * @return Course|null
     */
    public function findById($courseId)
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
            ->from(array('p' => 'course'))
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
                    'author_nickname' => 'nickname',
                    'author_age' => 'age',
                    'author_bio' => 'bio',
                    'author_gender' => 'gender',
                ),
                $select::JOIN_LEFT
            )
            ->where(array(
                'p.id' => $courseId,
            ));

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $hydrator = new AggregateHydrator();
        $hydrator->add(new CourseHydrator());
        $hydrator->add(new CategoryHydrator());
        $hydrator->add(new AuthorHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Course());
        $resultSet->initialize($results);

        return ($resultSet->count() > 0 ? $resultSet->current() : null);
    }
    
    /**
     * @param $userId int
     *
     * @return Course|null
     */
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
            ->from(array('p' => 'course'))
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
                    'author_nickname' => 'nickname',
                    'author_age' => 'age',
                    'author_bio' => 'bio',
                    'author_gender' => 'gender',
                ),
                $select::JOIN_LEFT
            )
            ->where(array(
                'p.author_id' => $userId,
            ));

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $hydrator = new AggregateHydrator();
        $hydrator->add(new CourseHydrator());
        $hydrator->add(new CategoryHydrator());
        $hydrator->add(new AuthorHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Course());
        $resultSet->initialize($results);

        return ($resultSet->count() > 0 ? $resultSet->toArray() : null);
    }

    /**
     * @param Course $course
     *
     * @return void
     */
    public function update(Course $course,$slug)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->update('course')
            ->set(array(
                'title' => $course->getTitle(),
                'slug' => $slug,
                'content' => $course->getContent(),
                'category_id' => $course->getCategory()->getId(),
            ))
            ->where(array(
                'id' => $course->getId(),
            ));

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
    }

    /**
     * @param $courseId int
     *
     * @return void
     */
    public function delete($courseId)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $delete = $sql->delete()
            ->from('course')
            ->where(array(
                'id' => $courseId,
            ));

        $statement = $sql->prepareStatementForSqlObject($delete);
        $statement->execute();
    }
    
    /*
     * Topic's actions
     * 
     */
    
    public function saveTopic(Topic $topic,$slug, $authorId,$courseId)
    {
        var_dump($topic);
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert()
            ->values(array(
                'title' => $topic->getTitle(),
                'slug' => $slug,
                'content' => $topic->getContent(),
                //'category_id' => $topic->getCategory()->getId(),
                'created' => time(),
                'author_id' => $authorId,
                'course_id' => $courseId,
            ))
            ->into('topic');

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
        
        $inserted_id =  $this->adapter->getDriver()->getLastGeneratedValue();
        return $inserted_id;
    }
    
    /**
     * @param $categorySlug string
     * @param $courseSlug string
     *
     * @return Course|null
     */
    public function findTopic($topicSlug,$posted)
    {
     $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select();
        $select->columns(array(
                'id',
                'topic_title'=>'title',
                'topic_slug'=>'slug',
                'topic_content'=>'content',
                'created',
            ))
            ->from(array('p' => 'topic'))
            ->join(
                array('c' => 'course'), // Table name
                'c.id = p.course_id', // Condition
                array('course_id' => 'id','course_title'=>'title', 'course_slug' => 'slug'), // Columns
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
                    'author_nickname' => 'nickname',
                    'author_age' => 'age',
                    'author_bio' => 'bio',
                    'author_gender' => 'gender',
                ),
                $select::JOIN_LEFT
            )
            ->where(array(
                'p.slug' => $topicSlug,
                'p.created' => $posted,
            ));

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $hydrator = new AggregateHydrator();
        $hydrator->add(new CourseHydrator());
        $hydrator->add(new CategoryHydrator());
        $hydrator->add(new UserHydrator());
         $hydrator->add(new TopicHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Topic());
        $resultSet->initialize($results);

        return ($resultSet->count() > 0 ? $resultSet->current() : null);
    }
    
     public function fetchTopics()
    {
        
    }

    public function fetchTopicsByCourse($courseId,$page)
        {
             $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select();
        $select->columns(array(
            'id',
            'topic_title'=>'title',
            'topic_slug'=>'slug',
            'topic_content'=>'content',
            'created',
        ))
            ->from(array('p' => 'topic'))
            ->join(
                array('c' => 'course'), // Table name
                'c.id = p.course_id', // Condition
                array('course_id' => 'id', 
                      'course_title'=>'title',
                      'course_slug'=>'slug'), // Columns
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
                    'author_nickname' => 'nickname',
                    'author_age' => 'age',
                    'author_bio' => 'bio',
                    'author_gender' => 'gender',
                ),
                $select::JOIN_LEFT
            )
            ->where(array(
                'p.course_id' => $courseId,
            ));

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $hydrator = new AggregateHydrator();
        $hydrator->add(new TopicHydrator());
        $hydrator->add(new CourseHydrator());
        $hydrator->add(new UserHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Topic());
        $paginatorAdapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->adapter, $resultSet);
        $paginator = new \Zend\Paginator\Paginator($paginatorAdapter);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(120);

        return $paginator;
        }

        public function findTopicById($topicId)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select();
        $select->columns(array(
            'id',
            'topic_title'=>'title',
            'topic_slug'=>'slug',
            'topic_content'=>'content',
            'created',
        ))
            ->from(array('p' => 'topic'))
            ->join(
                array('c' => 'course'), // Table name
                'c.id = p.course_id', // Condition
                array('course_id' => 'id', 'course_title'=>'title', 'course_slug' => 'slug'), // Columns
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
                    'author_nickname' => 'nickname',
                    'author_age' => 'age',
                    'author_bio' => 'bio',
                    'author_gender' => 'gender',
                ),
                $select::JOIN_LEFT
            )
            ->where(array(
                'p.id' => $topicId,
            ));

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $hydrator = new AggregateHydrator();
        $hydrator->add(new CourseHydrator());
        $hydrator->add(new TopicHydrator());
        $hydrator->add(new UserHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Topic());
        $resultSet->initialize($results);

        return ($resultSet->count() > 0 ? $resultSet->current() : null);
    }

    /**
     * @param Course $course
     *
     * @return void
     */
    public function updateTopic(Topic $topic,$slug)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->update('topic')
            ->set(array(
                'title' => $topic->getTitle(),
                'slug' => $slug,
                'content' => $topic->getContent(),
                //'course_id' => $topic->getCourse()->getId(),
            ))
            ->where(array(
                'id' => $topic->getId(),
            ));

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
    }

    /**
     * @param $courseId int
     *
     * @return void
     */
    public function deleteTopic($topicId)
    {
        var_dump($topicId);
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $delete = $sql->delete()
            ->from('topic')
            ->where(array(
                'id' => $topicId,
            ));

        $statement = $sql->prepareStatementForSqlObject($delete);
        $statement->execute();
    }
    
    /*
     * Comments service
     * 
     */
    
    public function saveComment(Comment $comment, $authorId,$topicId)
    {
        //echo $comment->getComment();
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert()
            ->values(array(
                'comment' => $comment->getComment(),
                'topic_id' => $topicId,
                'created' => time(),
                'author_id' => $authorId,
            ))
            ->into('topic_comments');

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
    }
    
    public function deleteComment($commentId)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $delete = $sql->delete()
            ->from('topic_comments')
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
            ->from(array('p' => 'topic_comments'))
            ->join(
                array('c' => 'topic'), // Table name
                'c.id = p.topic_id', // Condition
                array(
                    'topic_id'=>'id', 
                    'topic_title'=>'title', 
                    'topic_content'=>'content',
                    'topic_slug'=>'slug',
                    'topic_created'=>'created',
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
                    'author_nickname' => 'nickname',
                    'author_age' => 'age',
                    'author_bio' => 'bio',
                    'author_gender' => 'gender',
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
        $hydrator->add(new StudentHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Comment());
        $resultSet->initialize($results);

        
        return ($resultSet->count() > 0 ? $resultSet->current() : null);
    }
    
    public function findCommentsByPost($topicId,$page)
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
            ->from(array('p' => 'topic_comments'))
            ->join(
                array('c' => 'topic'), // Table name
                'c.id = p.topic_id', // Condition
                array(
                    'topic_id'=>'id', 
                    'topic_title'=>'title', 
                    'topic_content'=>'content',
                    'topic_slug'=>'slug',
                    'topic_created'=>'created',
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
                    'author_nickname' => 'nickname',
                    'author_age' => 'age',
                    'author_bio' => 'bio',
                    'author_gender' => 'gender',
                ),
                $select::JOIN_LEFT
            )
            ->order('p.id DESC')
            ->where(array(
                'p.topic_id' => $topicId,
            ));


        $hydrator = new AggregateHydrator();
        $hydrator->add(new CommentHydrator());
        $hydrator->add(new ReplyHydrator());
        $hydrator->add(new StudentHydrator());

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
    
    public function addTagToPost(Tag $tag,$topic)
    {

        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert()
            ->values(array(
                'topic_id' => $topic,
                'tag_id' => $tag->getId(),
            ))
            ->into('topic_tag');

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
            
    }
 
    public function findTagsByPost($topicId,$page)
   {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select();
        $select->columns(array(
            'id',
        ))
            ->from(array('p' => 'topic_tag'))
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
                array('a' => 'topic'),
                'a.id = p.topic_id',
                array(
                    'topic_id'=>'id',
                    'title',
                    'slug',
                    'content',
                    'created',
                ),
                $select::JOIN_LEFT
            )
            ->where(array(
                'p.topic_id' => $topicId,
            ));


        $hydrator = new AggregateHydrator();
        $hydrator->add(new TagHydrator());
        $hydrator->add(new SkillHydrator());
        $hydrator->add(new ReplyHydrator());
        $hydrator->add(new TopicHydrator());
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
            ->from(array('p' => 'topic'))
            ->where(array(
                'p.slug' => $slugStr,
            ));

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $hydrator = new AggregateHydrator();
        $hydrator->add(new PostHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Topic());
        $resultSet->initialize($results);
        
        return ($resultSet->count() > 0 ? $resultSet->current() : null);
    }
    
}