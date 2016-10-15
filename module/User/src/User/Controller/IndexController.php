<?php

namespace User\Controller;

use User\Entity\User;
use User\Entity\Career;
use User\Entity\Education;
use User\Entity\Project;
use User\Form\Add;
use User\Form\ProjectForm;
use User\Form\AddJob;
use User\Form\AddEducation;
use User\Form\EditJob;
use User\Form\EditEducation;
use User\Form\Edit;
use User\Form\Login;
use User\Service\UserService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
//use User\Form\InputFilter\InputFilter;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout('layout/user');
        return new ViewModel();
       
    }

    public function addAction()
    {
        $this->layout('layout/user');
        $form = new Add();

        if ($this->request->isPost()) {
            $user = new User();
            $form->bind($user);
            $form->setInputFilter($this->getServiceLocator()->get('User\InputFilter\AddUser'));
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                $user->setUserGroup(UserService::GROUP_REGULAR);
                $this->getUserService()->add($user);
                $this->flashMessenger()->addSuccessMessage('Cuenta creada');
                return $this->redirect()->toRoute('login');
            }
        }

        return new ViewModel(array(
            'form' => $form,
        ));
    }
    
    /*
     * Edit basic info: first name, last name, age, gender & bio
     */
    public function editAction()
    {
       $this->layout('layout/user');
        $form = new Edit();

        if ($this->request->isPost()) {
            $user = new User();
            $form->bind($user);
            $form->setData($this->request->getPost());
            

            if ($form->isValid()) {
                $this->getUserService()->update($user);
                $nickname=$this->getUserService()->findByNickname($this->params()->fromRoute('nickname'));
                $this->flashMessenger()->addSuccessMessage('Información de usuario actualizada');
                return $this->redirect()->toRoute('profile',array('nickname'=>$nickname->getNickname()));
            }
        } else {
            $user = $this->getUserService()->findByNickname($this->params()->fromRoute('nickname'));
            //var_dump($user);
            if ($user == null) {
                $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
            } else {
                $form->bind($user);
                $form->get('firstName')->setValue($user->getFirstName());
                $form->get('lastName')->setValue($user->getLastName());
                $form->get('gender')->setValue($user->getGender());
                $form->get('age')->setValue($user->getAge());
                $form->get('bio')->setValue($user->getBio());
                $form->get('id')->setValue($user->getId());
            }
        }

        return new ViewModel(array(
            'form' => $form,
        )); 
    }
    
    /*
     * Edit user role
     */
    public function editRoleAction()
    {
        $this->layout('layout/user');
        $form = new EditRole();

        if ($this->request->isPost()) {
            $user = new User();
            $form->bind($user);
            $form->setData($this->request->getPost());
            

            if ($form->isValid()) {
                $this->getUserService()->updateRole($user);
                $this->flashMessenger()->addSuccessMessage('Información de usuario actualizada');
                return $this->redirect()->toRoute('management');
            }
        } else {
            $user = $this->getUserService()->findByNickname($this->params()->fromRoute('nickname'));
            //var_dump($user);
            if ($user == null) {
                $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
            } else {
                $form->bind($user);
                $form->get('firstName')->setValue($user->getFirstName());
                $form->get('lastName')->setValue($user->getLastName());
                $form->get('gender')->setValue($user->getGender());
                $form->get('age')->setValue($user->getAge());
                $form->get('bio')->setValue($user->getBio());
                $form->get('id')->setValue($user->getId());
            }
        }

        return new ViewModel(array(
            'form' => $form,
        )); 
    }
     
    /*
     *  Delete user account
     *  Only Admin's role, for now
     */
    public function deleteAction()
    {
        
    }
    
    /*
     * Project options
     */
    public function addProjectAction()
    {
        $this->layout('layout/user');
        if (!$user = $this->identity()) {
            $this->flashMessenger()->addErrorMessage('You must be logged in to add any data on profile');
            return $this->redirect()->toRoute('user');
        }

        $form = new ProjectForm();
        $variables = array('form' => $form);

        if ($this->request->isPost()) {
            $blogPost = new Project();
            $form->bind($blogPost);
            //$form->setInputFilter(new AddJob());
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                
                var_dump($blogPost);
                $this->getUserService()->addProject($blogPost, $user->id);
                
                $this->flashMessenger()->addSuccessMessage('El ...');
            }
        }
        
        return new ViewModel($variables);
    }
    
    public function editProjectAction()
    {
        $this->layout('layout/user');
        $form = new ProjectForm();
        
         if (!$user = $this->identity()) {
            $this->flashMessenger()->addErrorMessage('You must be logged in to add any data on profile');
            return $this->redirect()->toRoute('user');
        }

        if ($this->request->isPost()) {
            $course = new Project();
            $form->bind($course);
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                $this->getUserService()->updateProject($course);
                $this->flashMessenger()->addSuccessMessage('The project has been updated!');
            }
        } else {
            $projectId=$this->params()->fromRoute('projectId');
            var_dump($projectId);
            //$course = $this->getUserService()->findCareerById($this->params()->fromRoute('jobId'));
            //AQUI ME REGRESA NULL
            $course=$this->getUserService()->findProjectById($projectId);
            
            if ($course == null) {
                var_dump($course);
            } else {
                $form->bind($course);
                $form->get('project_type')->setValue($course->getProjectType());
                //$form->get('slug')->setValue($course->getSlug());
                $form->get('id')->setValue($course->getId());
            }
        }

        return new ViewModel(array(
            'form' => $form,
        ));
    
    }
    
    public function deleteProjectAction()
    {
        
    }
    
    /*
     * Education options
     */
    public function addEducationAction()
    {
        $this->layout('layout/user');
        if (!$user = $this->identity()) {
            $this->flashMessenger()->addErrorMessage('You must be logged in to add any data on profile');
            return $this->redirect()->toRoute('user');
        }

        $form = new AddEducation();
        $variables = array('form' => $form);

        if ($this->request->isPost()) {
            $blogPost = new Education();
            $form->bind($blogPost);
            //$form->setInputFilter(new AddJob());
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                
                var_dump($blogPost);
                $this->getUserService()->addEducation($blogPost, $user->id);
                
                $this->flashMessenger()->addSuccessMessage('El ...');
            }
        }
        
        return new ViewModel($variables);
    }
    
    public function editEducationAction()
    {
        $this->layout('layout/user');
        $form = new EditEducation();
        
         if (!$user = $this->identity()) {
            $this->flashMessenger()->addErrorMessage('You must be logged in to add any data on profile');
            return $this->redirect()->toRoute('user');
        }

        if ($this->request->isPost()) {
            $course = new Education();
            $form->bind($course);
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                $this->getUserService()->updateEducation($course);
                $this->flashMessenger()->addSuccessMessage('The job has been updated!');
            }
        } else {
            $educationId=$this->params()->fromRoute('educationId');
            var_dump($educationId);
            //$course = $this->getUserService()->findCareerById($this->params()->fromRoute('jobId'));
            //AQUI ME REGRESA NULL
            $course=$this->getUserService()->findEducationById($educationId);
            
            if ($course == null) {
                var_dump($course);
            } else {
                $form->bind($course);
                $form->get('degree_id')->setValue($course->getDegree()->getId());
                //$form->get('slug')->setValue($course->getSlug());
                $form->get('id')->setValue($course->getId());
            }
        }

        return new ViewModel(array(
            'form' => $form,
        ));
    
    }
    
    public function deleteEducationAction()
    {
        
    }
    
    /*
     * Career options
     */
    public function addJobAction()
    {
        $this->layout('layout/user');
        if (!$user = $this->identity()) {
            $this->flashMessenger()->addErrorMessage('You must be logged in to add any data on profile');
            return $this->redirect()->toRoute('user');
        }

        $form = new AddJob();
        $variables = array('form' => $form);

        if ($this->request->isPost()) {
            $blogPost = new Career();
            $form->bind($blogPost);
            //$form->setInputFilter(new AddJob());
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                
                var_dump($blogPost);
                $this->getUserService()->addCareer($blogPost, $user->id);
                
                $this->flashMessenger()->addSuccessMessage('The job has been added!');
            }
        }
        
        return new ViewModel($variables);
    }
    
    
    public function editJobAction()
    {
        $this->layout('layout/user');
        $form = new EditJob();
        
         if (!$user = $this->identity()) {
            $this->flashMessenger()->addErrorMessage('You must be logged in to add any data on profile');
            return $this->redirect()->toRoute('user');
        }

        if ($this->request->isPost()) {
            $course = new Career();
            $form->bind($course);
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                $this->getUserService()->updateCareer($course);
                $this->flashMessenger()->addSuccessMessage('The job has been updated!');
            }
        } else {
            $jobId=$this->params()->fromRoute('jobId');
            var_dump($jobId);
            //$course = $this->getUserService()->findCareerById($this->params()->fromRoute('jobId'));
            //AQUI ME REGRESA NULL
            $course=$this->getUserService()->findCareerById($jobId);
            
            if ($course == null) {
                var_dump($course);
            } else {
                $form->bind($course);
                //$form->get('category_id')->setValue($course->getCategory()->getId());
                //$form->get('slug')->setValue($course->getSlug());
                $form->get('id')->setValue($course->getId());
            }
        }

        return new ViewModel(array(
            'form' => $form,
        ));
    }
    
    public function deleteJobAction()
    {
        
    }
    

    /*
     * Authentication actions
     */
    public function loginAction()
    {
        $this->layout('layout/user');
        if ($this->identity() != null) {
            $this->flashMessenger()->addInfoMessage('Bienvenido');
            return $this->redirect()->toRoute('user');
        }

        $form = new Login();

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());
            $form->setInputFilter(new \User\InputFilter\Login());

            if ($form->isValid()) {
                $data = $form->getData();
                $loginResult = $this->getUserService()->login($data['email'], $data['password']);

                if ($loginResult) {
                    $this->flashMessenger()->addSuccessMessage('Bienvenido');
                    return $this->redirect()->toRoute('user');
                } else {
                    $this->flashMessenger()->addErrorMessage('Usuario o contraseña inválidos');
                    return $this->redirect()->toRoute('login');
                }
            }
        }

        return new ViewModel(array(
            'form' => $form,
        ));
    }

    public function logoutAction()
    {
        $this->layout('layout/user');
        /**
         * @var \Zend\Authentication\AuthenticationService $authenticationService
         */
        $authenticationService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        $authenticationService->clearIdentity();
        $this->flashMessenger()->addSuccessMessage('Has cerrado sesión');

        return $this->redirect()->toRoute('login');
    }
    
    public function profileAction()
    {
        $this->layout('layout/user');
        if (!$user = $this->identity()) {
            $this->flashMessenger()->addErrorMessage('No estás autorizado para ver perfiles');
            return $this->redirect()->toRoute('user');
        }
        
        //cambiar por nickname
        $userId = $this->params()->fromRoute('nickname');
        $member = $this->getUserService()->findByNickname($userId);
        $career=$this->getUserService()->findCareerByUser($member->getId());
        $education=$this->getUserService()->findEducationByUser($member->getId());
        $project=$this->getUserService()->findProjectByUser($member->getId());
        $course=$this->getCoursesService()->findByUser($member->getId());

        if ($member == null) {
            //$this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
            return $this->redirect()->toRoute('user');
        }
       
        return new ViewModel(array(
            'member' => $member,'career'=>$career,'education'=>$education,'project'=>$project,'course'=>$course,
        ));
    }
    
    
    public function getFileUploadLocation()
    {
        // Fetch Configuration from Module Config
        $config  = $this->getServiceLocator()->get('config');
        return $config['module_config']['upload_location'];
    }
    
    /**
     * @return \User\Service\UserService
     */
    protected function getUserService()
    {
        return $this->getServiceLocator()->get('User\Service\UserService');
    }
    
     protected function getCoursesService()
    {
        return $this->getServiceLocator()->get('Courses\Service\CoursesService');
    }
    
     protected function getUploadsService()
    {
        return $this->getServiceLocator()->get('User\Service\UploadsService');
    }
} 