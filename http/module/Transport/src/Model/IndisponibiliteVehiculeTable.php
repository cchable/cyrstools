<?php
/**
 * This is the IndisponibiliteVehiculeTable class for IndisponibiliteVehiculeTable service.
 * 
 * @package   module/Transport/src/Model/IndisponibiliteVehiculeTable.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
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
class IndisponibiliteVehiculeTable
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
      $select->order('STARTDATEINDISPONIBILITE ASC');
    }); 
  }
   
  //
  public function fetchAllPaginator($pageNumber = 1, $count = null, $search = '')
  {
        
    $fbSelect = new FBSelect($this->tableGateway->table);
    $fbSelect->order('STARTDATEINDISPONIBILITE ASC');
    if ($search) {
        
      $where = new Where();
      $fbSelect->where($where->like('STARTDATEINDISPONIBILITE', "%$search%"));
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
    $fbSelect->order('STARTDATEINDISPONIBILITE ASC');

    // Create a new result set based on the Vehicule entity:
    $resultSetPrototype = new ResultSet();
    $resultSetPrototype->setArrayObjectPrototype(new IndisponibiliteVehicule());

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
  public function getIndisponibiliteVehicule($id)
  {
    
    $id = (int) $id;
    $rowset = $this->tableGateway->select(['IDX_INDISPONIBILITEVEHICULE' => $id]);
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
  public function saveIndisponibiliteVehicule(IndisponibiliteVehicule $indisponibiliteVehicule)
  {

    $data = $indisponibiliteVehicule->getArrayCopy(false);
    $id = (int) $indisponibiliteVehicule->getId();

    if ($id === 0) {
      $this->tableGateway->insert($data);
      $indisponibiliteVehicule = $this->findOneByDateDebut($data);
      return $indisponibiliteVehicule;
    }
    
    try {
      $this->getIndisponibiliteVehicule($id);
    } catch (RuntimeException $e) {
      throw new RuntimeException(sprintf(
        'Cannot update vehicule with identifier %d; does not exist',
        $id
      ));
    }
    
    $this->tableGateway->update($data, ['IDX_INDISPONIBILITEVEHICULE' => $id]);
  }

  //
  public function deleteIndisponibiliteVehicule($id)
  {
    
    $this->tableGateway->delete(['IDX_INDISPONIBILITEVEHICULE' => (int) $id]);
  }
  
  //
  public function findOneBy(array $criteria)
  {
    
    $rowset = $this->tableGateway->select($criteria);
    $indisponibiliteVehicule = $rowset->current();
    
    return $indisponibiliteVehicule;
  }
  
  //
  public function findOneById(int $id)
  {
    
    $indisponibiliteVehicule = $this->findOneBy(['IDX_INDISPONIBILITEVEHICULE' => (int) $id]);
    return $indisponibiliteVehicule;
  }
  
  //
  public function findOneByDateDebut($data)
  {
    
    $indisponibiliteVehicule = $this->findOneBy(['STARTDATEINDISPONIBILITE' => $data['STARTDATEINDISPONIBILITE']]);
    return $indisponibiliteVehicule;
  }
  
  // 
  public function findOneByRecordOld(array $record)
  {
    
    $indisponibiliteVehicule = $this->findOneBy($record);
    
    return $indisponibiliteVehicule;
  }
  
  // 
  public function findOneByRecord($record, bool $bWithIDX = false)
  {
    if(is_array($record)) {
      if(!$bWithIDX) {
        unset($record['IDX_INDISPONIBILITEVEHICULE']);
      }
      $indisponibiliteVehicule = $this->findOneBy($record);
    } else { 
      if(is_object($record)) {
        $recordArray = $record->getArrayCopy($bWithIDX);
        $indisponibiliteVehicule = $this->findOneBy($recordArray);
      } else {
        $indisponibiliteVehicule = false;
      }
    }
    
    return $indisponibiliteVehicule;
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