<?php // \module\Application\config\module.config.php
/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;


return [
	//Router
	'router' => [
		'routes' => [
			'home' => [
				'type'    => Literal::class,
				'options' => [
					'route'    => '/',
					'defaults' => [
						'controller' => Controller\IndexController::class,
						'action'     => 'index',
					],
				],
			],
			'application' => [
				'type'    => Segment::class,
				'options' => [
					'route'    => '/application[/:action]',
					'defaults' => [
						'controller' => Controller\IndexController::class,
						'action'     => 'index',
					],
				],
			],
		],
	],
    
	//Controllers
	'controllers' => [
		'factories' => [
			//Controller\IndexController::class => InvokableFactory::class,
			Controller\IndexController::class 
				=> Controller\Factory\IndexControllerFactory::class,
		],
	],
    
	//Navigation
	'navigation2' => [
		'default' => [
			[
				'label' => 'Home',
				'route' => 'home',
			],
			
			[
				'label' => 'PlanningBus',
				'route' => 'planning',
				'pages' => [
					[
						'label'  => 'Add',
						'route'  => 'planning',
						'action' => 'add',
					],
				],
			],
			[
				'label' => 'Ephemeride',
				'route' => 'ephemeride',
				'pages' => [
					[
						'label'  => 'Add',
						'route'  => 'ephemeride',
						'action' => 'add',
					],
				],
			],
			[
				'label' => 'Année scolaire',
				'route' => 'anneescolaire',
				'pages' => [
					[
						'label'  => 'Add',
						'route'  => 'anneescolaire',
						'action' => 'add',
					],
				],
			],
		],
	],    
    
	//Service Manager
  'service_manager' => [
    'factories' => [
      Service\NavManager::class
        => Service\Factory\NavManagerFactory::class,
      Service\RbacAssertionManager::class
        => Service\Factory\RbacAssertionManagerFactory::class,
    ],
  ],	

  //Session Container
  'session_containers' => [
    'I18nSessionContainer',
    'ChauffeurSessionContainer',
    'AnneeScolaireSessionContainer',
    'EphemerideSessionContainer',
    'VehiculeSessionContainer',
    'TrajetSessionContainer',
    'EtapeSessionContainer',
    'GroupeSessionContainer',
    'HeurePlanningSessionContainer',
    'DatePlanningSessionContainer',
    'TypePlanningSessionContainer',
    'TransportSessionContainer',
    'PlanningSessionContainer',
    'DashboardSessionContainer',
  ],
  
	//View Helper
  'view_helpers' => [
    'factories' => [
      View\Helper\Menu::class
        => View\Helper\Factory\MenuFactory::class,
      View\Helper\Breadcrumbs::class
        => InvokableFactory::class,
    ],
    'aliases' => [
      'mainMenu'        => View\Helper\Menu::class,
      'pageBreadcrumbs' => View\Helper\Breadcrumbs::class,
    ],
  ],	
		
	//View Manager
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
		'strategies' => [
      'ViewJsonStrategy',
    ],
	],
		
	//View helper
	'view_helper_config' => [
    // The following key allows to define custom styling for FlashMessenger view helper.
    'flashmessenger' => [
      'default' => [
        'message_open_format'      => '<div%s role="alert"><button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>',
        'message_close_string'     => '</div>' . " \r\n",
        'message_separator_string' => '</div><div%s role="alert">' . " \r\n",
        'classes'                  => 'alert alert-primary alert-dismissible fadeshow',
      ],
      'success' => [
        'message_open_format'      => '<div%s role="alert"><button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>',
        'message_close_string'     => '</div>' . " \r\n",
        'message_separator_string' => '</div><div%s role="alert">' . " \r\n",
        'classes'                  => 'alert alert-success alert-dismissible fadeshow',
      ],
      'warning' => [
        'message_open_format'      => '<div%s role="alert"><button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>',
        'message_close_string'     => '</div>' . " \r\n",
        'message_separator_string' => '</div><div%s role="alert">' . " \r\n",
        'classes'                  => 'alert alert-success alert-dismissible fadeshow',
      ],
      'error'   => [
        'message_open_format'      => '<div%s role="alert"><button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>',
        'message_close_string'     => '</div>' . " \r\n",
        'message_separator_string' => '</div><div%s role="alert">' . " \r\n",
        'classes'                  => 'alert alert-danger alert-dismissible fadeshow',
      ],
      'info'    => [
        'message_open_format'      => '<div%s role="alert"><button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>',
        'message_close_string'     => '</div>' . " \r\n",
        'message_separator_string' => '</div><div%s role="alert">' . " \r\n",
        'classes'                  => 'alert alert-info alert-dismissible fadeshow',
      ],
    ],    
  ],
];
