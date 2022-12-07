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
use Laminas\Db\Sql\Select;

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
      $ephemeride = $this->findOneByRecord($data);
      return $ephemeride;
    }
    
    try {
      $this->getEphemeride($id);
    } catch (RuntimeException $e) {
      throw new RuntimeException(sprintf(
        'Cannot update ephemeride with identifier %d; does not exist',
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
    $row = $rowset->current();
    
    return $row;
  }
  
  //
  public function findOneById(int $id)
  {
    
    $row = $this->findOneBy(['IDX_EPHEMERIDE' => (int) $id]);
    return $row;
  }
  
  //
  public function findOneByName($name)
  {
    
    $row = $this->findOneBy(['NOMEPHEMERIDE' => $name]);
    return $row;
  }
  
  //
  public function findOneByBeginDate($data)
  {
   
    $ephemeride = $this->findOneBy(['STARTDATEPHEMERIDE' => $data['STARTDATEPHEMERIDE']]);
    return $ephemeride;
  }
  
  // 
  public function findOneByRecord($record, bool $bWithIDX = false)
  {
    if(is_array($record)) {
      if(!$bWithIDX) {
        unset($record['IDX_EPHEMERIDE']);
      }
      $row = $this->findOneBy($record);
    } else { 
      if(is_object($record)) {
        $recordArray = $record->getArrayCopy($bWithIDX);
        $row = $this->findOneBy($recordArray);
      } else {
        $row = false;
      }
    }
    
    return $row;
  }
  
  //ici 
  //01/01/2023-31/01/2023 - 15/01/2023-15/02/2023 => ko
  //                        02/01/2023-03/01/2023 => ko
  //                        02/01/2023-03/01/2023 => ko
  //
  public function checkBetweenDates($date) 
  {
 
    // Sample : SELECT * FROM "T_EPHEMERIDES" WHERE "STARTDATEPHEMERIDE" >= '2021-09-27' AND "ENDDATEPHEMERIDE" <= '2021-09-27';

    $mySelect = 
      function (Select $select) use ($date)
      {
        $select->where->greaterThanOrEqualTo('STARTDATEPHEMERIDE', $date);
        $select->where->lessThanOrEqualTo('ENDDATEPHEMERIDE', $date);
      };
      
      $rowset = $this->tableGateway->select($mySelect);
      
/*        
    $rowset = $this->tableGateway->select(function (Select $select) {
      
      $i = func_num_args();
      $date = "2022-09-27";
      $select->where->greaterThanOrEqualTo('STARTDATEPHEMERIDE', $date);
      $select->where->lessThanOrEqualTo('ENDDATEPHEMERIDE', $date);
    });
*/ 
    return $rowset->current();
  }
  
  //
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