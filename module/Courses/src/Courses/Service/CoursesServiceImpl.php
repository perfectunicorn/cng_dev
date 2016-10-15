<?php

namespace Courses\Service;

use Courses\Entity\Course;
use Courses\Entity\Topic;
use Courses\Entity\Comment;

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
    public function save(Course $course, $authorId)
    {
        $this->courseRepository->save($course, $authorId);
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
    public function find($categorySlug, $courseSlug)
    {
        return $this->courseRepository->find($categorySlug, $courseSlug);
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
    public function update(Course $course)
    {
        $this->courseRepository->update($course);
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
    public function saveTopic(Topic $course, $authorId,$courseId)
    {
        $this->courseRepository->saveTopic($course, $authorId,$courseId);
    }
    
       /**
     * @param $categorySlug string
     * @param $courseSlug string
     *
     * @return Course|null
     */
    public function findTopic($topicSlug)
    {
        return $this->courseRepository->findTopic($topicSlug);
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
    public function updateTopic(Topic $topic)
    {
        $this->courseRepository->updateTopic($topic);
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
        $this->courseRepository->saveComment($comment, $authorId,$topicId);
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


}