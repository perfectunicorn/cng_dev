<?php

namespace Courses\Service;

use Courses\Entity\Course;
use Courses\Entity\Topic;
use Courses\Entity\Comment;
use Blog\Entity\Tag;

class CoursesServiceImpl implements CoursesService
{
    /**
     * @var \Blog\Repository\CourseRepository $courseRepository
     */
    protected $courseRepository;


    /**
     * Saves a course
     *
     * @param Course $course
     * @param int $authorId
     *
     * @return Course
     */
    public function save(Course $course,$slug, $authorId)
    {
        return $this->courseRepository->save($course,$slug, $authorId);
    }

    /**
     * @param $page int
     *
     * @return \Zend\Paginator\Paginator
     */
    public function fetch($page)
    {
        return $this->courseRepository->fetch($page);
    }

    /**
     * @param $categorySlug string
     * @param $courseSlug string
     *
     * @return Course|null
     */
    public function find($categorySlug,$posted, $courseSlug)
    {
        return $this->courseRepository->find($categorySlug,$posted, $courseSlug);
    }

    /**
     * @param $courseId int
     *
     * @return Post|null
     */
    public function findById($courseId)
    {
        return $this->courseRepository->findById($courseId);
    }
    
    /**
     * @param $userId int
     *
     * @return Post|null
     */
    public function findByUser($userId)
    {
        return $this->courseRepository->findByUser($userId);
    }

    /**
     * @param Course $course
     *
     * @return void
     */
    public function update(Course $course,$slug)
    {
        $this->courseRepository->update($course,$slug);
    }

    /**
     * @param $courseId int
     *
     * @return void
     */
    public function delete($courseId)
    {
        $this->courseRepository->delete($courseId);
    }

    /**
     * @param \Courses\Repository\CourseRepository $courseRepository
     */
    public function setCoursesRepository($courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * @return \Courses\Repository\CourseRepository
     */
    public function getCoursesRepository()
    {
        return $this->courseRepository;
    }
    
    /*
     * Topic actions
     * 
     */
    
    /**
     * Saves a topic
     *
     * @param Topic $topic
     * @param int $authorId
     *
     * @return Topic
     */
    public function saveTopic(Topic $course,$slug, $authorId,$courseId)
    {
        
        //ERROR AL GUARDAR EN TOPIC_TAG PORQUE EN EL SERVICIO NO SE ESTABA RETORNANDO
        //EL VALOR DE ID DEL REGISTRO INSERTADO
       return $this->courseRepository->saveTopic($course,$slug, $authorId,$courseId);
    }
    
       /**
     * @param $categorySlug string
     * @param $courseSlug string
     *
     * @return Course|null
     */
    public function findTopic($topicSlug,$posted)
    {
        return $this->courseRepository->findTopic($topicSlug,$posted);
    }
    
       /**
     * @param $page int
     *
     * @return \Zend\Paginator\Paginator
     */
    public function fetchTopics()
    {
        return $this->courseRepository->fetchTopics();
    }
    
     public function fetchTopicsByCourse($courseId,$page)
    {
        return $this->courseRepository->fetchTopicsByCourse($courseId,$page);
    }
    
 
    /**
     * @param $courseId int
     *
     * @return Post|null
     */
    public function findTopicById($topicId)
    {
        return $this->courseRepository->findTopicById($topicId);
    }

    /**
     * @param Course $course
     *
     * @return void
     */
    public function updateTopic(Topic $topic,$slug)
    {
        $this->courseRepository->updateTopic($topic,$slug);
    }

    /**
     * @param $courseId int
     *
     * @return void
     */
    public function deleteTopic($topicId)
    {
        $this->courseRepository->deleteTopic($topicId);
    }
    
    /*
     * Comments service
     * 
     */
    
    public function saveComment(Comment $comment, $authorId,$topicId)
    {
        return $this->courseRepository->saveComment($comment, $authorId,$topicId);
    }
    
    public function deleteComment($commentId)
    {
        $this->courseRepository->deleteComment($commentId);
    }
    
    public function findCommentById($commentId)
    {
        return $this->courseRepository->findCommentById($commentId);
    }
    
    public function findCommentsByPost($topicId,$page)
    {
        return $this->courseRepository->findCommentsByPost($topicId,$page);
    }
    
    /*
     * Tags service
     * 
    */
    
    public function saveTag(Tag $tag)
    {
        return $this->courseRepository->saveTag($tag);
    }
    
    
    public function findTag($tagName)
    {
        return $this->courseRepository->findTag($tagName);
    }
    
    public function addTagToPost(Tag $tag,$topic)
    {
        $this->courseRepository->addTagToPost($tag,$topic);
    }
    
     public function findTagsByPost($topicId,$page)
    {
        return $this->courseRepository->findTagsByPost($topicId,$page);
    }


}