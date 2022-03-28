<?php
/**
 * @package   : module/PlanningBus/src/Model/TransportTable.php
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 * 
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Model;

use RuntimeException;

use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;

use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

use Hpb\Db\Sql\FBSelect;


/*
 * 
 */
class TransportTable
{
  
  private $tableGateway;

  //
  public function __construct(TableGatewayInterface $tableGateway)
  {

    $this->tableGateway = $tableGateway;
  }

  //
  public function getTransport($id)
  {
    
    $id = (int) $id;
    $rowset = $this->tableGateway->select(['IDX_TRANSPORT' => $id]);
    $row = $rowset->current();
    if (! $row) {
      throw new RuntimeException(sprintf(
        'Could not find row with identifier %d',
        $id
      ));
    }
    return $row;
  }

  //
  public function save(Transport $transport)
  {

    $data = $transport->getArrayCopy();
    $id = (int) $transport->getId();

    if ($id === 0) {
      $result = $this->tableGateway->insert($data);
      $transport = $this->findOneByNom($transport->getNom());
      return $transport;
    }
    
    try {
      $this->getTransport($id);
    } catch (RuntimeException $e) {
      throw new RuntimeException(sprintf(
        'Cannot update planning with identifier %d; does not exist',
        $id
      ));
    }
    $this->tableGateway->update($data, ['IDX_TRANSPORT' => $id]);
  }

  //
  public function delete($id)
  {
    
    $this->tableGateway->delete(['IDX_TRANSPORT' => (int) $id]);
  }
  
  //
  public function findOneBy(array $criteria)
  {
    
    $rowset = $this->tableGateway->select($criteria);
    $transport = $rowset->current();
    
    return $transport;
  }
  
  //
  public function findOneById(int $id)
  {
    
    $transport = $this->findOneBy(['IDX_TRANSPORT' => (int) $id]);
    return $transport;
  }

}