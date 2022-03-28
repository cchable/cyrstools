<?php
/**
 * @package   : module/PlanningBus/src/Controller/VehiculeController.php
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 * 
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

use PlanningBus\Service\VehiculeManager;

use PlanningBus\Model\Vehicule;
use PlanningBus\Model\VehiculeTable;

use PlanningBus\Form\VehiculeForm;
use PlanningBus\Form\SearchForm;


/*
 * 
 */
class VehiculeController extends AbstractActionController
{
  
	/*
	 * Vehicule table manager
	 * @var Plannigvehicule\Model\VehiculeTable
	 */
	private $vehiculeTable;
	
	/*
   * Vehicule manager
   * @var Plannigvehicule\Service\VehiculeManager
   */
	private $vehiculeManager;

  /*
   * 
   */
	public function __construct(
		VehiculeTable $vehiculeTable,
		VehiculeManager $vehiculeManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
	{
    
		$this->vehiculeTable     = $vehiculeTable;
		$this->vehiculeManager   = $vehiculeManager;
    $this->defaultRowPerPage = $defaultRowPerPage;
    $this->stepRowPerPage    = $stepRowPerPage;
    $this->sessionContainer  = $sessionContainer;
	}

	/*
   * This is the default "index" action of the controller. 
   * It displays the list of vehicule.
   */
	public function indexAction()
	{
   
    // Getting requested page number from the query
    // or to 1 if none is set, or the page is invalid
    $pageNumber = (int) $this->params()->fromQuery('page', 1);
    $pageNumber = ($pageNumber < 1) ? 1 : $pageNumber;
    
    // Getting requested number per page from the query
    // or to default if none is set, or the number per page is invalid
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
      'formSearch'     => $formSearch,
      'module'         => 'vehicule',
      'search'         => $search,
      'rowPerPage'     => $rowPerPage,
      'stepRowPerPage' => $this->stepRowPerPage,
      'vehicules'      => $this->vehiculeTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]); 
      
   //return new ViewModel([
   //   'vehicule' => $this->vehiculeTable->fetchAllPaginator($pageNumber, $count),
   // ]);
  }
  
  /*
   * This action displays a page allowing to add a new vehicule
   */
  public function addAction()
  {
    
    // Create Form
    $form = new VehiculeForm('create', $this->vehiculeTable);

    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {
      
      // Fill in the form with POST data
      $data = $this->params()->fromPost();
      $form->setData($data);

      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Add vehicule
        if ($this->vehiculeManager->addVehicule($data)) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage('Vehicule ajouté.');          
        } else {
          
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Le vehicule " . $data['NOMVEHICULE'] . " existe déjà.");
          return new ViewModel(['form' => $form]); 
        }
        
        // Redirect to "index" page
        return $this->redirect()->toRoute('vehicule', ['action'=>'index']); 
      }               
    } 
    
    return new ViewModel(['form' => $form]);  
  }
  
/*
   * This action displays a page allowing to edit an existing vehicule
   */
  public function editAction()
  {
    
		$id = (int)$this->params()->fromRoute('id', -1);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
    if ($id<1) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $vehicule = $this->vehiculeTable->findOneById($id);

    if ($vehicule == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    // Create ephemeride form
    $form = new VehiculeForm('update');
    
     // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {

      // Fill in the form with POST data
      $data = $this->params()->fromPost();            
      $form->setData($data);
    
      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Update vehicule
        if ($this->vehiculeManager->updateVehicule($vehicule, $data)) {
				
          // Add a flash message Suucess
          $this->flashMessenger()->addSuccessMessage('Véhicule modifié');
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Un véhicule '" . $data['NOMVEHICULE'] . "' existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('vehicule', ['action'=>'index']);
      }               
    } else {
		
      $form->setData($vehicule->getArrayCopy());
    }

    return new ViewModel([
      'form' => $form,
    ]); 
  }
  
  /*
   * This action delete a vehicule
   */
  public function deleteAction()
  {
	
    $id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
		
      $this->getResponse()->setStatusCode(404);
      return;
    }

    $vehicule = $this->vehiculeTable->findOneById($id);
    if ($vehicule == null) {
  
			$this->getResponse()->setStatusCode(404);
      return;
    }

    $nom = $vehicule->getNom();
    
    // Delete véhicule
    $this->vehiculeManager->deleteVehicule($vehicule);

    // Add a flash message
    $this->flashMessenger()->addWarningMessage("Le véhicule '$nom' a été supprimé");

    // Redirect to "index" page
    return $this->redirect()->toRoute('vehicule', ['action'=>'index']);      
  }
}