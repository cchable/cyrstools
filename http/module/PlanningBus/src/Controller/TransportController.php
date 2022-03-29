<?php
/**
 * @package   : module/PlanningBus/src/Controller/TransportController.php
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

use PlanningBus\Service\TransportManager;

use PlanningBus\Model\Transport;
use PlanningBus\Model\TransportTable;
use PlanningBus\Model\TransportFullTable;

use PlanningBus\Form\TransportToExcelForm;
use PlanningBus\Form\SearchForm;

use PlanningBus\Service\PlanningManager;

use PlanningBus\Model\PlanningTable;
use PlanningBus\Model\PlanningFullTable;

/*
 * 
 */
class TransportController extends AbstractActionController
{
  
  /*
   * Transport table manager
   * @var Plannigbus\Model\TransportTable
   */
  private $transportTable; 

  /*
   * TransportFull table manager
   * @var Plannigbus\Model\TransportTable
   */
  private $transportFullTable;
  
  /*
   * Transport manager
   * @var Plannigbus\Service\TransportManager
   */
  private $transportManager;

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
    TransportTable $transportTable,
    TransportFullTable $transportFullTable,
    TransportManager $transportManager,
    PlanningTable $planningTable,
    PlanningFullTable $planningFullTable,
    PlanningManager $planningManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
  {
    
    $this->transportTable     = $transportTable;
    $this->transportFullTable = $transportFullTable;
    $this->transportManager   = $transportManager;
    $this->defaultRowPerPage  = $defaultRowPerPage;
    $this->stepRowPerPage     = $stepRowPerPage;
    $this->sessionContainer   = $sessionContainer;
  }

  /*
   * This is the default "index" action of the controller. It displays the 
   * list of transport.
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
      'module'          => 'transport',
      'search'          => $search,
      'rowPerPage'      => $rowPerPage,
      'stepRowPerPage'  => $this->stepRowPerPage,
      'transportsFull'  => $this->transportFullTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
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
   * This is the "wizard" action of the controller. It displays the 
   * list of wizards.
   */
  public function excelAction()
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
    
    $transportToExcelForm = new TransportToExcelForm();
    
    return new ViewModel([
      'formSearch'      => $formSearch,
      'module'          => 'transport',
      'search'          => $search,
      'rowPerPage'      => $rowPerPage,
      'stepRowPerPage'  => $this->stepRowPerPage,
      'transportsFull'  => $this->transportFullTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]);
    
  }
  
  /*
   * This is the "search" action of the controller. It find in the 
   * list of transports.
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
    return $this->redirect()->toRoute('transport', ['action'=>'index']); 
  }
    
  /*
   * This action displays a page allowing to add a new transport.
   */
  public function addAction()
  {
    
    // Get the list of all available type transporte (sorted)
    $typesTransports = $this->transportManager->getAllTypeTransport();
    // Get the list of all available date transporte (sorted)
    $datesTransports = $this->transportManager->getAllDateTransport();
    // Get the list of all available heure transporte (sorted)
    $heuresTransports = $this->transportManager->getAllHeureTransport();
    
    // Create Form
    $form = new TransportForm('create', $typesTransports, $datesTransports, $heuresTransports);

    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {
      
      // Fill in the form with POST data
      $data = $this->params()->fromPost();
      $form->setData($data);

      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Add transport
        if ($this->transportManager->addTransport($data)) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage('Transport ajouté');          
        } else {
          
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Le transport existe déjà");
          return new ViewModel([
            'form'            => $form,
            'typesTransports'  => $typesTransports,
            'datesTransports'  => $datesTransports,
            'heuresTransports' => $heuresTransports,
          ]);
        }
        
        // Redirect to "index" page
        return $this->redirect()->toRoute('transport', ['action'=>'index']); 
      }               
    } 
    
    return new ViewModel([
      'form'            => $form,
      'typesTransports'  => $typesTransports,
      'datesTransports'  => $datesTransports,
      'heuresTransports' => $heuresTransports,
    ]);   
  }  
  
  /*
   * This action delete an transport
   */
  public function deleteAction()
  {
	
    $id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
		
      $this->getResponse()->setStatusCode(404);
      return;
    }

    $transport = $this->transportTable->findOneById($id);
    if ($transport == null) {
  
			$this->getResponse()->setStatusCode(404);
      return;
    }
    
    // Delete anneeScolaire.
    $this->transportManager->deleteTransport($id);

    // Add a flash message.
    $this->flashMessenger()->addWarningMessage("Le transport a été supprimée");

    // Redirect to "index" page
    return $this->redirect()->toRoute('transport', ['action'=>'index']);      
  }
  
  /*
   * This action displays a page allowing to edit an existing transport
   */
  public function editAction()
  {
    
		$id = (int)$this->params()->fromRoute('id', -1);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
    if ($id<1) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $transport = $this->transportTable->findOneById($id);

    if ($transport == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    // Get the list of all available type transport (sorted)
    $typesTransports = $this->transportManager->getAllTypeTransport();
    // Get the list of all available date transport (sorted)
    $datesTransports = $this->transportManager->getAllDateTransport();
    // Get the list of all available heure transport (sorted)
    $heuresTransports = $this->transportManager->getAllHeureTransport();
    
    // Create transport form
    $form = new TransportForm('update', $typesTransports, $datesTransports, $heuresTransports);
    
     // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {

      // Fill in the form with POST data
      $data = $this->params()->fromPost();            
      $form->setData($data);
    
      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Update transport
        if ($this->transportManager->updateTransport($transport, $data)) {
				
          // Add a flash message Suucess
          $this->flashMessenger()->addSuccessMessage('Transport modifié');
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Un transport existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('transport', ['action'=>'index']);
      }               
    } else {
		
      $form->setData($transport->getArrayCopy());
    }

    return new ViewModel([
      'form'            => $form,
      'typesTransports'  => $typesTransports,
      'datesTransports'  => $datesTransports,
      'heuresTransports' => $heuresTransports,
    ]); 
  }    
  
}