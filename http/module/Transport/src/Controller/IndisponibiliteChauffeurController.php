<?php
/**
 * @package   : module/Transport/src/Controller/IndisponibiliteChauffeurController.php
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-22 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\Controller\Plugin\FlashMessenger;

use Transport\Service\IndisponibiliteChauffeurManager;

use Transport\Model\IndisponibiliteChauffeur;
use Transport\Model\IndisponibiliteChauffeurTable;
use Transport\Model\IndisponibiliteChauffeurTableView;

use Transport\Form\IndisponibiliteChauffeurForm;
use Transport\Form\SearchForm;


/*
 * 
 */
class IndisponibiliteChauffeurController extends AbstractActionController
{
  
  /*
   * IndisponibiliteChauffeur table manager
   * @var Transport\Model\IndisponibiliteTable
   */
  private $indisponibiliteChauffeurTable;
  
  /*
   * IndisponibiliteChauffeur tableview manager
   * @var Transport\Model\IndisponibiliteChauffeurTableView
   */
  private $indisponibiliteChauffeurTableView; 
  
  /*
   * CIndisponibilitehauffeur manager
   * @var Transport\Service\ChauffeurManager
   */
  private $indisponibiliteChauffeurManager;

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
    IndisponibiliteChauffeurTable   	$indisponibiliteChauffeurTable,
    IndisponibiliteChauffeurTableView $indisponibiliteChauffeurTableView,
    IndisponibiliteChauffeurManager   $indisponibiliteChauffeurManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
  {
    
    $this->indisponibiliteChauffeurTable     = $indisponibiliteChauffeurTable;
    $this->indisponibiliteChauffeurTableView = $indisponibiliteChauffeurTableView;
    $this->indisponibiliteChauffeurManager   = $indisponibiliteChauffeurManager;
    $this->defaultRowPerPage = $defaultRowPerPage;
    $this->stepRowPerPage    = $stepRowPerPage;
    $this->sessionContainer  = $sessionContainer;
  }

  /*
   * This is the default "index" action of the controller. It displays the 
   * list of chauffeur.
   */
  public function indexAction()
  {
    
    // Getting requested page number from the query
    // or to 1 if none is set, or the page is invalid
    $pageNumber = (int) $this->params()->fromQuery('page', 1);
    $pageNumber = ($pageNumber < 1) ? 1 : $pageNumber;

    // Getting requested number per page from the query
    // or to default if none is set, or the number per page is invalid
    //$defaultRowPerPage = $this->config['paginator']['options']['defaultRowPerPage'];
    //$rowPerPage = (int) $this->params()->fromQuery('rowPerPage', $this->defaultRowPerPage);
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
      'formSearch'                 => $formSearch,
      'module'                     => 'indisponibilitechauffeur',
      'search'                     => $search,
      'rowPerPage'                 => $rowPerPage,
      'stepRowPerPage'             => $this->stepRowPerPage,
      'indisponibilitesChauffeurs' => $this->indisponibiliteChauffeurTableView->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]);   
  }
   
  /*
   * This action displays a page allowing to add a new chauffeur.
   */
  public function addAction()
  {
    
    // Get the list of all available annee scolaire (sorted)
    $chauffeurs = $this->indisponibiliteChauffeurManager->getChauffeurs();

    // Create Form
    $form = new IndisponibiliteChauffeurForm($chauffeurs);

    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {
      
      // Fill in the form with POST data
      $data = $this->params()->fromPost();
      
      if($data['ALLDAYINDISPONIBILITE']) {
        $data['STARTTIMEINDISPONIBILITE'] = '00:00:00';
        $data['ENDTIMEINDISPONIBILITE']   = '23:59:59';
        $data['ENDDATEINDISPONIBILITE']   = $data['STARTDATEINDISPONIBILITE'];
      }
      $form->setData($data);

      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();      
        
        // Add indisponibilitechauffeur
        if ($this->indisponibiliteChauffeurManager->addIndisponibiliteChauffeur($data)) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage("L'indisponibilité du chauffeur a été ajoutée");
          // Redirect to "index" page
          return $this->redirect()->toRoute('indisponibilitechauffeur', ['action'=>'index']); 
        } else {
          
          // Add a flash message Error
          $this->flashMessenger()->addMessage("L'indisponibilité du chauffeur existe déjà", 'error', 0);
        }
      } else {
        
        // Add a flash message Error
        $this->flashMessenger()->addMessage("Des données dans le formulaire sont erronées", 'error', 0);
      }             
    } 
    
    return new ViewModel([
      'form'       => $form,
      'chauffeurs' => $chauffeurs 
    ]);   
  }  
  
  /*
   * This action delete an indisponibilitechauffeur
   */
  public function deleteAction()
  {
	
    $id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
		
      $this->getResponse()->setStatusCode(404);
      return;
    }

    $indisponibiliteChauffeur = $this->indisponibiliteChauffeurTable->findOneById($id);
    if ($indisponibiliteChauffeur == null) {
  
			$this->getResponse()->setStatusCode(404);
      return;
    }

    $date = $indisponibiliteChauffeur->getDateDebut();
    
    // Delete chauffeur.
    $this->indisponibiliteChauffeurManager->deleteIndisponibiliteChauffeur($id);

    // Add a flash message.
    $this->flashMessenger()->addWarningMessage("L'indisponibilité du $date a été supprimée");

    // Redirect to "index" page
    return $this->redirect()->toRoute('indisponibilitechauffeur', ['action'=>'index']);      
  }
  
  /*
   * This action displays a page allowing to edit an existing indisponibilitechauffeur
   */
  public function editAction()
  {
    
		$id = (int)$this->params()->fromRoute('id', -1);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
    if ($id<1) {
      
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $indisponibiliteChauffeur = $this->indisponibiliteChauffeurTable->findOneById($id);

    if ($indisponibiliteChauffeur == null) {
      
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    // Get the list of all available chauffeur (sorted)
    $chauffeurs = $this->indisponibiliteChauffeurManager->getChauffeurs();
    
    // Create Form
    $form = new IndisponibiliteChauffeurForm($chauffeurs);
    
     // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {

      // Fill in the form with POST data
      $data = $this->params()->fromPost();            
      $form->setData($data);
    
      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        $date = $indisponibiliteChauffeur->getDateDebut();
        // Update chauffeur
        if ($this->indisponibiliteChauffeurManager->updateIndisponibiliteChauffeur($indisponibiliteChauffeur, $data)) {
				
          // Add a flash message Suucess
          $this->flashMessenger()->addSuccessMessage("L'indisponibilité du $date a été modifiée.");
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("L'indisponibilité du $date existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('indisponibilitechauffeur', ['action'=>'index']);
      }               
    } else {
		
      $form->setData($indisponibiliteChauffeur->getArrayCopy());
    }

    return new ViewModel([
      'form'       => $form,
      'chauffeurs' => $chauffeurs 
    ]);
  }
  
 //ToDo   
  private function initDataForPaginator() {  
 
  }
}