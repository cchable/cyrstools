<?php
/**
 * @package   : module/PlanningBus/src/Controller/HeurePlanningController.php
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

use PlanningBus\Service\HeurePlanningManager;

use PlanningBus\Model\HeurePlanning;
use PlanningBus\Model\HeurePlanningTable;

use PlanningBus\Form\HeurePlanningForm;
use PlanningBus\Form\SearchForm;


/*
 * 
 */
class HeurePlanningController extends AbstractActionController
{
  
	/*
	 * HeurePlanning table manager
	 * @var Plannigbus\Model\HeurePlanningTable
	 */
	private $heurePlanningTable;
	
	/*
   * HeurePlanning manager
   * @var Plannigbus\Service\HeurePlanningManager
   */
	private $heurePlanningManager;

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
  
  
  /*
   * 
   */
	public function __construct(
		HeurePlanningTable $heurePlanningTable,
		HeurePlanningManager $heurePlanningManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
	{
    
		$this->heurePlanningTable   = $heurePlanningTable;
		$this->heurePlanningManager = $heurePlanningManager;
    $this->defaultRowPerPage    = $defaultRowPerPage;
    $this->stepRowPerPage       = $stepRowPerPage;
    $this->sessionContainer     = $sessionContainer;
	}

	/*
   * This is the default "index" action of the controller. 
   * It displays the list of heurePlanning.
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
      'module'          => 'heureplanning',
      'search'          => $search,
      'rowPerPage'      => $rowPerPage,
      'stepRowPerPage'  => $this->stepRowPerPage,
      'heuresPlannings' => $this->heurePlanningTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]); 
  }
    
  /*
   * This action displays a page allowing to add a new anneescolaire.
   */
  public function addAction()
  {
    
    // Create Form
    $form = new HeurePlanningForm('create');

    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {
      
      // Fill in the form with POST data
      $data = $this->params()->fromPost();
      $form->setData($data);

      // Valiheure form
      if($form->isValid()) {

        // Get filtered and valiheured data
        $data = $form->getData();

        // Add anneescolaire
        if ($this->heurePlanningManager->addHeurePlanning($data)) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage('Heure de planning ajoutée.');          
        } else {
          
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("L'heure " . $data['HEUREHEUREPLANNING'] . " existe déjà.");
          return new ViewModel(['form' => $form]); 
        }
        
        // Redirect to "index" page
        return $this->redirect()->toRoute('heureplanning', ['action'=>'index']); 
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

    $heurePlanning = $this->heurePlanningTable->findOneById($id);

    if ($heurePlanning == null) {
  
			$this->getResponse()->setStatusCode(404);
      return;
    }

    $heure = $heurePlanning->getHeure();
    
    // Delete heurePlanning.
    $this->heurePlanningManager->deleteHeurePlanning($heurePlanning);

    // Add a flash message.
    $this->flashMessenger()->addWarningMessage("L'heure $heure a été suppriméé");

    // Redirect to "index" page
    return $this->redirect()->toRoute('heureplanning', ['action'=>'index']);      
  }
  
  /*
   * This action displays a page allowing to edit an existing heurePlanning.
   */
  public function editAction()
  {
    
		$id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $heurePlanning = $this->heurePlanningTable->findOneById($id);

    if ($heurePlanning == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    // Create anneescolaire form
    $form = new HeurePlanningForm('upheure');
    
    $request = $this->getRequest();
    
    // Check if user has submitted the form
    if ($request->isPost()) {

      // Fill in the form with POST data
      $data = $this->params()->fromPost();            

      $form->setData($data);
    
      // Valiheure form
      if($form->isValid()) {

        // Get filtered and valiheured data
        $data = $form->getData();

        // Upheure heurePlanning.
        if ($this->heurePlanningManager->upheureHeurePlanning($heurePlanning, $data)) {
				
          // Add a flash message Suucess
          $this->flashMessenger()->addSuccessMessage('Heure de planning modifiée');
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Une autre heure de planning " . $data['HEUREHEUREPLANNING'] . " existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('heureplanning', ['action'=>'index']);
      }               
    } else {
		
      $form->setData(array(
        'HEUREHEUREPLANNING' => $heurePlanning->getHeure(),
      ));
    }

    return new ViewModel([
      'form'         => $form,
      'heurePlanning' => $heurePlanning
    ]); 
  }  
}