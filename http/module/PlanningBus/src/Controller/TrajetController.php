<?php
/**
 * @package   : module/PlanningBus/src/Controller/TrajetController.php
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

use PlanningBus\Service\TrajetManager;

use PlanningBus\Model\Trajet;
use PlanningBus\Model\TrajetTable;
use PlanningBus\Model\TrajetFullTable;

use PlanningBus\Form\TrajetForm;
use PlanningBus\Form\SearchForm;


/*
 * 
 */
class TrajetController extends AbstractActionController
{
  
  /*
   * Trajet table manager
   * @var Plannigbus\Model\TrajetTable
   */
  private $trajetTable; 

  /*
   * TrajetFull table manager
   * @var Plannigbus\Model\TrajetTable
   */
  private $trajetFullTable;
  
  /*
   * Trajet manager
   * @var Plannigbus\Service\TrajetManager
   */
  private $trajetManager;

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
    TrajetTable $trajetTable,
    TrajetFullTable $trajetFullTable,
    TrajetManager $trajetManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
  {
    
    $this->trajetTable       = $trajetTable;
    $this->trajetFullTable   = $trajetFullTable;
    $this->trajetManager     = $trajetManager;
    $this->defaultRowPerPage = $defaultRowPerPage;
    $this->stepRowPerPage    = $stepRowPerPage;
    $this->sessionContainer  = $sessionContainer;
  }

  /*
   * This is the default "index" action of the controller. It displays the 
   * list of trajet.
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
      'module'         => 'trajet',
      'search'         => $search,
      'rowPerPage'     => $rowPerPage,
      'stepRowPerPage' => $this->stepRowPerPage,
      'trajetsFull'    => $this->trajetFullTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]);
  }
  
  /*
   * This action displays a page allowing to add a new trajet.
   */
  public function addAction()
  {
    
    // Get the list of all available annee scolaire (sorted)
    $etapes = $this->trajetManager->getAllEtapes();
    
    // Create Form
    $form = new TrajetForm('create', $etapes);

    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {
      
      // Fill in the form with POST data
      $data = $this->params()->fromPost();
      $form->setData($data);

      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Add trajet
        if ($this->trajetManager->addTrajet($data)) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage('Trajet ajoutée.');          
        } else {
          
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Le trajet " . $data['NOMTRAJET'] . " existe déjà.");
          return new ViewModel([
            'form'   => $form,
            'etapes' => $etapes
          ]);  
        }
        
        // Redirect to "index" page
        return $this->redirect()->toRoute('trajet', ['action'=>'index']); 
      }               
    } 
    
    return new ViewModel([
      'form'   => $form,
      'etapes' => $etapes
    ]);  
  }  
  
  /*
   * This action delete an trajet
   */
  public function deleteAction()
  {
	
    $id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
		
      $this->getResponse()->setStatusCode(404);
      return;
    }

    $trajet = $this->trajetTable->findOneById($id);
    if ($trajet == null) {
  
			$this->getResponse()->setStatusCode(404);
      return;
    }

    $nom = $trajet->getNom();
    
    // Delete anneeScolaire.
    $this->trajetManager->deleteTrajet($id);

    // Add a flash message.
    $this->flashMessenger()->addWarningMessage("Le trajet $nom a été supprimé");

    // Redirect to "index" page
    return $this->redirect()->toRoute('trajet', ['action'=>'index']);      
  }
  
  /*
   * This action displays a page allowing to edit an existing trajet
   */
  public function editAction()
  {
    
		$id = (int)$this->params()->fromRoute('id', -1);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
    if ($id<1) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $trajet = $this->trajetTable->findOneById($id);

    if ($trajet == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    // Get the list of all available annee scolaire (sorted)
    $etapes = $this->trajetManager->getAllEtapes();
    
    // Create trajet form
    $form = new TrajetForm('update', $etapes);
    
     // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {

      // Fill in the form with POST data
      $data = $this->params()->fromPost();            
      $form->setData($data);
    
      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Update trajet
        if ($this->trajetManager->updateTrajet($trajet, $data)) {
				
          // Add a flash message Suucess
          $this->flashMessenger()->addSuccessMessage("Le trajet '" . $data['NOMTRAJET'] . "' a été modifié");
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Un trajet '" . $data['NOMTRAJET'] . "' existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('trajet', ['action'=>'index']);
      }               
    } else {
		
      $form->setData($trajet->getArrayCopy(false));
    }

    return new ViewModel([
      'form'   => $form,
      'etapes' => $etapes,
    ]); 
  }    
  
}