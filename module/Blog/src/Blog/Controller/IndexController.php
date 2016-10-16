<?php
//
namespace Blog\Controller;

use Blog\Entity\Post;
use Blog\Entity\Comment;
use Blog\Entity\Tag;

use Blog\Form\Add;
use Blog\Form\CommentsForm;

use Blog\InputFilter\AddPost;

use Zend\Filter\StaticFilter;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/*
 * PENDIENTE
 * 
 * - TAG CLOUD
 * - Redireccionar a página después de agregar, editar o eliminar post
 * - Permitir Edición de tags
 * - Eliminar post: Incluir alerta de eliminación,
 * validación en caso de existir comentarios asociados al post
 * - Reemplazar route cuando accedes a ruta específica pero no se cuenta con permisos 
 * para realizar la acción
 * - Validación de tags, no agregar tags no adecuados al tema del blog (InputFilter)
 * 
 */

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout('layout/user');
        return new ViewModel(array(
            'paginator' => $this->getBlogService()->fetch($this->params()->fromRoute('page')),
        ));
    }

    public function addAction()
    {
        $this->layout('layout/user');
        if (!$user = $this->identity()) {
            $this->flashMessenger()->addErrorMessage('Debes iniciar sesión para agregar un post');
            return $this->redirect()->toRoute('blog');
        }

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Add($dbAdapter);
        $variables = array('form' => $form);

        if ($this->request->isPost()) {
            $blogPost = new Post();
           
            $form->bind($blogPost);
            $form->setInputFilter(new AddPost());
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                $data = $this->request->getPost();
                $tagsStr=$data['tags'];
                $slugStr=$data['title'];
                $slug=$this->makeSlug($slugStr); 

                
                $post=$this->getBlogService()->save($blogPost, $slug,$user->id);
                $this->addTagsToPost($tagsStr, $post); 
                $this->flashMessenger()->addSuccessMessage('El post ha sido creado');
            }
        }

        return new ViewModel($variables);
    }

    public function viewPostAction()
    {
        $this->layout('layout/user');
         if (!$user = $this->identity()) {
            $this->flashMessenger()->addErrorMessage('Debes iniciar sesión para ver el post');
            return $this->redirect()->toRoute('blog');
        }
        
        $categorySlug = $this->params()->fromRoute('categorySlug');
        $postSlug = $this->params()->fromRoute('postSlug');
        $posted=$this->params()->fromRoute('posted');
        $post = $this->getBlogService()->find($categorySlug, $postSlug,$posted);

        $form=new CommentsForm();
        

        if ($post == null) {
         
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
        }
        //$this->request->isPost()
        if ($this->request->isPost()) {
            $blogPost = new Comment();
            $form->bind($blogPost);
            // $this->request->getPost()
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
 
                $this->getBlogService()->saveComment($blogPost, $user->id,$post->getId());
                //$this->flashMessenger()->addSuccessMessage('The comment has been added!');
            }
        }

        $paginator=$this->getBlogService()->findCommentsByPost($post->getId(),$this->params()->fromRoute('page'));
        $tags=$this->getBlogService()->findTagsByPost($post->getId(),$this->params()->fromRoute('page'));

        return new ViewModel(array(
            'post' => $post,
            'form' => $form,
            'paginator' => $paginator,
            'tags'=>$tags,
        ));
    }


    public function editAction()
    {
        $this->layout('layout/user');
         if (!$user = $this->identity()) {
            $this->flashMessenger()->addErrorMessage('Debes iniciar sesión para editar un post');
            return $this->redirect()->toRoute('blog');
        }
        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Add($dbAdapter);

        if ($this->request->isPost()) {
            $post = new Post();
            $form->bind($post);
         
            $form->setInputFilter(new AddPost());
            $form->setData($this->request->getPost());
             
            if ($form->isValid()) {
                $data=$form->setData($this->request->getPost());
                //$slugStr=$data['title'];
                $slug=$this->makeSlug($slugStr); 
               
                $this->getBlogService()->update($post,$slug);
                $this->flashMessenger()->addSuccessMessage('El post ha sido actualizado');
            }
        } else {
             $categorySlug = $this->params()->fromRoute('categorySlug');
             $posted=$this->params()->fromRoute('posted');
             $postSlug = $this->params()->fromRoute('postSlug');
             $post = $this->getBlogService()->find($categorySlug, $postSlug,$posted);

            if ($post == null) {
                $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
            } else {
                $form->bind($post);
                $form->get('category_id')->setValue($post->getCategory()->getId());
                $form->get('slug')->setValue($post->getSlug());
                $form->get('id')->setValue($post->getId());
            }
        }

        return new ViewModel(array(
            'form' => $form,
        ));
    }

    public function deleteAction()
    {
        $this->layout('layout/user');
         if (!$user = $this->identity()) {
            $this->flashMessenger()->addErrorMessage('Debes iniciar sesión para eliminar un post');
            return $this->redirect()->toRoute('blog');
        }
        
        //crear formulario si/no eliminar
        $categorySlug = $this->params()->fromRoute('categorySlug');
        $posted=$this->params()->fromRoute('posted');
        $postSlug = $this->params()->fromRoute('postSlug');
        $post = $this->getBlogService()->find($categorySlug, $postSlug,$posted);
        
        $this->getBlogService()->delete($post->getId());
        $this->flashMessenger()->addSuccessMessage('El post ha sido eliminado');
        return $this->redirect()->toRoute('blog');
    }
    
    /**
     * Adds/updates tags in the given post.
     */
    private function addTagsToPost($tagsStr, $post) 
    {   
        // array de tags
        $tags = explode(',', strtolower($tagsStr));

        foreach ($tags as $tagName) {
            
            $tagName = StaticFilter::execute($tagName, 'StringTrim');
            if (empty($tagName)) {
                continue; 
            }
            
            $tag=$this->getBlogService()->findTag($tagName);
             
            if ($tag == null)
            {
                $tag = new Tag();
                $tag->setName($tagName);
                $tag->setId($this->getBlogService()->saveTag($tag));
            }
            
            $this->getBlogService()->addTagToPost($tag,$post);
           
            
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
    
    protected function getBlogService()
    {
        return $this->getServiceLocator()->get('Blog\Service\BlogService');
    }
} 