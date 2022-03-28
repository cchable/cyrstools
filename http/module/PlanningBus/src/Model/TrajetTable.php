<?php
/**
 * @package   : module/PlanningBus/src/Model/TrajetTable.php: 
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

use Laminas\I18n\Filter\NumberParse;

use Laminas\Paginator\Paginator;
use Laminas\Paginator\Adapter\DbSelect;

use Hpb\Db\Sql\FBSelect;


/*
 * 
 */
class TrajetTable
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
      $select->order('NOMTRAJET ASC');
    }); 
  }

	//
	public function fetchAllPaginator($pageNumber = 1, $count = null)
  {
        
    $fbSelect = new FBSelect($this->tableGateway->table);
    $fbSelect->order('NOMTRAJET ASC');
    
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
  public function getTrajet($idTrajet)
  {
    
    $id = (int) $idTrajet;
    $rowset = $this->tableGateway->select(['IDX_TRAJET' => $id]);
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
  public function saveTrajet(Trajet $trajet)
  {
    
    $data = $trajet->getArrayCopy();
    $id = (int) $trajet->getId();

    if ($id === 0) {
      
      $result = $this->tableGateway->insert($data);
      $trajet = $this->findOneByRecord($trajet);
      return $trajet;
    }

    try {
      $this->getTrajet($id);
    } catch (RuntimeException $e) {
      throw new RuntimeException(sprintf(
        'Cannot update trajet with identifier %d; does not exist',
        $id
      ));
    }
    $this->tableGateway->update($data, ['IDX_TRAJET' => $id]);
  }

	//
  public function deleteTrajet($id)
  {
    
    $this->tableGateway->delete(['IDX_TRAJET' => (int) $id]);
  }
    
	//
  public function findOneBy(array $criteria)
  {
    
    $rowset = $this->tableGateway->select($criteria);
    $trajet = $rowset->current();
    
    return $trajet;
  }
  
	//
  public function findOneById(int $id)
  {
    
    $trajet = $this->findOneBy(['IDX_TRAJET' => (int) $id]);
    return $trajet;
  }
  
  //
  public function findOneByNom($nom)
  {
    
    $trajet = $this->findOneBy(['NOMTRAJET' => $nom]);
    return $trajet;
  }
  
  //
  public function findOneByRecord(Trajet $record)
  {
    
    $recordArray = $record->getArrayCopy();
    unset($recordArray["IDX_TRAJET"]);
    $trajet = $this->findOneBy($recordArray);
    
    return $trajet;
  }
}