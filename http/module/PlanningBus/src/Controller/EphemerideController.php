<?php
/**
 * @package   : module/PlanningBus/src/Controller/EphemerideController.php
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

use PlanningBus\Service\EphemerideManager;

use PlanningBus\Model\Ephemeride;
use PlanningBus\Model\EphemerideTable;
use PlanningBus\Model\EphemerideFullTable;

use PlanningBus\Form\EphemerideForm;
use PlanningBus\Form\SearchForm;


/*
 * 
 */
class EphemerideController extends AbstractActionController
{
  
  /*
   * Ephemeride table manager
   * @var Plannigbus\Model\EphemerideTable
   */
  private $ephemerideTable; 

  /*
   * EphemerideFull table manager
   * @var Plannigbus\Model\EphemerideTable
   */
  private $ephemerideFullTable;
  
  /*
   * Ephemeride manager
   * @var Plannigbus\Service\EphemerideManager
   */
  private $ephemerideManager;

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
    EphemerideTable $ephemerideTable,
    EphemerideFullTable $ephemerideFullTable,
    EphemerideManager $ephemerideManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
  {
    
    $this->ephemerideTable     = $ephemerideTable;
    $this->ephemerideFullTable = $ephemerideFullTable;
    $this->ephemerideManager   = $ephemerideManager;
    $this->defaultRowPerPage   = $defaultRowPerPage;
    $this->stepRowPerPage      = $stepRowPerPage;
    $this->sessionContainer    = $sessionContainer;
  }

  /*
   * This is the default "index" action of the controller. It displays the 
   * list of ephemeride.
   */
  public function indexAction()
  {
    
    // Pagination
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
    
    // Fill forme with data from url
    if ($search) 
      $formSearch->setData(['search' => $search]);
    
    return new ViewModel([
      'formSearch'      => $formSearch,
      'module'          => 'ephemeride',
      'search'          => $search,
      'rowPerPage'      => $rowPerPage,
      'stepRowPerPage'  => $this->stepRowPerPage,
      'ephemeridesFull' => $this->ephemerideFullTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]);   
  }
   
  /*
   * This action displays a page allowing to add a new ephemeride.
   */
  public function addAction()
  {
    
    // Get the list of all available annee scolaire (sorted)
    $anneesScolaires = $this->ephemerideManager->getAllAnneesScolaire();
    
    // Create Form
    $form = new EphemerideForm('create', $anneesScolaires);

    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {
      
      // Fill in the form with POST data
      $data = $this->params()->fromPost();
      $form->setData($data);

      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Add ephemeride
        if ($this->ephemerideManager->addEphemeride($data)) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage('Ephéméride ajoutée.');          
        } else {
          
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("L'éphéméride " . $data['INTITULEEPHEMERIDE'] . " existe déjà.");
          
          return new ViewModel([
            'form'            => $form,
            'anneesScolaires' => $anneesScolaires]);  
        }
        
        // Redirect to "index" page
        return $this->redirect()->toRoute('ephemeride', ['action'=>'index']); 
      }               
    } 
    
    return new ViewModel([
      'form'            => $form,
      'anneesScolaires' => $anneesScolaires]);  
  }  
  
  /*
   * This action delete an ephemeride
   */
  public function deleteAction()
  {
	
    $id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
		
      $this->getResponse()->setStatusCode(404);
      return;
    }

    $ephemeride = $this->ephemerideTable->findOneById($id);
    if ($ephemeride == null) {
  
			$this->getResponse()->setStatusCode(404);
      return;
    }

    $intitule = $ephemeride->getIntitule();
    
    // Delete anneeScolaire.
    $this->ephemerideManager->deleteEphemeride($id);

    // Add a flash message.
    $this->flashMessenger()->addWarningMessage("L'éphéméride $intitule a été supprimée");

    // Redirect to "index" page
    return $this->redirect()->toRoute('ephemeride', ['action'=>'index']);      
  }
  
  /*
   * This action displays a page allowing to edit an existing ephemeride
   */
  public function editAction()
  {
    
		$id = (int)$this->params()->fromRoute('id', -1);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
    if ($id<1) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $ephemeride = $this->ephemerideTable->findOneById($id);

    if ($ephemeride == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    // Get the list of all available annee scolaire (sorted)
    $anneesScolaires = $this->ephemerideManager->getAllAnneesScolaire();
    
    // Create ephemeride form
    $form = new EphemerideForm('update', $anneesScolaires);
    
     // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {

      // Fill in the form with POST data
      $data = $this->params()->fromPost();            
      $form->setData($data);
    
      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Update ephemeride
        if ($this->ephemerideManager->updateEphemeride($ephemeride, $data)) {
				
          // Add a flash message Suucess
          $this->flashMessenger()->addSuccessMessage('Ephéméride modifiée');
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Une éphéméride '" . $data['INTITULEEPHEMERIDE'] . "' existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('ephemeride', ['action'=>'index']);
      }               
    } else {
		
      $form->setData($ephemeride->getArrayCopy());
    }

    return new ViewModel([
      'form'            => $form,
      'anneesScolaires' => $anneesScolaires,
    ]); 
  }    
  
}