<?php
/**
 * @package   : module/PlanningBus/src/Controller/TypePlanningController.php
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

use PlanningBus\Service\TypePlanningManager;

use PlanningBus\Model\TypePlanning;
use PlanningBus\Model\TypePlanningTable;

use PlanningBus\Form\TypePlanningForm;
use PlanningBus\Form\SearchForm;


/*
 * 
 */
class TypePlanningController extends AbstractActionController
{
  
	/*
	 * TypePlanning table manager
	 * @var Plannigbus\Model\TypePlanningTable
	 */
	private $typePlanningTable;
	
	/*
   * TypePlanning manager
   * @var Plannigbus\Service\TypePlanningManager
   */
	private $typePlanningManager;
  
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
		TypePlanningTable $typePlanningTable,
		TypePlanningManager $typePlanningManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
	{
    
		$this->typePlanningTable   = $typePlanningTable;
		$this->typePlanningManager = $typePlanningManager;
    $this->defaultRowPerPage   = $defaultRowPerPage;
    $this->stepRowPerPage      = $stepRowPerPage;
    $this->sessionContainer    = $sessionContainer;
	}

	/*
   * This is the default "index" action of the controller. 
   * It displays the list of typePlanning.
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
      'module'         => 'typeplanning',
      'search'         => $search,
      'rowPerPage'     => $rowPerPage,
      'stepRowPerPage' => $this->stepRowPerPage,
      'typesPlannings' => $this->typePlanningTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]);
  }
    
  /*
   * This action displays a page allowing to add a new anneescolaire.
   */
  public function addAction()
  {
    
    // Create Form
    $form = new TypePlanningForm('create');

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
        if ($this->typePlanningManager->addTypePlanning($data)) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage('Type de planning ajoutée.');          
        } else {
          
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Le planning " . $data['NOMTYPEPLANNING'] . " existe déjà.");
          return new ViewModel(['form' => $form]); 
        }
        
        // Redirect to "index" page
        return $this->redirect()->toRoute('typeplanning', ['action'=>'index']); 
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

    $typePlanning = $this->typePlanningTable->findOneById($id);

    if ($typePlanning == null) {
  
			$this->getResponse()->setStatusCode(404);
      return;
    }

    $nom = $typePlanning->getNom();
    
    // Delete typePlanning.
    $this->typePlanningManager->deleteTypePlanning($typePlanning);

    // Add a flash message.
    $this->flashMessenger()->addWarningMessage("Le planning $nom a été supprimé");

    // Redirect to "index" page
    return $this->redirect()->toRoute('typeplanning', ['action'=>'index']);      
  }
  
  /*
   * This action displays a page allowing to edit an existing typePlanning.
   */
  public function editAction()
  {
    
		$id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $typePlanning = $this->typePlanningTable->findOneById($id);

    if ($typePlanning == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    // Create anneescolaire form
    $form = new TypePlanningForm('update');
    
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

        // Update typePlanning.
        if ($this->typePlanningManager->updateTypePlanning($typePlanning, $data)) {
				
          // Add a flash message Suucess
          $this->flashMessenger()->addSuccessMessage('Type de planning modifié');
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Une autre type de planning " . $data['NOMTYPEPLANNING'] . " existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('typeplanning', ['action'=>'index']);
      }               
    } else {
		
      $form->setData(array(
        'NOMTYPEPLANNING' => $typePlanning->getNom(), 
      ));
    }

    return new ViewModel([
      'form'         => $form,
      'typePlanning' => $typePlanning
    ]); 
  }  
}