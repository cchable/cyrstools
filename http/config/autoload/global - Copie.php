<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Laminas\Db\Adapter\AdapterAbstractServiceFactory;
use Laminas\Session;
use Laminas\Session\Validator\RemoteAddr;
use Laminas\Session\Validator\HttpUserAgent;


return [
	//Session configuration
	'session_config' => [
		'cookie_lifetime' => 60*60*1,     // Session cookie will expire in 1 hour.
		'gc_maxlifetime'  => 60*60*24*30, // How long to store session data on server (for 1 month).
    'name'            => 'CyrSTools'
	],
  
	//Session manager configuration
	'session_manager' => [
		// Session validators (used for security).
		'validators' => [
			RemoteAddr::class,
			HttpUserAgent::class,
		]
	],

	//Session storage configuration
	'session_storage' => [
		'type' => SessionArrayStorage::class
	],

	//Cache configuration
	'caches' => [
		'FilesystemCache' => [
			'adapter' => [
				'name'    => Filesystem::class,
				'options' => [
					// Store cached data in this directory.
					'cache_dir' => './data/cache',
					// Store cached data for 1 hour.
					'ttl'       => 60*60*1 
				],
			],
			'plugins' => [
				[
					'name'    => 'serializer',
					'options' => [                        
					],
				],
			],
		],
	],  

	//Paginator
	'paginator' => [
		'options' => [
			'defaultRowPerPage' => 2,
			'stepRowPerPage'    => 2,
		],
	],

	//Database connection configuration.
	'db' => [
		'driver'   => 'Pdo',
		'adapters' => [
			'Albums\Db\ReadWriteAdapter' => [
				'driver'   => 'Pdo',
				'dsn'      => 'firebird:dbname=127.0.0.1:skeletonlaminasFB4;charset=utf8;',
				'user'     => 'sysdba',
				'password' => 'masterkey',
			],
			'PlanningBus\Db\ReadWriteAdapter' => [
				'driver'   => 'Pdo',
				'dsn'      => 'firebird:dbname=127.0.0.1:planningtransport51;charset=utf8;',
				'user'     => 'sysdba',
				'password' => 'masterkey',
			],
			'Transport\Db\ReadWriteAdapter' => [
				'driver'   => 'Pdo',
				'dsn'      => 'firebird:dbname=srv-db-01.chablerie.net:transport;charset=utf8;',
				'user'     => 'sysdba',
				'password' => 'masterkey',
			],
		],
	],
];
