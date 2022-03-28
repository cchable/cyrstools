<?php
/**
 * @package   : module/PlanningBus/src/Controller/AnneeScolaireController.php
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

use PlanningBus\Service\AnneeScolaireManager;

use PlanningBus\Model\AnneeScolaire;
use PlanningBus\Model\AnneeScolaireTable;

use PlanningBus\Form\AnneeScolaireForm;
use PlanningBus\Form\SearchForm;


/*
 * 
 */
class AnneeScolaireController extends AbstractActionController
{
  
	/*
	 * AnneeScolaire table manager
	 * @var Plannigbus\Model\AnneeScolaireTable
	 */
	private $anneeScolaireTable;
	
	/*
   * AnneeScolaire manager
   * @var Plannigbus\Service\AnneeScolaireManager
   */
	private $anneeScolaireManager;
  
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
		AnneeScolaireTable $anneeScolaireTable,
		AnneeScolaireManager $anneeScolaireManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
	{
    
		$this->anneeScolaireTable   = $anneeScolaireTable;
		$this->anneeScolaireManager = $anneeScolaireManager;
    $this->defaultRowPerPage    = $defaultRowPerPage;
    $this->stepRowPerPage       = $stepRowPerPage;
    $this->sessionContainer     = $sessionContainer;
	}

	/*
   * This is the default "index" action of the controller. 
   * It displays the list of anneeScolaire.
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
      'module'          => 'anneescolaire',
      'search'          => $search,
      'rowPerPage'      => $rowPerPage,
      'stepRowPerPage'  => $this->stepRowPerPage,
      'anneesScolaires' => $this->anneeScolaireTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]);
  }
    
  /*
   * This action displays a page allowing to add a new anneescolaire.
   */
  public function addAction()
  {
    
    // Create Form
    $form = new AnneeScolaireForm('create');

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
        if ($this->anneeScolaireManager->addAnneeScolaire($data)) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage('Année scolaire ajoutée.');          
        } else {
          
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("L'année scolaire " . $data['ANNEEANNEESCOLAIRE'] . " existe déjà.");
          return new ViewModel(['form' => $form]); 
        }
        
        // Redirect to "index" page
        return $this->redirect()->toRoute('anneescolaire', ['action'=>'index']); 
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

    $anneeScolaire = $this->anneeScolaireTable->findOneById($id);

    if ($anneeScolaire == null) {
  
			$this->getResponse()->setStatusCode(404);
      return;
    }

    $annee = $anneeScolaire->getAnneeScolaire();
    
    // Delete anneeScolaire.
    $this->anneeScolaireManager->deleteAnneeScolaire($anneeScolaire);

    // Add a flash message.
    $this->flashMessenger()->addWarningMessage("L'année scolaire " . $annee . ' a été supprimée');

    // Redirect to "index" page
    return $this->redirect()->toRoute('anneescolaire', ['action'=>'index']);      
  }
  
  /*
   * This action displays a page allowing to edit an existing anneeScolaire.
   */
  public function editAction()
  {
    
		$id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $anneeScolaire = $this->anneeScolaireTable->findOneById($id);

    if ($anneeScolaire == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    // Create anneescolaire form
    $form = new AnneeScolaireForm('update');
    
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

        // Update anneeScolaire.
        if ($this->anneeScolaireManager->updateAnneeScolaire($anneeScolaire, $data)) {
				
          // Add a flash message Suucess
          $this->flashMessenger()->addSuccessMessage('Année scolaire modifiée');
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Une autre année scolaire " . $data['ANNEEANNEESCOLAIRE'] . " existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('anneescolaire', ['action'=>'index']);
      }               
    } else {
		
      $form->setData(array(
        'ANNEEANNEESCOLAIRE' => $anneeScolaire->getAnneeScolaire(), 
      ));
    }

    return new ViewModel([
      'form'          => $form,
      'anneeScolaire' => $anneeScolaire
    ]); 
  }  
}