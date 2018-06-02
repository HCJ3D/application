<?php
namespace Application;

use Application\Controller as ApplicationController;
use LeoGalleguillos\ThreeDimensions\Model\Factory as ThreeDimensionsFactory;
use LeoGalleguillos\ThreeDimensions\Model\Service as ThreeDimensionsService;
use LeoGalleguillos\ThreeDimensions\Model\Table as ThreeDimensionsTable;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Table as UserTable;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\Index::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'ground' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/ground/:action',
                    'defaults' => [
                        'controller' => Controller\Ground::class,
                    ],
                ],
            ],
            'mannequin' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/mannequin/:action',
                    'defaults' => [
                        'controller' => Controller\Mannequin::class,
                    ],
                ],
            ],
            'tmp' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/tmp[/:action]',
                    'defaults' => [
                        'controller' => Controller\Tmp::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'user' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/user/:action',
                    'defaults' => [
                        'controller' => Controller\User::class,
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            ApplicationController\Ground::class => function ($serviceManager) {
                return new ApplicationController\Ground(
                    $serviceManager->get(ThreeDimensionsService\Ground\Grounds::class)
                );
            },
            ApplicationController\Index::class => function ($serviceManager) {
                return new ApplicationController\Index(
                    $serviceManager->get(UserFactory\User\BuildFromCookies::class)
                );
            },
            ApplicationController\Mannequin::class => function ($serviceManager) {
                return new ApplicationController\Mannequin(
                    $serviceManager->get(ThreeDimensionsFactory\Mannequin::class),
                    $serviceManager->get(ThreeDimensionsService\Mannequin\Json::class),
                    $serviceManager->get(ThreeDimensionsTable\Mannequin::class),
                    $serviceManager->get(UserFactory\User\BuildFromCookies::class)
                );
            },
            ApplicationController\Tmp::class => function ($serviceManager) {
                return new ApplicationController\Tmp(
                    $serviceManager->get(ThreeDimensionsTable\Ground::class)
                );
            },
            ApplicationController\User::class => function ($serviceManager) {
                return new ApplicationController\User(
                    $serviceManager->get(UserFactory\User\BuildFromCookies::class),
                    $serviceManager->get(UserTable\User\DisplayName::class)
                );
            },
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
