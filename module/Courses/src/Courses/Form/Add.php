<?php

namespace Courses\Form;

use Courses\Entity\Hydrator\CategoryHydrator;
use Courses\Entity\Hydrator\CourseHydrator;
use Zend\Form\Form;
use Zend\Form\Element;

use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Stdlib\Hydrator\Aggregate\AggregateHydrator;

class Add extends Form
{
    
    use AdapterAwareTrait;
    
    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->adapter =$dbAdapter;
        parent::__construct('add');

        /*$hydrator = new AggregateHydrator();
        $hydrator->add(new CourseHydrator());
        $hydrator->add(new CategoryHydrator());
        $this->setHydrator($hydrator);*/

        $id = new Element\Hidden('id');
        $hydrator = new AggregateHydrator();
        $hydrator->add(new CourseHydrator());
        $hydrator->add(new CategoryHydrator());
        $this->setHydrator($hydrator);

        $title = new Element\Text('title');
        $title->setLabel('Nombre del curso');
        $title->setAttribute('class', 'form-control');

        $slug = new Element\Text('slug');
        $slug->setLabel('Slug');
        $slug->setAttribute('class', 'form-control');

        $content = new Element\Textarea('content');
        $content->setLabel('Descripción');
        $content->setAttribute('class', 'form-control');

        $category = new Element\Select('category_id');
        $category->setLabel('Categoría');
        $category->setAttribute('class', 'input-field');
        $category->setOptions(array(
                        'label' => 'Categoría',
                        'empty_option' => 'Seleccione una categoría',
                        'value_options' => $this->getOptionsForSelect(),
                ));

        $submit = new Element\Submit('submit');
        $submit->setValue('Crear clase');
        $submit->setAttribute('class', 'btn btn-primary');

        
        $this->add($id);
        $this->add($title);
        $this->add($slug);
        $this->add($content);
        $this->add($category);
        $this->add($submit);
    }
    
    public function getOptionsForSelect()
    {
        $dbAdapter= $this->adapter;
        $sql='SELECT id,name  FROM category ORDER BY id ASC';
        $statement= $dbAdapter->query($sql);
        $result= $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id']] = $res['name'];
        }
        return $selectData;
    }
} 