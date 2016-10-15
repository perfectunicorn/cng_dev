<?php

namespace Management\Form;

use Management\Entity\Hydrator\TagHydrator;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Stdlib\Hydrator\Aggregate\AggregateHydrator;

class AddCategory extends Form
{
    public function __construct()
    {
        parent::__construct('add');

        $hydrator = new AggregateHydrator();
        $hydrator->add(new TagHydrator());
        $this->setHydrator($hydrator);

        $id = new Element\Hidden('id');
        
        $name = new Element\Text('name');
        $name->setLabel('Etiqueta');
        $name->setAttribute('class', 'form-control');

        $slug = new Element\Text('slug');
        $slug->setLabel('Slug');
        $slug->setAttribute('class', 'form-control');

        $submit = new Element\Submit('submit');
        $submit->setValue('Crear categoría');
        $submit->setAttribute('class', 'btn btn-primary');
        
        $this->add($id);
        $this->add($name);
        $this->add($slug);
        $this->add($submit);
    }
} 