<?php
/**
 * This controleur is responsible for add/edit/delete 'organisarion'. 
 * 
 * @package   module/Transport/src/Controller/OrganisarionController.php
 * @version   1.0.1
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\Controller\Plugin\FlashMessenger;

use Transport\Service\OrganisarionManager;

use Transport\Model\Organisarion;
use Transport\Model\OrganisarionTable;
use Transport\Model\ViewOrganisarionTable;

use Transport\Form\OrganisarionForm;
use Transport\Form\SearchForm;


/**
 * 
 */
class OrganisarionController extends AbstractActionController
{
  
	/*
	 * Organisarion table manager
	 * @var Plannigorganisarion\Model\OrganisarionTable
	 */
	private $organisarionTable;
  
	/*
	 * Organisarion table manager
	 * @var Plannigorganisarion\Model\ViewOrganisarionTable
	 */
	private $viewOrganisarionTable;

	/*
   * Organisarion manager
   * @var Plannigorganisarion\Service\OrganisarionManager
   */
	private $organisarionManager;

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
		OrganisarionTable $organisarionTable,
		ViewOrganisarionTable $viewOrganisarionTable,
		OrganisarionManager $organisarionManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
	{
    
		$this->organisarionTable       = $organisarionTable;
		$this->viewOrganisarionTable   = $viewOrganisarionTable;
		$this->organisarionManager     = $organisarionManager;
    $this->defaultRowPerPage = $defaultRowPerPage;
    $this->stepRowPerPage    = $stepRowPerPage;
    $this->sessionContainer  = $sessionContainer;
	}

	/**
   * This is the default "index" action of the controller. 
   * It displays the list of organisarion.
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
      'formSearch'        => $formSearch,
      'module'            => 'organisarion',
      'search'            => $search,
      'rowPerPage'        => $rowPerPage,
      'stepRowPerPage'    => $this->stepRowPerPage,
      'viewOrganisarions' => $this->viewOrganisarionTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]);
  }
  
  /*
   * This action displays a page allowing to add a new organisarion
   */
  public function addAction()
  {

    // Get the list of all available annee scolaire (sorted)
    $etapesDepart = $this->organisarionManager->getEtapes();
    $etapesArrivee = &$etapesDepart;

    // Create Form
    $form = new OrganisarionForm($etapesDepart, $etapesArrivee, 'create');

    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {
      
      // Fill in the form with POST data
      $data = $this->params()->fromPost();
      $form->setData($data);

      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Add organisarion
        $result = $this->organisarionManager->addOrganisarion($data);
        if ($result instanceof Organisarion) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage("Le organisarion '" . $data['NOMTRAJET'] . "' a été ajouté");          
          // Redirect to "index" page
          return $this->redirect()->toRoute('organisarion', ['action'=>'index']); 
        } else {
          
          // Add a flash message Error
          switch($result){

            case 1:
              $this->flashMessenger()->addErrorMessage("Le organisarion '" . $data['NOMTRAJET'] . "' existe déjà");
              break;
            
            default:
            $this->flashMessenger()->addErrorMessage("Erreur dans la sauvegarde du organisarion '" . $data['NOMTRAJET'] . "'");
          }
        }
      } else {
        
        // Add a flash message Error
        $this->flashMessenger()->addMessage("Des données dans le formulaire sont erronées", 'error', 0);
      }
    } 
    
    return new ViewModel([
      'form' => $form,
      'etapesDepart'  => $etapesDepart,
      'etapesArrivee' => $etapesArrivee,
    ]);  
  }
  
  /**
   * This action displays a page allowing to edit an existing organisarion
   */
  public function editAction()
  {
    
		$id = (int)$this->params()->fromRoute('id', -1);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
    if ($id<1) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $organisarion = $this->organisarionTable->findOneById($id);

    if ($organisarion == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }

    // Get the list of all available annee scolaire (sorted)
    $etapesDepart = $this->organisarionManager->getEtapes();
    $etapesArrivee = &$etapesDepart;
    
    // Create organisarion form
    $form = new OrganisarionForm($etapesDepart, $etapesArrivee, 'update');
  
    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {

      // Fill in the form with POST data
      $data = $this->params()->fromPost();            
      $form->setData($data);
    
      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Update organisarion
        if ($this->organisarionManager->updateOrganisarion($organisarion, $data)) {
				
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage('Organisarion modifié');
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Un organisarion '" . $data['NOMTRAJET'] . "' existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('organisarion', ['action'=>'index']);
      }               
    } else {
		
      $form->setData($organisarion->getArrayCopy());
    }

    return new ViewModel([
      'form' => $form,
    ]); 
  }
  
  /*
   * This action delete a organisarion
   */
  public function deleteAction()
  {
	
    $id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
		
      $this->getResponse()->setStatusCode(404);
      return;
    }

    $organisarion = $this->organisarionTable->findOneById($id);
    if ($organisarion == null) {
  
			$this->getResponse()->setStatusCode(404);
      return;
    }

    $nom = $organisarion->getNom();
    
    // Delete véhicule
    $this->organisarionManager->deleteOrganisarion($organisarion);

    // Add a flash message
    $this->flashMessenger()->addWarningMessage("Le organisarion '$nom' a été supprimé");

    // Redirect to "index" page
    return $this->redirect()->toRoute('organisarion', ['action'=>'index']);      
  }
}