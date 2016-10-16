<?php

namespace Courses\Form;

use Courses\Entity\Hydrator\CategoryHydrator;
use Courses\Entity\Hydrator\CourseHydrator;
use Courses\Entity\Hydrator\TopicHydrator;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Stdlib\Hydrator\Aggregate\AggregateHydrator;

class AddTopic extends Form
{
    public function __construct()
    {
        parent::__construct('add');

        $hydrator = new AggregateHydrator();
        $hydrator->add(new CourseHydrator());
        $hydrator->add(new TopicHydrator());
        $hydrator->add(new CategoryHydrator());
        $this->setHydrator($hydrator);

        $id = new Element\Hidden('id');
        $courseId = new Element\Hidden('courseId');
      
        $title = new Element\Text('topic_title');
        $title->setLabel('Título');
        $title->setAttribute('class', 'form-control');

        $slug = new Element\Text('topic_slug');
        $slug->setLabel('Slug');
        $slug->setAttribute('class', 'form-control');

        $content = new Element\Textarea('topic_content');
        $content->setLabel('Contenido');
        $content->setAttributes(array(
           'class'=> 'form-control' ,
            'id'=>'topic_content'
        ));
        
        $tags= new Element\Text('tags');
        $tags->setLabel('Tags');
        $tags->setAttribute('class', 'form-control');
        $tags->setAttribute('placeholder', 'html,javascript,css');

        
        $submit = new Element\Submit('submit');
        $submit->setValue('Publicar');
        $submit->setAttribute('class', 'btn btn-primary');

        $this->add($id);
        $this->add($courseId);
        $this->add($title);
        $this->add($slug);
        $this->add($content);
        $this->add($tags);
        $this->add($submit);
    }
} 