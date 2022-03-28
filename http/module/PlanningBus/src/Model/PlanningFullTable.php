<?php
/**
 * @package   : module/PlanningBus/src/Model/PlanningFullTable.php: 
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
class PlanningFullTable
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
	public function fetchAllPaginator($pageNumber = 1, $count = null)
  {
        
    $fbSelect = new FBSelect($this->tableGateway->table);
    $fbSelect->order('IDX_PLANNING ASC');
    
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
  public function getPlanning($idPlanningFull)
  {
    
    $id = (int) $idPlanningFull;
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
  public function findOneBy(array $criteria)
  {
    
    $rowset = $this->tableGateway->select($criteria);
    $planningFull = $rowset->current();
    
    return $planningFull;
  }
  
	//
  public function findOneById(int $id)
  {
    
    $planningFull = $this->findOneBy(['IDX_PLANNING' => (int) $id]);
    return $planningFull;
  }
  
  //
  public function findOneByRecord(Planning $planning)
  {
    
    $planningFull = $this->findOneBy([
      'IDX_TYPEPLANNING'  => $planning->getIdTypePlanning(),
      'IDX_DATEPLANNING'  => $planning->getIdDatePlanning(),
      'IDX_HEUREPLANNING' => $planning->getIdHeurePlanning(),
    ]);
    return $planningFull;
  }
}