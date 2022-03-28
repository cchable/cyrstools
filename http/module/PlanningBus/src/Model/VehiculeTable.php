<?php
/**
 * @package   : module/PlanningBus/src/Model/VehiculeTable.php
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
class VehiculeTable
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
      $select->order('NOMVEHICULE ASC');
    }); 
  }
   
  //
  public function fetchAllPaginator($pageNumber = 1, $count = null, $search = '')
  {
        
    $fbSelect = new FBSelect($this->tableGateway->table);
    $fbSelect->order('NOMVEHICULE ASC');
    if ($search) {
        
      $where = new Where();
      $fbSelect->where($where->like('NOMVEHICULE', "%$search%"));
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
    $fbSelect->order('NOMVEHICULE ASC');

    // Create a new result set based on the Vehicule entity:
    $resultSetPrototype = new ResultSet();
    $resultSetPrototype->setArrayObjectPrototype(new Vehicule());

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
  public function getVehicule($id)
  {
    
    $id = (int) $id;
    $rowset = $this->tableGateway->select(['IDX_VEHICULE' => $id]);
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
  public function saveVehicule(Vehicule $vehicule)
  {

    $data = $vehicule->getArrayCopy();
    $id = (int) $vehicule->getId();

    if ($id === 0) {
      $result = $this->tableGateway->insert($data);
      $vehicule = $this->findOneByRecord($vehicule);
      return $vehicule;
    }
    
    try {
      $this->getVehicule($id);
    } catch (RuntimeException $e) {
      throw new RuntimeException(sprintf(
        'Cannot update vehicule with identifier %d; does not exist',
        $id
      ));
    }
    $this->tableGateway->update($data, ['IDX_VEHICULE' => $id]);
  }

  //
  public function deleteVehicule($id)
  {
    
    $this->tableGateway->delete(['IDX_VEHICULE' => (int) $id]);
  }
  
  //
  public function findOneBy(array $criteria)
  {
    
    $rowset = $this->tableGateway->select($criteria);
    $vehicule = $rowset->current();
    
    return $vehicule;
  }
  
  //
  public function findOneById(int $id)
  {
    
    $vehicule = $this->findOneBy(['IDX_VEHICULE' => (int) $id]);
    return $vehicule;
  }
  
  //
  public function findOneByNom($nom)
  {
    
    $vehicule = $this->findOneBy(['NOMVEHICULE' => $nom]);
    return $vehicule;
  }
  
  // 
  public function findOneByRecord(Vehicule $record)
  {
    
    $recordArray = $record->getArrayCopy();
    unset($recordArray["IDX_VEHICULE"]);
    $vehicule = $this->findOneBy($recordArray);
    
    return $vehicule;
  }
}