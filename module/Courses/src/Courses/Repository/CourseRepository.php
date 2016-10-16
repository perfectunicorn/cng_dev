<?php

namespace Courses\Repository;

use Application\Repository\RepositoryInterface;
use Courses\Entity\Course;
use Courses\Entity\Topic;
use Courses\Entity\Comment;
use Blog\Entity\Tag;
use Blog\Entity\Skill;

interface CourseRepository extends RepositoryInterface
{
    /**
     * Saves a course
     *
     * @param Course $course
     * @param int $authorId
     *
     * @return void
     */
    public function save(Course $course,$slug, $authorId);

    /**
     * @param $page int
     *
     * @return \Zend\Paginator\Paginator
     */
    public function fetch($page);

    /**
     * @param $categorySlug string
     * @param $courseSlug string
     *
     * @return Course|null
     */
    public function find($categorySlug,$posted, $courseSlug);

    /**
     * @param $courseId int
     *
     * @return Course|null
     */
    public function findById($courseId);
    
     /**
     * @param $userId int
     *
     * @return Course|null
     */
    public function findByUser($userId);
    
    

    /**
     * @param Course $course
     *
     * @return void
     */
    public function update(Course $course,$slug);

    /**
     * @param $courseId int
     *
     * @return void
     */
    public function delete($courseId);
    
    /*
     * Topic's actions
     * 
     */
    
    /**
     * Saves a topic
     *
     * @param Topic $topic
     * @param int $authorId
     *
     * @return void
     */
    public function saveTopic(Topic $topic, $slug,$authorId,$userId);
    
      /**
     * @param $categorySlug string
     * @param $courseSlug string
     *
     * @return Course|null
     */
    public function findTopic($topicSlug,$posted);
    
       /**
     * @param $page int
     *
     * @return \Zend\Paginator\Paginator
     */
    public function fetchTopics();

     public function fetchTopicsByCourse($courseId,$page);
     
     public function findTopicById($topicId);

    /**
     * @param Course $course
     *
     * @return void
     */
    public function updateTopic(Topic $topic,$slug);

    /**
     * @param $courseId int
     *
     * @return void
     */
    public function deleteTopic($topicId);
    
    public function saveComment(Comment $comment, $authorId,$topicId);
    
    public function deleteComment($commentId);
    
    public function findCommentById($commentId);
    
    public function findCommentsByPost($topicId,$page);
    
     /*
     * Tags
     */
    
    public function saveTag(Tag $tag);
    
    public function findTag($tagName);
    
    public function addTagToPost(Tag $tag,$topic);
    
    public function findTagsByPost($topicId,$page);


}