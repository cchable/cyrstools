<?php
/**
 * @package   : module/Transport/config/module.config.php
 *
 * @purpose   : Gestion des transport du SAES de la COCOF
 * 
 * 
 * @copyright : Copyright (C) 2018-22 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Transport;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;


return [
	//Router 
	'router' => [
		'routes' => [	
			// Define a new route called "dashboard"
			'dashboard' => [
				'type'    => Segment::class,
				'options' => [
					'route'       => '/dashboard[/:action[/:ID]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],
					'defaults' => [
						'controller' => Controller\DashboardController::class,
						'action'     => 'index',
					],
				],
			],

/*
			// Define a new route called "vehicule"
			vehicule' => [
				// Define a "Segment" route type: 
				'type'    => Segment::class,
				'options' => [
					// Listen to "/vehicule" as uri:
					'route'       => '/vehicule[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],          
					// Define default controller and action to be called when this route is matched
					'defaults' => [
						'controller' => Controller\VehiculeController::class,
						'action'     => 'index',
					],
				],
			],
*/
/*
			// Define a new route called "chauffeur"
			'chauffeur' => [
				// Define a "Segment" route type: 
				'type'    => Segment::class,
				'options' => [
					// Listen to "/chauffeur" as uri:
					'route'       => '/chauffeur[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],          
					// Define default controller and action to be called when this route is matched
					'defaults' => [
						'controller' => Controller\ChauffeurController::class,
						'action'     => 'index',
					],
				],
			],
*/
/*
			// Define a new route called "ephemeride"
			'ephemeride' => [
				// Define a "Segment" route type: 
				'type'    => Segment::class,
				'options' => [
					// Listen to "/ephemeride" as uri:
					'route'       => '/ephemeride[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],          
					// Define default controller and action to be called when this route is matched
					'defaults' => [
						'controller' => Controller\EphemerideController::class,
						'action'     => 'index',
					],
				],
			],
*/
/*
			// Define a new route called "anneescolaire"
			'anneescolaire' => [
				// Define a "Segment" route type: 
				'type'    => Segment::class,
				'options' => [
					// Listen to "/anneescolaire" as uri:
					'route'       => '/anneescolaire[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],          
					// Define default controller and action to be called when this route is matched
					'defaults' => [
						'controller' => Controller\AnneeScolaireController::class,
						'action'     => 'index',
					],
				],
			],
*/
/*
			// Define a new route called "etape"
			'etape' => [
				// Define a "Segment" route type: 
				'type'    => Segment::class,
				'options' => [
					// Listen to "/etape" as uri:
					'route'       => '/etape[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],          
					// Define default controller and action to be called when this route is matched
					'defaults' => [
						'controller' => Controller\EtapeController::class,
						'action'     => 'index',
					],
				],
			],
*/
/*
			// Define a new route called "trajet"
			'trajet' => [
				// Define a "Segment" trajet type: 
				'type'    => Segment::class,
				'options' => [
					// Listen to "/etape" as uri:
					'route'       => '/trajet[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],          
					// Define default controller and action to be called when this route is matched
					'defaults' => [
						'controller' => Controller\TrajetController::class,
						'action'     => 'index',
					],
				],
			],
 */
 /*
			// Define a new route called "groupe"
			'groupe' => [
				// Define a "Segment" route type: 
				'type'    => Segment::class,
				'options' => [
					// Listen to "/groupe" as uri:
					'route'       => '/groupe[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],          
					// Define default controller and action to be called when this route is matched
					'defaults' => [
						'controller' => Controller\GroupeController::class,
						'action'     => 'index',
					],
				],
			],
*/
/*
			// Define a new route called "planning"
			'planning' => [
				// Define a "Segment" route type: 
				'type'    => Segment::class,
				'options' => [
					// Listen to "/planning" as uri:
					'route'       => '/planning[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],          
					// Define default controller and action to be called when this route is matched
					'defaults' => [
						'controller' => Controller\PlanningController::class,
						'action'     => 'index',
					],
				],
			],
*/
/*
			// Define a new route called "typeplanning"
			'typeplanning' => [
				// Define a "Segment" route type: 
				'type'    => Segment::class,
				'options' => [
					// Listen to "/typeplanning" as uri:
					'route'       => '/typeplanning[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],          
					// Define default controller and action to be called when this route is matched
					'defaults' => [
						'controller' => Controller\TypePlanningController::class,
						'action'     => 'index',
					],
				],
			],
*/
/*   
			// Define a new route called "dateplanning"
			'dateplanning' => [
				// Define a "Segment" route type: 
				'type'    => Segment::class,
				'options' => [
					// Listen to "/dateplanning" as uri:
					'route'       => '/dateplanning[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],          
					// Define default controller and action to be called when this route is matched
					'defaults' => [
						'controller' => Controller\DatePlanningController::class,
						'action'     => 'index',
					],
				],
			],
*/
/*      
			// Define a new route called "heureplanning"
			'heureplanning' => [
				// Define a "Segment" route type: 
				'type'    => Segment::class,
				'options' => [
					// Listen to "/heureplanning" as uri:
					'route'       => '/heureplanning[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],          
					// Define default controller and action to be called when this route is matched
					'defaults' => [
						'controller' => Controller\HeurePlanningController::class,
						'action'     => 'index',
					],
				],
			],
*/
/*
			// Define a new route called "transport"
			'transport' => [
				// Define a "Segment" route type: 
				'type'    => Segment::class,
				'options' => [
					// Listen to "/transport" as uri:
					'route'       => '/transport[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],          
					// Define default controller and action to be called when this route is matched
					'defaults' => [
						'controller' => Controller\TransportController::class,
						'action'     => 'index',
					],
				],
			],      
*/ 
		],
	],
  
	//Controller
  'controllers' => [
    'factories' => [

		Controller\DashboardController::class 
			=> Controller\Factory\DashboardControllerFactory::class,
/*	
		Controller\HeurePlanningController::class 
			=> Controller\Factory\HeurePlanningControllerFactory::class,

		Controller\DatePlanningController::class 
			=> Controller\Factory\DatePlanningControllerFactory::class,

		Controller\TypePlanningController::class 
			=> Controller\Factory\TypePlanningControllerFactory::class,

		Controller\PlanningController::class 
			=> Controller\Factory\PlanningControllerFactory::class,
		
		Controller\EphemerideController::class 
			=> Controller\Factory\EphemerideControllerFactory::class,

		Controller\AnneeScolaireController::class 
			=> Controller\Factory\AnneeScolaireControllerFactory::class,

		Controller\VehiculeController::class 
			=> Controller\Factory\VehiculeControllerFactory::class,

		Controller\EtapeController::class 
			=> Controller\Factory\EtapeControllerFactory::class,
		
		Controller\TrajetController::class 
			=> Controller\Factory\TrajetControllerFactory::class,  
		
		Controller\GroupeController::class 
			=> Controller\Factory\GroupeControllerFactory::class,
		
		Controller\TransportController::class 
			=> Controller\Factory\TransportControllerFactory::class,  
*/
    ],
  ],
	
	//Service Manager
	'service_manager' => [
		'factories' => [    
			// Register Models
	  
			Model\ChauffeurTable::class
				=> Model\Factory\ChauffeurTableFactory::class,
			Model\ChauffeurTableGateway::class
				=> Model\Factory\ChauffeurTableGatewayFactory::class,
/*					
			Model\ChauffeurFullTable::class
				=> Model\Factory\ChauffeurFullTableFactory::class,
			Model\ChauffeurFullTableGateway::class
				=> Model\Factory\ChauffeurFullTableGatewayFactory::class,
		  
			Model\HeurePlanningTable::class
				=> Model\Factory\HeurePlanningTableFactory::class,
			Model\HeurePlanningTableGateway::class
				=> Model\Factory\HeurePlanningTableGatewayFactory::class,
				  
			Model\DatePlanningTable::class
				=> Model\Factory\DatePlanningTableFactory::class,
			Model\DatePlanningTableGateway::class
				=> Model\Factory\DatePlanningTableGatewayFactory::class,
			
			Model\TypePlanningTable::class
				=> Model\Factory\TypePlanningTableFactory::class,
			Model\TypePlanningTableGateway::class
				=> Model\Factory\TypePlanningTableGatewayFactory::class,
		  
			Model\PlanningTable::class
				=> Model\Factory\PlanningTableFactory::class,
			Model\PlanningTableGateway::class
				=> Model\Factory\PlanningTableGatewayFactory::class,
		  
			Model\PlanningFullTable::class
				=> Model\Factory\PlanningFullTableFactory::class,
			Model\PlanningFullTableGateway::class
				=> Model\Factory\PlanningFullTableGatewayFactory::class,
			
			Model\EphemerideTable::class
				=> Model\Factory\EphemerideTableFactory::class,
			Model\EphemerideTableGateway::class
				=> Model\Factory\EphemerideTableGatewayFactory::class,
				
			Model\EphemerideFullTable::class
				=> Model\Factory\EphemerideFullTableFactory::class,
			Model\EphemerideFullTableGateway::class
				=> Model\Factory\EphemerideFullTableGatewayFactory::class,
				
			Model\AnneeScolaireTable::class
				=> Model\Factory\AnneeScolaireTableFactory::class,
			Model\AnneeScolaireTableGateway::class
				=> Model\Factory\AnneeScolaireTableGatewayFactory::class,

			Model\VehiculeTable::class
				=> Model\Factory\VehiculeTableFactory::class,
			Model\VehiculeTableGateway::class
				=> Model\Factory\VehiculeTableGatewayFactory::class,

			Model\EtapeTable::class
				=> Model\Factory\EtapeTableFactory::class,
			Model\EtapeTableGateway::class
				=> Model\Factory\EtapeTableGatewayFactory::class,

			Model\TrajetTable::class
				=> Model\Factory\TrajetTableFactory::class,
			Model\TrajetTableGateway::class
				=> Model\Factory\TrajetTableGatewayFactory::class,
		  
			Model\TrajetFullTable::class
				=> Model\Factory\TrajetFullTableFactory::class,
			Model\TrajetFullTableGateway::class
				=> Model\Factory\TrajetFullTableGatewayFactory::class,   

			Model\GroupeTable::class
				=> Model\Factory\GroupeTableFactory::class,
			Model\GroupeTableGateway::class
				=> Model\Factory\GroupeTableGatewayFactory::class,

			Model\TransportTable::class
				=> Model\Factory\TransportTableFactory::class,
			Model\TransportTableGateway::class
				=> Model\Factory\TransportTableGatewayFactory::class,
				
			Model\TransportFullTable::class
				=> Model\Factory\TransportFullTableFactory::class,
			Model\TransportFullTableGateway::class
				=> Model\Factory\TransportFullTableGatewayFactory::class,
*/     
			// Register Services
/*		
			Service\ChauffeurManager::class
				=> Service\Factory\ChauffeurManagerFactory::class,

			Service\HeurePlanningManager::class
				=> Service\Factory\HeurePlanningManagerFactory::class,
			
			Service\DatePlanningManager::class
				=> Service\Factory\DatePlanningManagerFactory::class,
			
			Service\TypePlanningManager::class
				=> Service\Factory\TypePlanningManagerFactory::class,
			
			Service\PlanningManager::class
				=> Service\Factory\PlanningManagerFactory::class,
			
			Service\EphemerideManager::class
				=> Service\Factory\EphemerideManagerFactory::class,

			Service\AnneeScolaireManager::class
				=> Service\Factory\AnneeScolaireManagerFactory::class,
			
			Service\VehiculeManager::class
				=> Service\Factory\VehiculeManagerFactory::class,

			Service\EtapeManager::class
				=> Service\Factory\EtapeManagerFactory::class,

			Service\TrajetManager::class
				=> Service\Factory\TrajetManagerFactory::class,

			Service\GroupeManager::class
				=> Service\Factory\GroupeManagerFactory::class,

			Service\TransportManager::class
				=> Service\Factory\TransportManagerFactory::class,
*/			
		],
	],
	
	//View

	'view_manager' => [
		'template_path_stack' => [
			'transport' => __DIR__ . '/../view',
		],
	],
];