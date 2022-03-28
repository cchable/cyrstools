<?php
/**
 * @package   : module/PlanningBus/src/Controller/GroupeController.php
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

use PlanningBus\Service\GroupeManager;

use PlanningBus\Model\Groupe;
use PlanningBus\Model\GroupeTable;

use PlanningBus\Form\GroupeForm;
use PlanningBus\Form\SearchForm;


/*
 * 
 */
class GroupeController extends AbstractActionController
{
  
	/*
	 * Groupe table manager
	 * @var Plannigbus\Model\GroupeTable
	 */
	private $groupeTable;
	
	/*
   * Groupe manager
   * @var Plannigbus\Service\GroupeManager
   */
	private $groupeManager;
  
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
		GroupeTable $groupeTable,
		GroupeManager $groupeManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)    
	{
    
		$this->groupeTable       = $groupeTable;
		$this->groupeManager     = $groupeManager;
    $this->defaultRowPerPage = $defaultRowPerPage;
    $this->stepRowPerPage    = $stepRowPerPage;
    $this->sessionContainer  = $sessionContainer;
	}

	/*
   * This is the default "index" action of the controller. 
   * It displays the list of groupe.
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
      'module'         => 'groupe',
      'search'         => $search,
      'rowPerPage'     => $rowPerPage,
      'stepRowPerPage' => $this->stepRowPerPage,
      'groupes'        => $this->groupeTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]);
  }
    
  /*
   * This action displays a page allowing to add a new groupe.
   */
  public function addAction()
  {
    
    // Create Form
    $form = new GroupeForm('create');

    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {
      
      // Fill in the form with POST data
      $data = $this->params()->fromPost();
      $form->setData($data);

      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Add groupe
        if ($this->groupeManager->addGroupe($data)) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage('Groupe ajouté.');          
        } else {
          
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Le groupe " . $data['NOMGROUPE'] . " existe déjà.");
          return new ViewModel(['form' => $form]); 
        }
        
        // Redirect to "index" page
        return $this->redirect()->toRoute('groupe', ['action'=>'index']); 
      }               
    } 
    
    return new ViewModel(['form' => $form]);  
  }
  
	/*
   * This action delete an groupe
   */
  public function deleteAction()
  {
	
    $id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
		
      $this->getResponse()->setStatusCode(404);
      return;
    }

    $groupe = $this->groupeTable->findOneById($id);

    if ($groupe == null) {
  
			$this->getResponse()->setStatusCode(404);
      return;
    }

    $nom = $groupe->getNom();
    
    // Delete groupe.
    $this->groupeManager->deleteGroupe($groupe);

    // Add a flash message.
    $this->flashMessenger()->addWarningMessage("Le groupe $nom a été supprimé");

    // Redirect to "index" page
    return $this->redirect()->toRoute('groupe', ['action'=>'index']);      
  }
  
  /*
   * This action displays a page allowing to edit an existing groupe.
   */
  public function editAction()
  {
    
		$id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $groupe = $this->groupeTable->findOneById($id);

    if ($groupe == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    // Create groupe form
    $form = new GroupeForm('update');
    
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

        // Update groupe.
        if ($this->groupeManager->updateGroupe($groupe, $data)) {
				
          // Add a flash message Suucess
          $this->flashMessenger()->addSuccessMessage('Groupe modifié');
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Un autre groupe " . $data['NOMGROUPE'] . " existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('groupe', ['action'=>'index']);
      }               
    } else {
		
      $form->setData(array(
        'NOMGROUPE'     => $groupe->getNom(),
        'NOMBREGROUPE'  => $groupe->getNombre(),
      ));
    }

    return new ViewModel([
      'form'   => $form,
      'groupe' => $groupe
    ]); 
  }  
}