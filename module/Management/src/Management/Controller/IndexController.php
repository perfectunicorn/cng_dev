<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Management\Controller;

use Management\Entity\Category;
use Management\Form\AddCategory;
use Management\Entity\Tag;
use Management\Form\AddTag;
use Management\Service\ManagementService;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Response;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout('layout/user');
        return new ViewModel();
    }
    
    public function addCategoryAction()
    {
       $this->layout('layout/user');
        if (!$user = $this->identity()) {
            $this->flashMessenger()->addErrorMessage('You must be logged in to add categories');
            return $this->redirect()->toRoute('home');
        }
        
        $form = new AddCategory();
        $variables = array('form' => $form);
       

        if ($this->request->isPost()) {
            $blogPost = new Category(); 
            $form->bind($blogPost);
             
            //$form->setInputFilter(new TopicFilter());
            $form->setData($this->request->getPost());
            
            if ($form->isValid()) {
                //var_dump($blogPost);
                //$course = $this->getCoursesService()->findById($this->params()->fromRoute('courseId'));
                $this->getManagementService()->saveCategory($blogPost);
                $this->flashMessenger()->addSuccessMessage('The category has been added!');
            }

        }
        
        return new ViewModel($variables);
        
    }
    
    public function editCategoryAction()
    {
        return new ViewModel();
    }
    
    public function deleteCategoryAction()
    {
        return new ViewModel();
    }
    
    public function viewCategoriesAction()
    {
        return new ViewModel();
    }
    
    
    
    public function addTagAction()
    {
       $this->layout('layout/user');
        if (!$user = $this->identity()) {
            $this->flashMessenger()->addErrorMessage('You must be logged in to add tags');
            return $this->redirect()->toRoute('home');
        }
        
        $form = new AddTag();
        $variables = array('form' => $form);
       

        if ($this->request->isPost()) {
            $blogPost = new Tag(); 
            $form->bind($blogPost);
             
            //$form->setInputFilter(new TopicFilter());
            $form->setData($this->request->getPost());
            
            if ($form->isValid()) {
                //var_dump($blogPost);
                //$course = $this->getCoursesService()->findById($this->params()->fromRoute('courseId'));
                $this->getManagementService()->saveTag($blogPost);
                $this->flashMessenger()->addSuccessMessage('The tag has been added!');
            }

        }
        
        return new ViewModel($variables);
        
    }
    
    /**
     * @return \Management\Service\ManagementService $managementService
     */
 protected function getManagementService()
    {
        return $this->getServiceLocator()->get('Management\Service\ManagementService');
    }
}
