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
    'IndisponibilitechauffeurSessionContainer',
    'EphemerideSessionContainer',
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
        'classes'                  => 'alert alert-default alert-dismissible fade show d-flex align-items-center',
      ],
      
      'success' => [
        'message_open_format'      => '<div%s role="alert"><svg class="bi text-muted flex-shrink-0 me-2" width="32" height="32" role="img" aria-label="Success:"><use xlink:href="#check2_square"/></svg><button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>',
        'message_close_string'     => '</div>' . " \r\n",
        'message_separator_string' => '</div><div%s role="alert">' . " \r\n",
        'classes'                  => 'alert alert-success alert-dismissible fade show d-flex align-items-center',
      ],
      
      'warning' => [
        'message_open_format'      => '<div%s role="alert"><svg class="bi text-muted flex-shrink-0 me-2" width="32" height="32" role="img" aria-label="Warning:"><use xlink:href="#exclamation_square"/></svg><button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>',
        'message_close_string'     => '</div>' . " \r\n",
        'message_separator_string' => '</div><div%s role="alert">' . " \r\n",
        'classes'                  => 'alert alert-warning alert-dismissible fade show d-flex align-items-center',
      ],
      
      'error'   => [
        'message_open_format'      => '<div%s role="alert"><svg class="bi text-muted flex-shrink-0 me-2" width="32" height="32" role="img" aria-label="Error:"><use xlink:href="#shield_exclamation"/></svg><button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>',
        'message_close_string'     => '</div>' . " \r\n",
        'message_separator_string' => '</div><div%s role="alert">' . " \r\n",
        'classes'                  => 'alert alert-danger alert-dismissible fade show d-flex align-items-center',
      ],
      
      'info'    => [
        'message_open_format'      => '<div%s role="alert"><svg class="bi text-muted flex-shrink-0 me-2" width="32" height="32" role="img" aria-label="Info:"><use xlink:href="#info_square"/></svg><button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>',
        'message_close_string'     => '</div>' . " \r\n",
        'message_separator_string' => '</div><div%s role="alert">' . " \r\n",
        'classes'                  => 'alert alert-info alert-dismissible fade show d-flex align-items-center',
      ],
    ],    
  ],
];
