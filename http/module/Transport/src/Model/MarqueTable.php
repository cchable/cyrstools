<?php
/**
 * This is the MarqueTable class for MarqueTable service.
 * 
 * @package   module/Transport/src/Model/MarqueTable.php
 * @version   1.0
 * @copyright 2018-22 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Model;

use RuntimeException;

use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;

use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

use Hpb\Db\Sql\FBSelect;


/*
 * 
 */
class MarqueTable
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
      $select->order('NOMMARQUE ASC');
    }); 
  }
   
  //
  public function fetchAllPaginator($pageNumber = 1, $count = null, $search = '')
  {
        
    $fbSelect = new FBSelect($this->tableGateway->table);
    $fbSelect->order('NOMMARQUE ASC');
    if ($search) {
        
      $where = new Where();
      $fbSelect->where($where->like('NOMMARQUE', "%$search%"));
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
    $fbSelect->order('NOMMARQUE ASC');

    // Create a new result set based on the Marque entity:
    $resultSetPrototype = new ResultSet();
    $resultSetPrototype->setArrayObjectPrototype(new Marque());

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
  public function getMarque($id)
  {
    
    $id = (int) $id;
    $rowset = $this->tableGateway->select(['IDX_MARQUE' => $id]);
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
  public function saveMarque(Marque $marque)
  {

    $data = $marque->getArrayCopy(false);
    $id = (int) $marque->getId();

    if ($id === 0) {
      $result = $this->tableGateway->insert($data);
      $marque = $this->findOneByRecord($marque);
      return $marque;
    }
    
    try {
      $this->getMarque($id);
    } catch (RuntimeException $e) {
      throw new RuntimeException(sprintf(
        'Cannot update marque with identifier %d; does not exist',
        $id
      ));
    }
    $this->tableGateway->update($data, ['IDX_MARQUE' => $id]);
  }

  //
  public function deleteMarque($id)
  {
    
    $this->tableGateway->delete(['IDX_MARQUE' => (int) $id]);
  }
  
  //
  public function findOneBy(array $criteria)
  {
    
    $rowset = $this->tableGateway->select($criteria);
    $marque = $rowset->current();
    
    return $marque;
  }
  
  //
  public function findOneById(int $id)
  {
    
    $marque = $this->findOneBy(['IDX_MARQUE' => (int) $id]);
    return $marque;
  }
  
  //
  public function findOneByName($nom)
  {
    
    $marque = $this->findOneBy(['NOMMARQUE' => $nom]);
    return $marque;
  }
  
  // 
  public function findOneByRecord(Marque $record)
  {
    
    $recordArray = $record->getArrayCopy();
    unset($recordArray["IDX_MARQUE"]);
    $marque = $this->findOneBy($recordArray);
    
    return $marque;
  }
  
  /**
   * Calcul le nombre d'enregistrement dans la table
   *
   * @return int $count
   * @access public
   */
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
    $row = $rowset->current();
    $count = $row['COUNT'];
    
    return $count;
  }
}