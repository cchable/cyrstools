<?php //module/Album/src/Model/AlbumTable.php

/**
 * @package   : 
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-20 H.P.B
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Album\Model;

use RuntimeException;

use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

use Hpb\Db\Sql\FBSelect;

class AlbumTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAllOld()
    {
        return $this->tableGateway->select();
    }

    public function fetchAll($paginated = false)
    {
        if ($paginated) {
            return $this->fetchPaginatedResults();
        }

        return $this->tableGateway->select();
    }
    
    private function fetchPaginatedResults()
    {
        // Create a new Select object for the table:
        $fbSelect = new FBSelect($this->tableGateway->getTable());
        $fbSelect->order('ARTIST ASC, TITLE ASC');

        // Create a new result set based on the Album entity:
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Album());

        // Create a new pagination adapter object:
        $paginatorAdapter = new DbSelect(
            // our configured select object:
            $fbSelect,
            // the adapter to run it against:
            $this->tableGateway->getAdapter(),
            // the result set to hydrate:
            $resultSetPrototype
        );

        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }
    
    public function getAlbum($ID)
    {
        $ID = (int) $ID;
        $rowset = $this->tableGateway->select(['ID' => $ID]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $ID
            ));
        }

        return $row;
    }

    public function saveAlbum(Album $album)
    {
        $data = [
            'ARTIST' => $album->ARTIST,
            'TITLE'  => $album->TITLE,
        ];

        $ID = (int) $album->ID;

        if ($ID === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getAlbum($ID);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $ID
            ));
        }

        $this->tableGateway->update($data, ['ID' => $ID]);
    }

    public function deleteAlbum($ID)
    {
        $this->tableGateway->delete(['ID' => (int) $ID]);
    }
}