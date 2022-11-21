<?php
/**
 * @package   : module/Transport/src/Model/IndisponibiliteChauffeurTable.php
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
class IndisponibiliteChauffeurTable
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

    // Create a new result set based on the Chauffeur entity:
    $resultSetPrototype = new ResultSet();
    $resultSetPrototype->setArrayObjectPrototype(new IndisponibiliteChauffeur());

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
  public function getIndisponibiliteChauffeur($id)
  {
    
    $id = (int) $id;
    $rowset = $this->tableGateway->select(['IDX_INDISPONIBILITECHAUFFEUR' => $id]);
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
  public function saveIndisponibiliteChauffeur(IndisponibiliteChauffeur $indisponibiliteChauffeur)
  {

    $data = $indisponibiliteChauffeur->getArrayCopy(false);
    $id = (int) $indisponibiliteChauffeur->getId();

    if ($id === 0) {
      $this->tableGateway->insert($data);
      $indisponibiliteChauffeur = $this->findOneByDateDebut($data);
      return $indisponibiliteChauffeur;
    }
    
    try {
      $this->getIndisponibiliteChauffeur($id);
    } catch (RuntimeException $e) {
      throw new RuntimeException(sprintf(
        'Cannot update vehicule with identifier %d; does not exist',
        $id
      ));
    }
    
    $this->tableGateway->update($data, ['IDX_INDISPONIBILITECHAUFFEUR' => $id]);
  }

  //
  public function deleteIndisponibiliteChauffeur($id)
  {
    
    $this->tableGateway->delete(['IDX_INDISPONIBILITECHAUFFEUR' => (int) $id]);
  }
  
  //
  public function findOneBy(array $criteria)
  {
    
    $rowset = $this->tableGateway->select($criteria);
    $indisponibiliteChauffeur = $rowset->current();
    
    return $indisponibiliteChauffeur;
  }
  
  //
  public function findOneById(int $id)
  {
    
    $indisponibiliteChauffeur = $this->findOneBy(['IDX_INDISPONIBILITECHAUFFEUR' => (int) $id]);
    return $indisponibiliteChauffeur;
  }
  
  //
  public function findOneByDateDebut($data)
  {
    
    $indisponibiliteChauffeur = $this->findOneBy(['STARTDATEINDISPONIBILITE' => $data['STARTDATEINDISPONIBILITE']]);
    return $indisponibiliteChauffeur;
  }
  
  // 
  public function findOneByRecordOld(array $record)
  {
    
    $indisponibiliteChauffeur = $this->findOneBy($record);
    
    return $indisponibiliteChauffeur;
  }
  
  // 
  public function findOneByRecord($record, bool $bWithIDX)
  {
    if(is_array($record)) {
      if(!$bWithIDX) {
        unset($record['IDX_INDISPONIBILITECHAUFFEUR']);
      }
      $indisponibiliteChauffeur = $this->findOneBy($record);
    } else { 
      if(is_object($record)) {
        $recordArray = $record->getArrayCopy($bWithIDX);
        $indisponibiliteChauffeur = $this->findOneBy($recordArray);
      } else {
        $indisponibiliteChauffeur = false;
      }
    }
    
    return $indisponibiliteChauffeur;
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