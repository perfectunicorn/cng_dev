<?php
//
namespace Blog;

return array(
    'router' => array(
        'routes' => array(
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /blog/:controller/:action
            'blog' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/blog',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Blog\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                        'page'          => 1,
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),

                    'paged' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/page/:page',
                            'constraints' => array(
                                'page' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Blog\Controller\Index',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
            ),

            
            'add-post' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/blog/add',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Index',
                        'action' => 'add',
                    ),
                ),
            ),
            
             'add-comment' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/blog/add-comment',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Index',
                        'action' => 'addComment',
                    ),
                ),
            ),
            
            'display-post' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/blog/posts/:categorySlug/:posted/:postSlug',
                    'constraints' => array(
                        'posted' => '[0-9]+',
                        'categorySlug' => '[a-zA-Z0-9-]+',
                        'postSlug' => '[a-zA-Z0-9-]+',
                        
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Index',
                        'action' => 'viewPost',
                        'page'          => 1,
                    ),
                ),
            ),

            'edit-post' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/blog/edit/:categorySlug/:posted/:postSlug',
                    'constraints' => array(
                        'posted' => '[0-9]+',
                        'categorySlug' => '[a-zA-Z0-9-]+',
                        'postSlug' => '[a-zA-Z0-9-]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Index',
                        'action' => 'edit',
                    ),
                ),
            ),

            'delete-post' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/blog/delete/:categorySlug/:posted/:postSlug',
                    'constraints' => array(
                        'posted' => '[0-9]+',
                        'categorySlug' => '[a-zA-Z0-9-]+',
                        'postSlug' => '[a-zA-Z0-9-]+', 
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Index',
                        'action' => 'delete',
                    ),
                ),
            ),
            
   
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Blog\Controller\Index' => Controller\IndexController::class
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);