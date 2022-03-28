<?php
/**
 * @package   : module/PlanningBus/src/Model/HeurePlanningTable.php
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
class HeurePlanningTable
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
      return $this->fetchPaginatedResults();
    }

    return $this->tableGateway->select(function (\Laminas\Db\Sql\Select $select)
    {
      $select->order('HEUREHEUREPLANNING ASC');
    }); 
  }
   
  //
  public function fetchAllPaginator($pageNumber = 1, $count = null, $search = '')
  {
        
    $fbSelect = new FBSelect($this->tableGateway->table);
    $fbSelect->order('HEUREHEUREPLANNING ASC');
    if ($search) {
        
      $where = new Where();
      $fbSelect->where($where->like('HEUREHEUREPLANNING', "%$search%"));
    }
    
    /*
     * Creating paginator adapter by using DBSelect method of Lamians\Paginator\Adapter
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
    
    /**
     * Paginator object consist of user entity objects 
     * is ready to be returned
     */
    return $paginator;  
  }
  
  //
  private function fetchPaginatedResults()
  {
  
    // Create a new Select object for the table:
    $fbSelect = new FBSelect($this->tableGateway->getTable());
    $fbSelect->order('HEUREHEUREPLANNING ASC');

    // Create a new result set based on the HeurePlanning entity:
    $resultSetPrototype = new ResultSet();
    $resultSetPrototype->setArrayObjectPrototype(new HeurePlanning());

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
  
  //
  public function getHeurePlanning($id)
  {
    
    $id = (int) $id;
    $rowset = $this->tableGateway->select(['IDX_HEUREPLANNING' => $id]);
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
  public function save(HeurePlanning $heurePlanning)
  {

    $data = $heurePlanning->getArrayCopy();
    $id = (int) $heurePlanning->getId();

    if ($id === 0) {
      $result = $this->tableGateway->insert($data);
      $heurePlanning = $this->findOneByRecord($heurePlanning);
      return $heurePlanning;
    }
    
    try {
      $this->getHeurePlanning($id);
    } catch (RuntimeException $e) {
      throw new RuntimeException(sprintf(
        'Cannot upheure planning with identifier %d; does not exist',
        $id
      ));
    }
    $this->tableGateway->update($data, ['IDX_HEUREPLANNING' => $id]);
  }

  //
  public function delete($id)
  {
    
    $this->tableGateway->delete(['IDX_HEUREPLANNING' => (int) $id]);
  }
  
  //
  public function findOneBy(array $criteria)
  {
    
    $rowset = $this->tableGateway->select($criteria);
    $heurePlanning = $rowset->current();
    
    return $heurePlanning;
  }
  
  //
  public function findOneById(int $id)
  {
    
    $heurePlanning = $this->findOneBy(['IDX_HEUREPLANNING' => (int) $id]);
    return $heurePlanning;
  }
  
  //
  public function findOneByHeure($heure)
  {
    
    $heurePlanning = $this->findOneBy(['HEUREHEUREPLANNING' => $heure]);
    return $heurePlanning;
  }
  
  //
  public function findOneByRecord(HeurePlanning $record)
  {
    
    $recordArray = $record->getArrayCopy();
    unset($recordArray["IDX_HEUREPLANNING"]);
    $heurePlanning = $this->findOneBy($recordArray);
    
    return $heurePlanning;
  }
}