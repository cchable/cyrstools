<?php
/**
 * Gestion des transports du SAES de la COCOF
 *  
 * @package   : module/Transport/config/module.config.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Transport;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;


return [
	//Router 
	'router' => [
		'routes' => [
			// Define a new route called "dashboard"
			'transport' => [
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

      // Define a new route called "indisponibilitechauffeur"
			'indisponibilitechauffeur' => [
        // Define a "Segment" route type: 
				'type'    => Segment::class,
				'options' => [
        // Listen to "/chauffeur" as uri:
					'route'       => '/indisponibilitechauffeur[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],
          // Define default controller and action to be called when this route is matched
					'defaults' => [
						'controller' => Controller\IndisponibiliteChauffeurController::class,
						'action'     => 'index',
					],
				],
			],
			
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
      
			// Define a new route called "marque"
			'marque' => [
				// Define a "Segment" route type: 
				'type'    => Segment::class,
				'options' => [
					// Listen to "/marque" as uri:
					'route'       => '/marque[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],          
					// Define default controller and action to be called when this route is matched
					'defaults' => [
						'controller' => Controller\MarqueController::class,
						'action'     => 'index',
					],
				],
			],
      
			// Define a new route called "typevehicule"
			'typevehicule' => [
				// Define a "Segment" route type: 
				'type'    => Segment::class,
				'options' => [
					// Listen to "/typevehicule" as uri:
					'route'       => '/typevehicule[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],          
					// Define default controller and action to be called when this route is matched
					'defaults' => [
						'controller' => Controller\TypeVehiculeController::class,
						'action'     => 'index',
					],
				],
			],      
    
			// Define a new route called "vehicule"
			'vehicule' => [
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
      
      // Define a new route called "indisponibilitevehicule"
			'indisponibilitevehicule' => [
				// Define a "Segment" route type: 
				'type'    => Segment::class,
				'options' => [
					// Listen to "/indisponibilitevehicule" as uri:
					'route'       => '/indisponibilitevehicule[/:action[/:id]]',
					'constraints' => [
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					],          
					// Define default controller and action to be called when this route is matched
					'defaults' => [
						'controller' => Controller\IndisponibiliteVehiculeController::class,
						'action'     => 'index',
					],
				],
			], 
      
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
						'controller' => Controller\TrajetController::class,
						'action'     => 'index',
					],
				],
			],      

      // Define a new route called "trajet"
			'trajet' => [
				// Define a "Segment" route type: 
				'type'    => Segment::class,
				'options' => [
					// Listen to "/trajet" as uri:
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
 
		],
	],
  
	//Controller
  'controllers' => [
    'factories' => [

		Controller\DashboardController::class 
			=> Controller\Factory\DashboardControllerFactory::class,
      
		Controller\ChauffeurController::class 
			=> Controller\Factory\ChauffeurControllerFactory::class,

    Controller\IndisponibiliteChauffeurController::class 
			=> Controller\Factory\IndisponibiliteChauffeurControllerFactory::class,

    Controller\IndisponibiliteVehiculeController::class 
			=> Controller\Factory\IndisponibiliteVehiculeControllerFactory::class,
			
		Controller\AnneeScolaireController::class 
			=> Controller\Factory\AnneeScolaireControllerFactory::class,
			
		Controller\EphemerideController::class 
			=> Controller\Factory\EphemerideControllerFactory::class,
      
		Controller\MarqueController::class 
			=> Controller\Factory\MarqueControllerFactory::class,  
      
		Controller\TypeVehiculeController::class 
			=> Controller\Factory\TypeVehiculeControllerFactory::class,      

		Controller\VehiculeController::class 
			=> Controller\Factory\VehiculeControllerFactory::class,
      
		Controller\EtapeController::class 
			=> Controller\Factory\EtapeControllerFactory::class,
            
		Controller\TrajetController::class 
			=> Controller\Factory\TrajetControllerFactory::class,
      
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
        
			Model\IndisponibiliteChauffeurTable::class
				=> Model\Factory\IndisponibiliteChauffeurTableFactory::class,
			Model\IndisponibiliteChauffeurTableGateway::class
				=> Model\Factory\IndisponibiliteChauffeurTableGatewayFactory::class,
			Model\ViewIndisponibiliteChauffeurTable::class
				=> Model\Factory\ViewIndisponibiliteChauffeurTableFactory::class,
			Model\ViewIndisponibiliteChauffeurTableGateway::class
				=> Model\Factory\ViewIndisponibiliteChauffeurTableGatewayFactory::class,   
        
			Model\IndisponibiliteVehiculeTable::class
				=> Model\Factory\IndisponibiliteVehiculeTableFactory::class,
			Model\IndisponibiliteVehiculeTableGateway::class
				=> Model\Factory\IndisponibiliteVehiculeTableGatewayFactory::class,
			Model\ViewIndisponibiliteVehiculeTable::class
				=> Model\Factory\ViewIndisponibiliteVehiculeTableFactory::class,
			Model\ViewIndisponibiliteVehiculeTableGateway::class
				=> Model\Factory\ViewIndisponibiliteVehiculeTableGatewayFactory::class,
      
			Model\AnneeScolaireTable::class
				=> Model\Factory\AnneeScolaireTableFactory::class,
			Model\AnneeScolaireTableGateway::class
				=> Model\Factory\AnneeScolaireTableGatewayFactory::class,

      Model\MarqueTable::class
				=> Model\Factory\MarqueTableFactory::class,
			Model\MarqueTableGateway::class
				=> Model\Factory\MarqueTableGatewayFactory::class,
        
      Model\TypeVehiculeTable::class
				=> Model\Factory\TypeVehiculeTableFactory::class,
			Model\TypeVehiculeTableGateway::class
				=> Model\Factory\TypeVehiculeTableGatewayFactory::class,
      
			Model\EphemerideTable::class
				=> Model\Factory\EphemerideTableFactory::class,
			Model\EphemerideTableGateway::class
				=> Model\Factory\EphemerideTableGatewayFactory::class,
			Model\ViewEphemerideTable::class
				=> Model\Factory\ViewEphemerideTableFactory::class,
			Model\ViewEphemerideTableGateway::class
				=> Model\Factory\ViewEphemerideTableGatewayFactory::class,        
      
			Model\MarqueTable::class
				=> Model\Factory\MarqueTableFactory::class,
			Model\MarqueTableGateway::class
				=> Model\Factory\MarqueTableGatewayFactory::class, 
        
			Model\TypeVehiculeTable::class
				=> Model\Factory\TypeVehiculeTableFactory::class,
			Model\TypeVehiculeTableGateway::class
				=> Model\Factory\TypeVehiculeTableGatewayFactory::class,
        
			Model\VehiculeTable::class
				=> Model\Factory\VehiculeTableFactory::class,
			Model\VehiculeTableGateway::class
				=> Model\Factory\VehiculeTableGatewayFactory::class,
			Model\ViewVehiculeTable::class
				=> Model\Factory\ViewVehiculeTableFactory::class,
			Model\ViewVehiculeTableGateway::class
				=> Model\Factory\ViewVehiculeTableGatewayFactory::class,
        
			Model\EtapeTable::class
				=> Model\Factory\EtapeTableFactory::class,
			Model\EtapeTableGateway::class
				=> Model\Factory\EtapeTableGatewayFactory::class,    
        
			Model\TrajetTable::class
				=> Model\Factory\TrajetTableFactory::class,
			Model\TrajetTableGateway::class
				=> Model\Factory\TrajetTableGatewayFactory::class,
			Model\ViewTrajetTable::class
				=> Model\Factory\ViewTrajetTableFactory::class,
			Model\ViewTrajetTableGateway::class
				=> Model\Factory\ViewTrajetTableGatewayFactory::class,           

			// Register Services
			Service\ChauffeurManager::class
				=> Service\Factory\ChauffeurManagerFactory::class,
        
			Service\IndisponibiliteChauffeurManager::class
				=> Service\Factory\IndisponibiliteChauffeurManagerFactory::class,
        
			Service\IndisponibiliteVehiculeManager::class
				=> Service\Factory\IndisponibiliteVehiculeManagerFactory::class,
				
			Service\AnneeScolaireManager::class
				=> Service\Factory\AnneeScolaireManagerFactory::class,
        
			Service\EphemerideManager::class
				=> Service\Factory\EphemerideManagerFactory::class,
				
			Service\MarqueManager::class
				=> Service\Factory\MarqueManagerFactory::class,		

			Service\TypeVehiculeManager::class
				=> Service\Factory\TypeVehiculeManagerFactory::class,
				
			Service\VehiculeManager::class
				=> Service\Factory\VehiculeManagerFactory::class,
				
			Service\EtapeManager::class
				=> Service\Factory\EtapeManagerFactory::class,
        
			Service\TrajetManager::class
				=> Service\Factory\TrajetManagerFactory::class,	
		],
	],
	
	//View

	'view_manager' => [
		'template_path_stack' => [
			'transport' => __DIR__ . '/../view',
		],
	],
];