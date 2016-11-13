<?php
return array(
    'doctrine' => array(

        'connection' => array(
            'odm_default' => array(
                'server'           => 'localhost',
                'port'             => '27017',
                'connectionString' => null,
                'user'             => 'admin',
                'password'         => 'admin',
                'dbname'           => 'cng_db',
                'options'          => array()
            ),
        ),

        'configuration' => array(
            'odm_default' => array(
                'metadata_cache'     => 'array',
                'driver'             => 'odm_default',

                'generate_proxies'   => true,
                'proxy_dir'          => 'data/DoctrineMongoODMModule/Proxy',
                'proxy_namespace'    => 'DoctrineMongoODMModule\Proxy',

                'generate_hydrators' => true,
                'hydrator_dir'       => 'data/DoctrineMongoODMModule/Hydrator',
                'hydrator_namespace' => 'DoctrineMongoODMModule\Hydrator',

                'default_db'         => 'cng_db',

                'filters'            => array(),  // array('filterName' => 'BSON\Filter\Class'),

                'logger'             => null // 'DoctrineMongoODMModule\Logging\DebugStack'
            )
        ),

        
        'driver' => array(
    'odm_default' => array(
        'drivers' => array(
            'Application\Document' => 'aplikasi'
        )
    ),
    'aplikasi' => array(
        'class' => 'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
        'cache' => 'array',
        'paths' => array(
            'module/Application/src/Application/Document'
        )
    )
),
        
        
        
        
        
        /*
        'driver' => array(
            'odm_default' => array(
                'drivers' => array('class'     => 'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
                    'namespace' => 'Application/Document/',
                    'paths'     => array('module/Application/src/Application/Document/'),
                    
            )
        ),
            ),
        
        
        
        /*'chatmongo'=>array('class'=>'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
            'cache'=>'array',
            'paths'=>array('module/Application/src/Application/Document')
            )
            ),*/
        

        'documentmanager' => array(
           'odm_default' => array(
             'connection'    => 'odm_default',
               'configuration' => 'odm_default',
                'eventmanager' => 'odm_default'
            )
        ),

        'eventmanager' => array(
            'odm_default' => array(
                'subscribers' => array()
            )
        ),
    )
    );