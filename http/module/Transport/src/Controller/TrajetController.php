<?php
/**
 * This controleur is responsible for add/edit/delete 'trajet'. 
 * 
 * @package   module/Transport/src/Controller/TrajetController.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\Controller\Plugin\FlashMessenger;

use Transport\Service\TrajetManager;

use Transport\Model\Trajet;
use Transport\Model\TrajetTable;

use Transport\Form\TrajetForm;
use Transport\Form\SearchForm;


/**
 * 
 */
class TrajetController extends AbstractActionController
{
  
	/*
	 * Trajet table manager
	 * @var Plannigtrajet\Model\TrajetTable
	 */
	private $trajetTable;
  
	/*
	 * Trajet table manager
	 * @var Plannigtrajet\Model\ViewTrajetTable
	 */
	private $viewTrajetTable;

	/*
   * Trajet manager
   * @var Plannigtrajet\Service\TrajetManager
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

  /**
   * 
   */
	public function __construct(
		TrajetTable $trajetTable,
		ViewTrajetTable $ViewTrajetTable,
		TrajetManager $trajetManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
	{
    
		$this->trajetTable       = $trajetTable;
		$this->ViewTrajetTable   = $ViewTrajetTable;
		$this->trajetManager     = $trajetManager;
    $this->defaultRowPerPage = $defaultRowPerPage;
    $this->stepRowPerPage    = $stepRowPerPage;
    $this->sessionContainer  = $sessionContainer;
	}

	/**
   * This is the default "index" action of the controller. 
   * It displays the list of trajet.
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
      'viewTrajets'    => $this->viewTrajetTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]); 
  }
  
  /*
   * This action displays a page allowing to add a new trajet
   */
  public function addAction()
  {
       
    // Create Form
    $form = new TrajetForm('create');

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
        $result = $this->trajetManager->addTrajet($data);
        if ($result instanceof Trajet) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage("L'étape '" . $data['NOMETAPE'] . "' a été ajoutée");          
          // Redirect to "index" page
          return $this->redirect()->toRoute('trajet', ['action'=>'index']); 
        } else {
          
          // Add a flash message Error
          switch($result){

            case 1:
              $this->flashMessenger()->addErrorMessage("L'étape '" . $data['NOMETAPE'] . "' existe déjà");
              break;
            
            default:
            $this->flashMessenger()->addErrorMessage("Erreur dans la sauvegarde de l'étape  '" . $data['NOMETAPE'] . "'");
          }
        }
      } else {
        
        // Add a flash message Error
        $this->flashMessenger()->addMessage("Des données dans le formulaire sont erronées", 'error', 0);
      }
    } 
    
    return new ViewModel([
      'form' => $form, 
    ]);  
  }
  
  /**
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
    
    // Create trajet form
    $form = new TrajetForm('update');
    
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
				
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage('Étape modifiée');
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Une étape '" . $data['NOMETAPE'] . "' existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('trajet', ['action'=>'index']);
      }               
    } else {
		
      $form->setData($trajet->getArrayCopy());
    }

    return new ViewModel([
      'form' => $form,
    ]); 
  }
  
  /*
   * This action delete a trajet
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
    
    // Delete véhicule
    $this->trajetManager->deleteTrajet($trajet);

    // Add a flash message
    $this->flashMessenger()->addWarningMessage("Le véhicule '$nom' a été supprimé");

    // Redirect to "index" page
    return $this->redirect()->toRoute('trajet', ['action'=>'index']);      
  }
  
  /**
   * This action displays a page of an existing trajet
   */
  public function infoAction()
  {
    
		$id = (int) $this->params()->fromRoute('id', -1);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
    if ($id<1) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $trajet = $this->viewTrajetTable->getTrajet($id);

    if ($trajet == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    return new ViewModel([
      'module'   => 'trajet',
      'trajet' => $trajet,
    ]);  
  }  
}