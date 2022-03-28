<?php
/**
 * @package   : module/PlanningBus/src/Model/PlanningTable.php: 
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

use Laminas\Paginator\Paginator;
use Laminas\Paginator\Adapter\DbSelect;

use Hpb\Db\Sql\FBSelect;


/*
 * 
 */
class PlanningTable
{
	
  private $tableGateway;

	//
  public function __construct(TableGatewayInterface $tableGateway)
  {
    
    $this->tableGateway = $tableGateway;
  }

	//
  public function fetchAll($paginated = false)
  {
    
    if ($paginated) {
      return $this->fetchAllPaginator();
    }

    return $this->tableGateway->select(function (\Laminas\Db\Sql\Select $select)
    {
      $select->order('IDX_PLANNING ASC');
    }); 
  }

	//
	public function fetchAllPaginator($pageNumber = 1, $count = null, $search = null)
  {
        
    $fbSelect = new FBSelect($this->tableGateway->table);
    $fbSelect->order('IDX_PLANNING ASC');
    if ($search) {
      
      $fbSelect->where(function (Where $where) {
          $where->like('IDX_PLANNING', "%$search%");
      });
    }
    
    /*
     * Creating paginator adapter by using DBSelect method
     * of Laminas\Paginator\Adapter
     * Paginator adapter will contain user entity objects
     * since DbSelect is also injected with the TableGateway's
     * ResultSet prototype
     * 
     * @var \Laminas\Paginator\Adapter\DbSelect $paginatorAdapter
     */
    $paginatorAdapter = new DbSelect($fbSelect, 
      $this->tableGateway->adapter, 
      $this->tableGateway->getResultSetPrototype());
    
    /*
     * Actual paginator consist of hydrated user entity objects
     * 
     * @var \Laminas\Paginator\Paginator $paginator
     */
    $paginator = new Paginator($paginatorAdapter);
    
    /*
     * Setting item count per page if it is defined
     * If no count specified then all records will be returned 
     */
    if ($count) {
      $paginator->setDefaultItemCountPerPage($count);
    }
    
    /*
     * Retrieve only items in the requested page
     * by setting the current page number
     */
    $paginator->setCurrentPageNumber($pageNumber);
    
    /*
     * Paginator object consist of user entity objects 
     * is ready to be returned
     */
    return $paginator;  
  }  
  
	//
  public function get($idPlanning)
  {
    
    $id = (int) $idPlanning;
    $rowset = $this->tableGateway->select(['IDX_PLANNING' => $id]);
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
  public function save(Planning $planning)
  {
    
    $data = $planning->getArrayCopy();
    $id = (int) $planning->getId();

    if ($id === 0) {
      $result = $this->tableGateway->insert($data);
      $planning = $this->findOneByRecord($planning);
      return $planning;
    }

    try {
      $this->get($id);
    } catch (RuntimeException $e) {
      throw new RuntimeException(sprintf(
        'Cannot update planning with identifier %d; does not exist',
        $id
      ));
    }
    $this->tableGateway->update($data, ['IDX_PLANNING' => $id]);
  }

	//
  public function delete($id)
  {
    
    $this->tableGateway->delete(['IDX_PLANNING' => (int) $id]);
  }
    
	//
  public function findOneBy(array $criteria)
  {
    
    $rowset = $this->tableGateway->select($criteria);
    $planning = $rowset->current();
    
    return $planning;
  }
  
	//
  public function findOneById(int $id)
  {
    
    $planning = $this->findOneBy(['IDX_PLANNING' => (int) $id]);
    return $planning;
  }
  
  //
  public function findOneByRecord(Planning $record)
  {
    
    $recordArray = $record->getArrayCopy();
    unset($recordArray["IDX_PLANNING"]);
    $planning = $this->findOneBy($recordArray);
    
    return $planning;
  }
}