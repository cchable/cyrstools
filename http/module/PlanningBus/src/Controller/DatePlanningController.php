<?php
/**
 * @package   : module/PlanningBus/src/Controller/DatePlanningController.php
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

use PlanningBus\Service\DatePlanningManager;

use PlanningBus\Model\DatePlanning;
use PlanningBus\Model\DatePlanningTable;

use PlanningBus\Form\DatePlanningForm;
use PlanningBus\Form\SearchForm;


/*
 * 
 */
class DatePlanningController extends AbstractActionController
{
  
	/*
	 * DatePlanning table manager
	 * @var Plannigbus\Model\DatePlanningTable
	 */
	private $datePlanningTable;
	
	/*
   * DatePlanning manager
   * @var Plannigbus\Service\DatePlanningManager
   */
	private $datePlanningManager;

  /*
   * 
   */
	public function __construct(
		DatePlanningTable $datePlanningTable,
		DatePlanningManager $datePlanningManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
	{
    
		$this->datePlanningTable   = $datePlanningTable;
		$this->datePlanningManager = $datePlanningManager;
    $this->defaultRowPerPage    = $defaultRowPerPage;
    $this->stepRowPerPage       = $stepRowPerPage;
    $this->sessionContainer     = $sessionContainer;
	}

	/*
   * This is the default "index" action of the controller. 
   * It displays the list of datePlanning.
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
      'module'         => 'dateplanning',
      'search'         => $search,
      'rowPerPage'     => $rowPerPage,
      'stepRowPerPage' => $this->stepRowPerPage,
      'datesPlannings' => $this->datePlanningTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]);
  }
    
  /*
   * This action displays a page allowing to add a new anneescolaire.
   */
  public function addAction()
  {
    
    // Create Form
    $form = new DatePlanningForm('create');

    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {
      
      // Fill in the form with POST data
      $data = $this->params()->fromPost();
      $form->setData($data);

      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Add anneescolaire
        if ($this->datePlanningManager->addDatePlanning($data)) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage('Date de planning ajoutée.');          
        } else {
          
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("La date " . $data['DATEDATEPLANNING'] . " existe déjà.");
          return new ViewModel(['form' => $form]); 
        }
        
        // Redirect to "index" page
        return $this->redirect()->toRoute('dateplanning', ['action'=>'index']); 
      }               
    } 
    
    return new ViewModel(['form' => $form]);  
  }
  
	/*
   * This action delete an anneescolaire
   */
  public function deleteAction()
  {
	
    $id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
		
      $this->getResponse()->setStatusCode(404);
      return;
    }

    $datePlanning = $this->datePlanningTable->findOneById($id);

    if ($datePlanning == null) {
  
			$this->getResponse()->setStatusCode(404);
      return;
    }

    $date = $datePlanning->getDate();
    
    // Delete datePlanning.
    $this->datePlanningManager->deleteDatePlanning($datePlanning);

    // Add a flash message.
    $this->flashMessenger()->addWarningMessage("La date $date a été suppriméé");

    // Redirect to "index" page
    return $this->redirect()->toRoute('dateplanning', ['action'=>'index']);      
  }
  
  /*
   * This action displays a page allowing to edit an existing datePlanning.
   */
  public function editAction()
  {
    
		$id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $datePlanning = $this->datePlanningTable->findOneById($id);

    if ($datePlanning == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    // Create anneescolaire form
    $form = new DatePlanningForm('update');
    
    $request = $this->getRequest();
    
    // Check if user has submitted the form
    if ($request->isPost()) {

      // Fill in the form with POST data
      $data = $this->params()->fromPost();            

      $form->setData($data);
    
      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Update datePlanning.
        if ($this->datePlanningManager->updateDatePlanning($datePlanning, $data)) {
				
          // Add a flash message Suucess
          $this->flashMessenger()->addSuccessMessage('Date de planning modifiée');
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Une autre date de planning " . $data['DATEDATEPLANNING'] . " existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('dateplanning', ['action'=>'index']);
      }               
    } else {
		
      $form->setData(array(
        'DATEDATEPLANNING'        => $datePlanning->getDate(),
        'CODESEMAINEDATEPLANNING' => $datePlanning->getCode(),
      ));
    }

    return new ViewModel([
      'form'         => $form,
      'datePlanning' => $datePlanning
    ]); 
  }  
}