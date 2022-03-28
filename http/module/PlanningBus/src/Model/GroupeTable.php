<?php
/**
 * @package   : module/PlanningBus/src/Model/GroupeTable.php
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
class GroupeTable
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
      $select->order('NOMGROUPE ASC');
    }); 
  }
   
  //
  public function fetchAllPaginator($pageNumber = 1, $count = null, $search = '')
  {
        
    $fbSelect = new FBSelect($this->tableGateway->table);
    $fbSelect->order('NOMGROUPE ASC');
    if ($search) {
        
      $where = new Where();
      $fbSelect->where($where->like('NOMGROUPE', "%$search%"));
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
    $fbSelect->order('NOMGROUPE ASC');

    // Create a new result set based on the Groupe entity:
    $resultSetPrototype = new ResultSet();
    $resultSetPrototype->setArrayObjectPrototype(new Groupe());

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
  public function getGroupe($id)
  {
    
    $id = (int) $id;
    $rowset = $this->tableGateway->select(['IDX_GROUPE' => $id]);
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
  public function saveGroupe(Groupe $groupe)
  {

    $data = $groupe->getArrayCopy();
    $id = (int) $groupe->getId();

    if ($id === 0) {
      $result = $this->tableGateway->insert($data);
      $groupe = $this->findOneByNom($groupe->getNom());
      return $groupe;
    }
    
    try {
      $this->getGroupe($id);
    } catch (RuntimeException $e) {
      throw new RuntimeException(sprintf(
        'Cannot update groupe with identifier %d; does not exist',
        $id
      ));
    }
    $this->tableGateway->update($data, ['IDX_GROUPE' => $id]);
  }

  //
  public function deleteGroupe($id)
  {
    
    $this->tableGateway->delete(['IDX_GROUPE' => (int) $id]);
  }
  
  //
  public function findOneBy(array $criteria)
  {
    
    $rowset = $this->tableGateway->select($criteria);
    $groupe = $rowset->current();
    
    return $groupe;
  }
  
  //
  public function findOneById(int $id)
  {
    
    $groupe = $this->findOneBy(['IDX_GROUPE' => (int) $id]);
    return $groupe;
  }
  
  //
  public function findOneByNom($nom)
  {
    
    $groupe = $this->findOneBy(['NOMGROUPE' => $nom]);
    return $groupe;
  }
  
  // 
  public function findOneByRecord(Groupe $record)
  {
    
    $recordArray = $record->getArrayCopy();
    unset($recordArray["IDX_GROUPE"]);
    $groupe = $this->findOneBy($recordArray);

    return $groupe;
  }
}