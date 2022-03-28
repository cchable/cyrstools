<?php

/**
 * @package   : 
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-20 H.P.B
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Album;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\AlbumTable::class => function($container) {
                    $tableGateway = $container->get(Model\AlbumTableGateway::class);
                    return new Model\AlbumTable($tableGateway);
                },
                Model\AlbumTableGateway::class => function ($container) {
                    //$dbAdapter = $container->get(AdapterInterface::class);
                    $dbAdapter = $container->get('Albums\Db\ReadWriteAdapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Album());
                    return new TableGateway('ALBUMS', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }
    
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\AlbumController::class => function($container) {
                    return new Controller\AlbumController(
                        $container->get(Model\AlbumTable::class)
                    );
                },
            ],
        ];
    }
}