<?php
/**
 * This controleur is responsible for add/edit/delete 'typeVehicule'. 
 * 
 * @package   module/Transport/src/Controller/TypeVehiculeController.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\Controller\Plugin\FlashMessenger;

use Transport\Service\TypeVehiculeManager;

use Transport\Model\TypeVehicule;
use Transport\Model\TypeVehiculeTable;

use Transport\Form\TypeVehiculeForm;
use Transport\Form\SearchForm;


/**
 * TypeVehiculeController class
 */
class TypeVehiculeController extends AbstractActionController
{
  
  /**
   * TypeVehicule table manager
   *
   * @var Transport\Model\TypeVehiculeTable $typeVehiculeTable
   * @access private
   */
  private $typeVehiculeTable; 
  
  /**
   * TypeVehicule manager service
   *
   * @var Transport\Service\TypeVehiculeManager $typeVehiculeManager
   * @access private
   */
  private $typeVehiculeManager;

  /**
   * Default row per page
   *
   * @var int $defaultRowPerPage
   * @access private
   */
  private $defaultRowPerPage;

  /**
   * Number of step row per page
   *
   * @var int $stepRowPerPage
   * @access private
   */
  private $stepRowPerPage;

  /**
   * Session container
   *
   * @var Laminas\Session\Container
   * @access private
   */
  private $sessionContainer;  
  
  
  /**
   * 
   */
  public function __construct(
    TypeVehiculeTable   $typeVehiculeTable,
    TypeVehiculeManager $typeVehiculeManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
  {
    
    $this->typeVehiculeTable   = $typeVehiculeTable;
    $this->typeVehiculeManager = $typeVehiculeManager;
    $this->defaultRowPerPage = $defaultRowPerPage;
    $this->stepRowPerPage    = $stepRowPerPage;
    $this->sessionContainer  = $sessionContainer;
  }

  /**
   * This is the default "index" action of the controller. 
   * It displays the list of TypeVehicule.
   *
   * @access public
   */
  public function indexAction()
  {
    
    // Getting requested page number from the query
    // or to 1 if none is set, or the page is invalid
    $pageNumber = (int) $this->params()->fromQuery('page', 1);
    $pageNumber = ($pageNumber < 1) ? 1 : $pageNumber;

    // Getting requested number per page from the query
    // or to default if none is set, or the number per page is invalid
    //$defaultRowPerPage = $this->config['paginator']['options']['defaultRowPerPage'];
    //$rowPerPage = (int) $this->params()->fromQuery('rowPerPage', $this->defaultRowPerPage);
    $rowPerPage = (int) $this->params()->fromQuery('rowPerPage', 0);
    $rowPerPage = ($rowPerPage < 1) ? 0 : $rowPerPage;
   
    if ($rowPerPage) {
      
      $this->sessionContainer->rowPerPage = $rowPerPage;
    } else {
      if (isset($this->sessionContainer->rowPerPage)) {
        
        $rowPerPage = $this->sessionContainer->rowPerPage;
      } else {
        
        $this->sessionContainer->rowPerPage = $rowPerPage = $this->defaultRowPerPage;
      }
    }
        
    // Getting requested seach from the query
    // or to null if none is set
    $search = $this->params()->fromQuery('search', '');
    
    // Create search Form
    $formSearch = new SearchForm();

    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {
      
      // Fill in the form with POST data
      $data = $this->params()->fromPost();
      $formSearch->setData($data);
      
      // Validate form
      if($formSearch->isValid()) {

        // Get filtered and validated data
        $data = $formSearch->getData();  
        $search = $data['search'];
      }
    }

    // Fill form with data from url
    if ($search) 
      $formSearch->setData(['search' => $search]);
    
    return new ViewModel([
      'formSearch'      => $formSearch,
      'module'          => 'typevehicule',
      'search'          => $search,
      'rowPerPage'      => $rowPerPage,
      'stepRowPerPage'  => $this->stepRowPerPage,
      'typeVehicules'   => $this->typeVehiculeTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]);   
  }
   
  /*
   * This action displays a page allowing to add a new typeVehicule.
   */
  public function addAction()
  {
    
    // Create Form
    $form = new TypeVehiculeForm('create');

    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {
      
      // Fill in the form with POST data
      $data = $this->params()->fromPost();
      $form->setData($data);

      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();  
        
        // Add TypeVehicule
        if ($this->typeVehiculeManager->addTypeVehicule($data)) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage("La typeVehicule '" . $data['NOMMARQUE'] . "' a été ajoutée");
          // Redirect to "index" page
          return $this->redirect()->toRoute('typeVehicule', ['action'=>'index']); 
        } else {
          
          // Add a flash message Error
          $this->flashMessenger()->addMessage("La typeVehicule '" . $data['NOMMARQUE'] . "' existe déjà", 'error', 0);
        }
      } else {
        
        // Add a flash message Error
        $this->flashMessenger()->addMessage("Des données dans le formulaire sont erronées", 'error', 0);
      }
    } 
    
    return new ViewModel([
      'form' => $form,
    ]);   
  }  
  
  /*
   * This action delete an typeVehicule
   */
  public function deleteAction()
  {
	
    $id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
		
      $this->getResponse()->setStatusCode(404);
      return;
    }

    $typeVehicule = $this->typeVehiculeTable->findOneById($id);
    if ($typeVehicule == null) {
  
			$this->getResponse()->setStatusCode(404);
      return;
    }

    $nom = $typeVehicule->getName();
    
    // Delete typeVehicule.
    $this->typeVehiculeManager->deleteTypeVehicule($id);

    // Add a flash message.
    $this->flashMessenger()->addWarningMessage("La typeVehicule '$nom' a été supprimée");

    // Redirect to "index" page
    return $this->redirect()->toRoute('typeVehicule', ['action'=>'index']);      
  }
  
  /*
   * This action displays a page allowing to edit an existing typeVehicule
   */
  public function editAction()
  {
    
		$id = (int)$this->params()->fromRoute('id', -1);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
    if ($id<1) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $typeVehicule = $this->typeVehiculeTable->findOneById($id);

    if ($typeVehicule == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    // Create typeVehicule form
    $form = new TypeVehiculeForm('update');
    
     // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {

      // Fill in the form with POST data
      $data = $this->params()->fromPost();            
      $form->setData($data);
    
      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Update typeVehicule
        if ($this->typeVehiculeManager->updateTypeVehicule($typeVehicule, $data)) {
				
          // Add a flash message Suucess
          $this->flashMessenger()->addSuccessMessage('La typeVehicule a été modifiée');
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("La typeVehicule '" . $data['NOMMARQUE'] . "' existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('typeVehicule', ['action'=>'index']);
      }               
    } else {
		
      $form->setData($typeVehicule->getArrayCopy());
    }

    return new ViewModel([
      'form' => $form,
    ]);
  }
}