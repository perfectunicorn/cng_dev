<?php

namespace Courses\Controller;

use Courses\Entity\Course;
use Courses\Entity\Topic;
use Courses\Entity\Comment;
use Blog\Entity\Tag;
use Blog\Entity\Skill;

use Courses\Form\Add;
use Courses\Form\Edit;
use Courses\Form\AddTopic;
use Courses\Form\CommentsForm;
use Courses\Form\EditTopic;

use Courses\InputFilter\AddCourse;
use Courses\InputFilter\TopicFilter;

use Zend\Http\Response;
use Zend\Filter\StaticFilter;
use Zend\Mvc\Controller\AbstractActionController;
//use Zend\Stdlib\Hydrator\Aggregate\AggregateHydrator;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
          $this->layout('layout/user');
        return new ViewModel(array(
            'paginator' => $this->getCoursesService()->fetch($this->params()->fromRoute('page')),
        ));
    }

    public function addAction()
    {
        $this->layout('layout/user');
        if (!$user = $this->identity()) {
            $this->flashMessenger()->addErrorMessage('Debes iniciar sesión para crear cursos');
            return $this->redirect()->toRoute('courses');
        }
        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Add($dbAdapter);
        $variables = array('form' => $form);

        if ($this->request->isPost()) {
            $blogPost = new Course();
            
            $form->bind($blogPost);
            $form->setInputFilter(new AddCourse());
            $form->setData($this->request->getPost());
            var_dump($this->request->getPost());
            if ($form->isValid()) {
                
                $data = $this->request->getPost();
                
                $slugStr=$data['title'];
                $slug=$this->makeSlug($slugStr); 
                
                $course=$this->getCoursesService()->save($blogPost,$slug,$user->id);
                 
                $this->flashMessenger()->addSuccessMessage('El curso ha sido creado');
            }
        }
        
        return new ViewModel($variables);
    }

    public function viewCourseAction()
    {
        $this->layout('layout/user');
        
        if (!$user = $this->identity()) {
            $this->flashMessenger()->addErrorMessage('You must be logged in to view courses');
            return $this->redirect()->toRoute('courses');
        }
        
        $categorySlug = $this->params()->fromRoute('categorySlug');
        $courseSlug = $this->params()->fromRoute('courseSlug');
        $posted = $this->params()->fromRoute('posted');
        $course = $this->getCoursesService()->find($categorySlug, $posted,$courseSlug);
        //var_dump($course->getId());
        $paginator=$this->getCoursesService()->fetchTopicsByCourse($course->getId(),$this->params()->fromRoute('page'));

        if ($course == null) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
        }
        return new ViewModel(array(
            'course' => $course,'paginator'=>$paginator
        ));
    }

    public function editAction()
    {
        $this->layout('layout/user');
          if (!$user = $this->identity()) {
            $this->flashMessenger()->addErrorMessage('Debes iniciar sesión para editar un curso');
            return $this->redirect()->toRoute('blog');
        }
        //$form = new Edit();
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Add($dbAdapter);
        

        if ($this->request->isPost()) {
            $course = new Course();
            $form->bind($course);
            $form->setInputFilter(new AddCourse());
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                $data = $this->request->getPost();
                $slugStr=$data['title'];
                $slug=$this->makeSlug($slugStr);
                $this->getCoursesService()->update($course,$slug);
                $this->flashMessenger()->addSuccessMessage('El curso ha sido actualizado');
            }
        } else {
            $course = $this->getCoursesService()->findById($this->params()->fromRoute('courseId'));

            if ($course == null) {
                $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
            } else {
                $form->bind($course);
                $form->get('category_id')->setValue($course->getCategory()->getId());
                $form->get('slug')->setValue($course->getSlug());
                $form->get('id')->setValue($course->getId());
            }
        }

        return new ViewModel(array(
            'form' => $form,
        ));
    }

    public function deleteAction()
    {
        $this->layout('layout/user');
        $this->getCoursesService()->delete($this->params()->fromRoute('courseId'));
        $this->flashMessenger()->addSuccessMessage('El curso ha sido eliminado');
        return $this->redirect()->toRoute('courses');
    }

    /*
     * Topics actions
     * 
     * 
     */
    public function addTopicAction()
    {
        $this->layout('layout/user');
        if (!$user = $this->identity()) {
            $this->flashMessenger()->addErrorMessage('Debes iniciar sesion para crear lecciones');
            return $this->redirect()->toRoute('courses');
        }
        
        $categorySlug = $this->params()->fromRoute('categorySlug');
        $courseSlug = $this->params()->fromRoute('courseSlug');
        $posted = $this->params()->fromRoute('posted');
        $course = $this->getCoursesService()->find($categorySlug,$posted,$courseSlug);
        
        $form = new AddTopic();
        $variables = array('form' => $form);
       
        if ($this->request->isPost()) {
            $blogPost = new Topic(); 
            
            $form->bind($blogPost);
            //$form->setInputFilter(new TopicFilter());
            $form->setData($this->request->getPost());
            
            if ($form->isValid()) {
                $data = $this->request->getPost();
                $tagsStr=$data['tags'];
                
                //AQUI HUBO ERROR POR LLAMAR A TITLE(FORM) Y NO A TOPIC TITLE
                $slugStr=$data['topic_title'];
                var_dump($slugStr);
                $slug=$this->makeSlug($slugStr); 
                var_dump($slug);
                //var_dump($blogPost);
                //$course = $this->getCoursesService()->findById($this->params()->fromRoute('courseId'));
                $topic=$this->getCoursesService()->saveTopic($blogPost,$slug, $user->id,$course->id);
                $this->addTagsToPost($tagsStr, $topic); 
                $this->flashMessenger()->addSuccessMessage('The topic has been added!');
            }
        }else {
            //$course = $this->getCoursesService()->findById($this->params()->fromRoute('courseId'));
            if ($course == null) {
                $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
            } else {
                $form->bind($course);
                $form->get('courseId')->setValue($course->id);
            }
        }
        
        return new ViewModel($variables);
    }

    public function editTopicAction()
    {
        $this->layout('layout/user');
        $form = new EditTopic();

        if ($this->request->isPost()) {
            $topic = new Topic();
            
            $form->bind($topic);
            $form->setData($this->request->getPost());
                        
            if ($form->isValid()) {
                
                //ERROR no se declaro la variable data
                $data = $this->request->getPost();
                $slugStr=$data['topic_title'];
                $slug=$this->makeSlug($slugStr);
                //ERROR: se puso $course en lugar de $topic 
                $this->getCoursesService()->updateTopic($topic,$slug);
                //$this->getCoursesService()->updateTopic($topic);
                $this->flashMessenger()->addSuccessMessage('The topic has been updated!');
            }
        } else {
            
            $topic = $this->getCoursesService()->findTopicById($this->params()->fromRoute('topicId'));
             //var_dump($topic);
            if ($topic == null) {
                $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
            } else {
                $form->bind($topic);
                $form->get('id')->setValue($topic->getId());
                $form->get('topic_title')->setValue($topic->getTitle());
                $form->get('topic_slug')->setValue($topic->getSlug());
                $form->get('topic_content')->setValue($topic->getContent());
                //$form->get('course_id')->setValue($topic->getCourse()->getId());

            }
        }

        return new ViewModel(array(
            'form' => $form,
        ));
    }

    public function deleteTopicAction()
    {
        $this->layout('layout/user');
        $this->getCoursesService()->deleteTopic($this->params()->fromRoute('topicId'));
        $this->flashMessenger()->addSuccessMessage('The topic has been deleted!');
        return $this->redirect()->toRoute('courses');
    }
    

    public function viewTopicAction()
    {
        $this->layout('layout/user');
        
        if (!$user = $this->identity()) {
            $this->flashMessenger()->addErrorMessage('You must be logged in to view blog');
            return $this->redirect()->toRoute('blog');
        }
        
        $topicSlug = $this->params()->fromRoute('topicSlug');
        $posted = $this->params()->fromRoute('posted');
        $topic = $this->getCoursesService()->findTopic($topicSlug,$posted);
        
        $form=new CommentsForm();
        
        if ($topic == null) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
        }

         if ($this->request->isPost()) {
            $blogPost = new Comment();
            $form->bind($blogPost);
            //var_dump($blogPost);
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
 
                //var_dump($blogPost);
                $this->getCoursesService()->saveComment($blogPost, $user->id,$topic->getId());
                //$this->flashMessenger()->addSuccessMessage('The comment has been added!');
            }
        }

                $paginator=$this->getCoursesService()->findCommentsByPost($topic->getId(),$this->params()->fromRoute('page'));
                $tags=$this->getCoursesService()->findTagsByPost($topic->getId(),$this->params()->fromRoute('page'));
                
               
        return new ViewModel(array(
            'topic' => $topic,
            'tags'=>$tags,
            'form' => $form,
            'paginator' => $paginator,
        ));

        /*return new ViewModel(array(
            'topic' => $topic,
        ));*/
    }
    
        /**
     * Adds/updates tags in the given post.
     */
    private function addTagsToPost($tagsStr, $topic) 
    {   
        // array de tags
        $tags = explode(',', strtolower($tagsStr));

        foreach ($tags as $tagName) {
            
            //ERROR POR NO IMPORTAR EL STATIC FILTER
            $tagName = StaticFilter::execute($tagName, 'StringTrim');
            if (empty($tagName)) {
                continue; 
            }
            
            $tag=$this->getCoursesService()->findTag($tagName);
             
            if ($tag == null)
            {
                $tag = new Tag();
                $tag->setName($tagName);
                $tag->setId($this->getCoursesService()->saveTag($tag));
            }
            
            $this->getCoursesService()->addTagToPost($tag,$topic);
           
            
        }
    }
    
      private function makeSlug($slugStr) 
    {   
        $value = strtolower($slugStr);
        $separator="-";

        if (function_exists('iconv')) {
            $value = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
        }

        $value = preg_replace("/[^[a-z0-9]+/", ' ', $value);
        $value = trim($value);
        $value = preg_replace("/[\s]/", $separator, $value);
        
        return $value;
           
    } 
    
    /**
     * @return \Courses\Service\CoursesService $coursesService
     */
    protected function getCoursesService()
    {
        return $this->getServiceLocator()->get('Courses\Service\CoursesService');
    }
} 