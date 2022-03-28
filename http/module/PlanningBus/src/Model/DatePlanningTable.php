<?php
/**
 * @package   : module/PlanningBus/src/Model/DatePlanningTable.php
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
class DatePlanningTable
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
      $select->order('DATEDATEPLANNING ASC, CODESEMAINEDATEPLANNING ASC');
    }); 
  }
   
  //
  public function fetchAllPaginator($pageNumber = 1, $count = null, $search = '')
  {
        
    $fbSelect = new FBSelect($this->tableGateway->table);
    $fbSelect->order('DATEDATEPLANNING ASC, CODESEMAINEDATEPLANNING ASC');
    if ($search) {
        
      $where = new Where();
      $fbSelect->where($where->like('DATEDATEPLANNING', "%$search%"));
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
    $fbSelect->order('DATEDATEPLANNING ASC, CODESEMAINEDATEPLANNING ASC');

    // Create a new result set based on the DatePlanning entity:
    $resultSetPrototype = new ResultSet();
    $resultSetPrototype->setArrayObjectPrototype(new DatePlanning());

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
  public function getDatePlanning($id)
  {
    
    $id = (int) $id;
    $rowset = $this->tableGateway->select(['IDX_DATEPLANNING' => $id]);
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
  public function save(DatePlanning $datePlanning)
  {

    $data = $datePlanning->getArrayCopy();
    $id = (int) $datePlanning->getId();

    if ($id === 0) {
      $result = $this->tableGateway->insert($data);
      $datePlanning = $this->findOneByRecord($datePlanning);
      return $datePlanning;
    }
    
    try {
      $this->getDatePlanning($id);
    } catch (RuntimeException $e) {
      throw new RuntimeException(sprintf(
        'Cannot update planning with identifier %d; does not exist',
        $id
      ));
    }
    $this->tableGateway->update($data, ['IDX_DATEPLANNING' => $id]);
  }

  //
  public function delete($id)
  {
    
    $this->tableGateway->delete(['IDX_DATEPLANNING' => (int) $id]);
  }
  
  //
  public function findOneBy(array $criteria)
  {
    
    $rowset = $this->tableGateway->select($criteria);
    $datePlanning = $rowset->current();
    
    return $datePlanning;
  }
  
  //
  public function findOneById(int $id)
  {
    
    $datePlanning = $this->findOneBy(['IDX_DATEPLANNING' => (int) $id]);
    return $datePlanning;
  }
  
  //
  public function findOneByDate($date)
  {
    
    $datePlanning = $this->findOneBy(['DATEDATEPLANNING' => $date]);
    return $datePlanning;
  }
  
  //
  public function findOneByRecord(DatePlanning $record)
  {
    
    $recordArray = $record->getArrayCopy();
    unset($recordArray["IDX_DATEPLANNING"]);
    $datePlanning = $this->findOneBy($recordArray);
    
    return $datePlanning;
  }
}