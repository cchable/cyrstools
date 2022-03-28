<?php
/**
 * @package   : module/PlanningBus/src/Controller/EtapeController.php
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

use PlanningBus\Service\EtapeManager;

use PlanningBus\Model\Etape;
use PlanningBus\Model\EtapeTable;

use PlanningBus\Form\EtapeForm;
use PlanningBus\Form\SearchForm;


/*
 * 
 */
class EtapeController extends AbstractActionController
{
  
	/*
	 * Etape table manager
	 * @var Plannigbus\Model\EtapeTable
	 */
	private $etapeTable;
	
	/*
   * Etape manager
   * @var Plannigbus\Service\EtapeManager
   */
	private $etapeManager;
  
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
		EtapeTable $etapeTable,
		EtapeManager $etapeManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
	{
    
		$this->etapeTable        = $etapeTable;
		$this->etapeManager      = $etapeManager;
    $this->defaultRowPerPage = $defaultRowPerPage;
    $this->stepRowPerPage    = $stepRowPerPage;
    $this->sessionContainer  = $sessionContainer;
	}

	/*
   * This is the default "index" action of the controller. 
   * It displays the list of etape.
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
      'module'         => 'etape',
      'search'         => $search,
      'rowPerPage'     => $rowPerPage,
      'stepRowPerPage' => $this->stepRowPerPage,
      'etapes'         => $this->etapeTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]);
  }
    
  /*
   * This action displays a page allowing to add a new etape.
   */
  public function addAction()
  {
    
    // Create Form
    $form = new EtapeForm('create');

    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {
      
      // Fill in the form with POST data
      $data = $this->params()->fromPost();
      $form->setData($data);

      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Add etape
        if ($this->etapeManager->addEtape($data)) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage('Etape ajoutée.');          
        } else {
          
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("L'étape " . $data['NOMETAPE'] . " existe déjà.");
          return new ViewModel(['form' => $form]); 
        }
        
        // Redirect to "index" page
        return $this->redirect()->toRoute('etape', ['action'=>'index']); 
      }               
    } 
    
    return new ViewModel(['form' => $form]);  
  }
  
	/*
   * This action delete an etape
   */
  public function deleteAction()
  {
	
    $id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
		
      $this->getResponse()->setStatusCode(404);
      return;
    }

    $etape = $this->etapeTable->findOneById($id);

    if ($etape == null) {
  
			$this->getResponse()->setStatusCode(404);
      return;
    }

    $nom = $etape->getNom();
    
    // Delete etape.
    $this->etapeManager->deleteEtape($etape);

    // Add a flash message.
    $this->flashMessenger()->addWarningMessage("L'étape " . $nom . ' a été supprimée');

    // Redirect to "index" page
    return $this->redirect()->toRoute('etape', ['action'=>'index']);      
  }
  
  /*
   * This action displays a page allowing to edit an existing etape.
   */
  public function editAction()
  {
    
		$id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $etape = $this->etapeTable->findOneById($id);

    if ($etape == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    // Create etape form
    $form = new EtapeForm('update');
    
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

        // Update etape.
        if ($this->etapeManager->updateEtape($etape, $data)) {
				
          // Add a flash message Suucess
          $this->flashMessenger()->addSuccessMessage('Etape modifiée');
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Une autre étape " . $data['NOMETAPE'] . " existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('etape', ['action'=>'index']);
      }               
    } else {
		
      $form->setData(array(
        'NOMETAPE'      => $etape->getNom(),
        'ADRESSEETAPE'  => $etape->getAdresse(),
        'PRINTEDEETAPE' => $etape->getPrinted(),
      ));
    }

    return new ViewModel([
      'form'  => $form,
      'etape' => $etape
    ]); 
  }  
}