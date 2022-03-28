<?php
/**
 * @package   : module/PlanningBus/src/Controller/PlanningController.php
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

use PlanningBus\Service\PlanningManager;

use PlanningBus\Model\Planning;
use PlanningBus\Model\PlanningTable;
use PlanningBus\Model\PlanningFullTable;

use PlanningBus\Form\PlanningForm;
use PlanningBus\Form\SearchForm;


/*
 * 
 */
class PlanningController extends AbstractActionController
{
  
  /*
   * Planning table manager
   * @var Plannigbus\Model\PlanningTable
   */
  private $planningTable; 

  /*
   * PlanningFull table manager
   * @var Plannigbus\Model\PlanningTable
   */
  private $planningFullTable;
  
  /*
   * Planning manager
   * @var Plannigbus\Service\PlanningManager
   */
  private $planningManager;
  
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
  

  // Add this constructor:
  public function __construct(
    PlanningTable $planningTable,
    PlanningFullTable $planningFullTable,
    PlanningManager $planningManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
  {
    
    $this->planningTable     = $planningTable;
    $this->planningFullTable = $planningFullTable;
    $this->planningManager   = $planningManager;
    $this->defaultRowPerPage = $defaultRowPerPage;
    $this->stepRowPerPage    = $stepRowPerPage;
    $this->sessionContainer  = $sessionContainer;
  }

  /*
   * This is the default "index" action of the controller. It displays the 
   * list of planning.
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
      'formSearch'      => $formSearch,
      'module'          => 'planning',
      'search'          => $search,
      'rowPerPage'      => $rowPerPage,
      'stepRowPerPage'  => $this->stepRowPerPage,
      'plannings'       => $this->planningFullTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]);
  }
  
  /*
   * This is the "wizard" action of the controller. It displays the 
   * list of wizards.
   */
  public function wizardAction()
  {
    
    return new ViewModel();   
  }
  
  /*
   * This is the "search" action of the controller. It find in the 
   * list of plannings.
   */
  public function searchAction()
  {
   
    // Create Form
    $formSearch = new SearchForm();

    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {
      
      // Fill in the form with POST data
      $data = $this->params()->fromPost();
      $formSearch->setData($data);
    }   
    
    $this->flashMessenger()->addInfoMessage('Recherche Ok');
    
    // Redirect to "index" page
    return $this->redirect()->toRoute('planning', ['action'=>'index']); 
  }
    
  /*
   * This action displays a page allowing to add a new planning.
   */
  public function addAction()
  {
    
    // Get the list of all available type planninge (sorted)
    $typesPlannings = $this->planningManager->getAllTypePlanning();
    // Get the list of all available date planninge (sorted)
    $datesPlannings = $this->planningManager->getAllDatePlanning();
    // Get the list of all available heure planninge (sorted)
    $heuresPlannings = $this->planningManager->getAllHeurePlanning();
    
    // Create Form
    $form = new PlanningForm('create', $typesPlannings, $datesPlannings, $heuresPlannings);

    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {
      
      // Fill in the form with POST data
      $data = $this->params()->fromPost();
      $form->setData($data);

      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Add planning
        if ($this->planningManager->addPlanning($data)) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage('Planning ajouté');          
        } else {
          
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Le planning existe déjà");
          return new ViewModel([
            'form'            => $form,
            'typesPlannings'  => $typesPlannings,
            'datesPlannings'  => $datesPlannings,
            'heuresPlannings' => $heuresPlannings,
          ]);
        }
        
        // Redirect to "index" page
        return $this->redirect()->toRoute('planning', ['action'=>'index']); 
      }               
    } 
    
    return new ViewModel([
      'form'            => $form,
      'typesPlannings'  => $typesPlannings,
      'datesPlannings'  => $datesPlannings,
      'heuresPlannings' => $heuresPlannings,
    ]);   
  }  
  
  /*
   * This action delete an planning
   */
  public function deleteAction()
  {
	
    $id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
		
      $this->getResponse()->setStatusCode(404);
      return;
    }

    $planning = $this->planningTable->findOneById($id);
    if ($planning == null) {
  
			$this->getResponse()->setStatusCode(404);
      return;
    }
    
    // Delete anneeScolaire.
    $this->planningManager->deletePlanning($id);

    // Add a flash message.
    $this->flashMessenger()->addWarningMessage("Le planning a été supprimée");

    // Redirect to "index" page
    return $this->redirect()->toRoute('planning', ['action'=>'index']);      
  }
  
  /*
   * This action displays a page allowing to edit an existing planning
   */
  public function editAction()
  {
    
		$id = (int)$this->params()->fromRoute('id', -1);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
    if ($id<1) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $planning = $this->planningTable->findOneById($id);

    if ($planning == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    // Get the list of all available type planninge (sorted)
    $typesPlannings = $this->planningManager->getAllTypePlanning();
    // Get the list of all available date planninge (sorted)
    $datesPlannings = $this->planningManager->getAllDatePlanning();
    // Get the list of all available heure planninge (sorted)
    $heuresPlannings = $this->planningManager->getAllHeurePlanning();
    
    // Create planning form
    $form = new PlanningForm('update', $typesPlannings, $datesPlannings, $heuresPlannings);
    
     // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {

      // Fill in the form with POST data
      $data = $this->params()->fromPost();            
      $form->setData($data);
    
      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Update planning
        if ($this->planningManager->updatePlanning($planning, $data)) {
				
          // Add a flash message Suucess
          $this->flashMessenger()->addSuccessMessage('Planning modifié');
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Un planning existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('planning', ['action'=>'index']);
      }               
    } else {
		
      $form->setData($planning->getArrayCopy());
    }

    return new ViewModel([
      'form'            => $form,
      'typesPlannings'  => $typesPlannings,
      'datesPlannings'  => $datesPlannings,
      'heuresPlannings' => $heuresPlannings,
    ]); 
  }    
  
}