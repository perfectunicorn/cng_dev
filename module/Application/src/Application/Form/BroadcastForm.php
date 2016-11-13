<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;

/*use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\Hydrator\Aggregate\AggregateHydrator;*/

class BroadcastForm extends Form
{

    public function __construct()
    {
        parent::__construct('add-broadcast');
        /*$hydrator = new AggregateHydrator();
        //$hydrator->add(new AuthorHydrator());
        $hydrator->add(new EducationHydrator());
        $hydrator->add(new DegreeHydrator());
        $this->setHydrator($hydrator);*/
        
        $title = new Element\Text('title');
        $title->setLabel('Nombre de la transmisión');
        $title->setAttribute('class', 'form-control');

        $startDate = new Element\DateTime('start_date');
        $startDate->setLabel('Fecha de transmisión');
        $startDate->setAttributes(array(
                'min'  => '2016-01-01T00:00:00Z',
                'max'  => '2020-01-01T00:00:00Z',
                'step' => '1', // minutes; default step interval is 1 min
                'class'=>'datetimepicker',
                'id'=>'timezone_example_1',
            ));
         $startDate->setOptions(array(
        'format' => 'YYYY-MM-DDThh:mm:ss.sZ'
    ));
        

        $submit = new Element\Submit('submit');
        $submit->setValue('Guardar transmisión');
        $submit->setAttribute('class', 'btn btn-primary');

        $this->add($title);
        $this->add($startDate);

        $this->add($submit);
    }
    
    
    
} 

?>
