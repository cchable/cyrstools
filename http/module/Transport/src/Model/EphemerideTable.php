<?php
/**
 * @package   : module/Transport/src/Model/EphemerideTable.php
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-22 H.P.B
 * 
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Model;

use RuntimeException;

use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\Sql\Where;

use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

use Hpb\Db\Sql\FBSelect;


/*
 * 
 */
class EphemerideTable
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
      $select->order('STARTDATEPHEMERIDE ASC');
    }); 
  }
   
  //
  public function fetchAllPaginator($pageNumber = 1, $count = null, $search = '')
  {
        
    $fbSelect = new FBSelect($this->tableGateway->table);
    $fbSelect->order('STARTDATEPHEMERIDE ASC');
    if ($search) {
        
      $where = new Where();
      $fbSelect->where($where->like('STARTDATEPHEMERIDE ASC', "%$search%"));
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
    $fbSelect->order('STARTDATEPHEMERIDE ASC ASC');

    // Create a new result set based on the Chauffeur entity:
    $resultSetPrototype = new ResultSet();
    $resultSetPrototype->setArrayObjectPrototype(new Ephemeride());

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
  public function getEphemeride($id)
  {
    
    $id = (int) $id;
    $rowset = $this->tableGateway->select(['IDX_EPHEMERIDE' => $id]);
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
  public function saveEphemeride(Ephemeride $ephemeride)
  {

    $data = $ephemeride->getArrayCopy(false);
    $id = (int) $ephemeride->getId();

    if ($id === 0) {
      $this->tableGateway->insert($data);
      $ephemeride = $this->findOneByDateDebut($data);
      return $ephemeride;
    }
    
    try {
      $this->getEphemeride($id);
    } catch (RuntimeException $e) {
      throw new RuntimeException(sprintf(
        'Cannot update vehicule with identifier %d; does not exist',
        $id
      ));
    }
    
    $this->tableGateway->update($data, ['IDX_EPHEMERIDE' => $id]);
  }

  //
  public function deleteEphemeride($id)
  {
    
    $this->tableGateway->delete(['IDX_EPHEMERIDE' => (int) $id]);
  }
  
  //
  public function findOneBy(array $criteria)
  {
    
    $rowset = $this->tableGateway->select($criteria);
    $ephemeride = $rowset->current();
    
    return $ephemeride;
  }
  
  //
  public function findOneById(int $id)
  {
    
    $ephemeride = $this->findOneBy(['IDX_EPHEMERIDE' => (int) $id]);
    return $ephemeride;
  }
  
  //
  public function findOneByDateDebut($data)
  {
    
    $ephemeride = $this->findOneBy(['STARTDATEPHEMERIDE' => $data['STARTDATEPHEMERIDE']]);
    return $ephemeride;
  }
  
  // 
  public function findOneByRecordOld(array $record)
  {
    
    $ephemeride = $this->findOneBy($record);
    
    return $ephemeride;
  }
  
  // 
  public function findOneByRecord($record, bool $bWithIDX = false)
  {
    if(is_array($record)) {
      if(!$bWithIDX) {
        unset($record['STARTDATEPHEMERIDE']);
      }
      $ephemeride = $this->findOneBy($record);
    } else { 
      if(is_object($record)) {
        $recordArray = $record->getArrayCopy($bWithIDX);
        $ephemeride = $this->findOneBy($recordArray);
      } else {
        $ephemeride = false;
      }
    }
    
    return $ephemeride;
  }
  
  public function getNumberOfRows() 
  {
    
    $adapter = $this->tableGateway->getAdapter();
    $sql     = $this->tableGateway->getSql();
    $select  = $sql->select();
    $select->columns(
      [
        'COUNT' => new \Laminas\Db\Sql\Expression("COUNT('')"),
      ],
      FALSE,
    );
    
    $selectString = $sql->buildSqlString($select);
    $rowset = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
    
    return $rowset->current();
  }
}