<?php

namespace Management;

return array(
    'invokables' => array(
        'Management\Repository\ManagementRepository' => 'Management\Repository\ManagementRepositoryImpl',
    ),

    'factories' => array(
        'Management\Service\ManagementService' => function(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
            $managementService = new \Management\Service\ManagementServiceImpl();
            $managementService->setManagementRepository($serviceLocator->get('Management\Repository\ManagementRepository'));

            return $managementService;
        },
    ),

    'initializers' => array(
        function($instance, \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
            if ($instance instanceof \Zend\Db\Adapter\AdapterAwareInterface) {
                $instance->setDbAdapter($serviceLocator->get('Zend\Db\Adapter\Adapter'));
            }
        },
    ),
);