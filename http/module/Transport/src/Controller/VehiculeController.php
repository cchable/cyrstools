<?php
/**
 * This controleur is responsible for add/edit/delete 'vehicule'. 
 * 
 * @package   module/Transport/src/Controller/VehiculeController.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\Controller\Plugin\FlashMessenger;

use Transport\Service\VehiculeManager;

use Transport\Model\Vehicule;
use Transport\Model\VehiculeTable;
use Transport\Model\ViewVehicule;
use Transport\Model\ViewVehiculeTable;

use Transport\Form\VehiculeForm;
use Transport\Form\SearchForm;


/**
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
   * ViewVehicule table manager
   * @var Transport\Model\ViewVehiculeTable
   */
  private $viewVehiculeTable; 
	
	/*
   * Vehicule manager
   * @var Plannigvehicule\Service\VehiculeManager
   */
	private $vehiculeManager;

  /*
   * Application config.
   * @var type 
   */
  private $defaultRowPerPage;

  /*
   * Application config.
   * @var type 
   */
  private $stepRowPerPage;

  /*
   * Session container.
   * @var Laminas\Session\Container
   */
  private $sessionContainer;  

  /**
   * 
   */
	public function __construct(
		VehiculeTable $vehiculeTable,
    ViewVehiculeTable $viewVehiculeTable,
		VehiculeManager $vehiculeManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
	{
    
		$this->vehiculeTable     = $vehiculeTable;
		$this->viewVehiculeTable = $viewVehiculeTable;
		$this->vehiculeManager   = $vehiculeManager;
    $this->defaultRowPerPage = $defaultRowPerPage;
    $this->stepRowPerPage    = $stepRowPerPage;
    $this->sessionContainer  = $sessionContainer;
	}

	/**
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
      'viewVehicules'  => $this->viewVehiculeTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]); 
  }
  
  /*
   * This action displays a page allowing to add a new vehicule
   */
  public function addAction()
  {
    
    // Get the list of all available brand (sorted)
    $marques = $this->vehiculeManager->getMarque();
    
    // Get the list of all available type of vehicle (sorted)
    $typeVehicules = $this->vehiculeManager->getTypeVehicule();
    
    // Create Form
    $form = new VehiculeForm($marques, $typeVehicules, 'create');

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
        $result = $this->vehiculeManager->addVehicule($data);
        if ($result instanceof Vehicule) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage("Le véhicule '" . $data['NOMVEHICULE'] . "' a été ajouté");          
          // Redirect to "index" page
          return $this->redirect()->toRoute('vehicule', ['action'=>'index']); 
        } else {
          
          // Add a flash message Error
          switch($result){

            case 1:
              $this->flashMessenger()->addErrorMessage("Le véhicule '" . $data['NOMVEHICULE'] . "' existe déjà");
              break;
            
            default:
            $this->flashMessenger()->addErrorMessage("Erreur dans la sauvegarde du véhicule '" . $data['NOMVEHICULE'] . "'");
          }
        }
      } else {
        
        // Add a flash message Error
        $this->flashMessenger()->addMessage("Des données dans le formulaire sont erronées", 'error', 0);
      }
    } 
    
    return new ViewModel([
      'form'          => $form, 
      'marques'       => $marques,
      'typeVehicules' => $typeVehicules,
    ]);  
  }
  
  /**
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
    
    // Get the list of all available brand (sorted)
    $marques = $this->vehiculeManager->getMarque();
    
    // Get the list of all available type of vehicle (sorted)
    $typeVehicules = $this->vehiculeManager->getTypeVehicule();
    
    // Create vehicule form
    $form = new VehiculeForm($marques, $typeVehicules, 'update');
    
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
      'form'          => $form,
      'marques'       => $marques,
      'typeVehicules' => $typeVehicules,
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
  
  /**
   * This action displays a page of an existing vehicule
   */
  public function infoAction()
  {
    
		$id = (int) $this->params()->fromRoute('id', -1);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
    if ($id<1) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $vehicule = $this->viewVehiculeTable->getVehicule($id);

    if ($vehicule == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    return new ViewModel([
      'module'   => 'vehicule',
      'vehicule' => $vehicule,
    ]);  
  }  
}