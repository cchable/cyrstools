<?php
/**
 * This controleur is responsible for add/edit/delete 'organisation'.
 * 
 * @package   module/Transport/src/Controller/OrganisationController.php
 * @version   1.0.1
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\Controller\Plugin\FlashMessenger;

use Transport\Service\OrganisationManager;

use Transport\Model\Organisation;
use Transport\Model\OrganisationTable;
use Transport\Model\ViewOrganisationTable;

use Transport\Form\OrganisationForm;
use Transport\Form\SearchForm;


/**
 * 
 */
class OrganisationController extends AbstractActionController
{
  
	/*
	 * Organisation table manager
	 * @var Plannigorganisation\Model\OrganisationTable
	 */
	private $organisationTable;
  
	/*
	 * Organisation table manager
	 * @var Plannigorganisation\Model\ViewOrganisationTable
	 */
	private $viewOrganisationTable;

	/*
   * Organisation manager
   * @var Plannigorganisation\Service\OrganisationManager
   */
	private $organisationManager;

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
		OrganisationTable $organisationTable,
		ViewOrganisationTable $viewOrganisationTable,
		OrganisationManager $organisationManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
	{
    
		$this->organisationTable       = $organisationTable;
		$this->viewOrganisationTable   = $viewOrganisationTable;
		$this->organisationManager     = $organisationManager;
    $this->defaultRowPerPage = $defaultRowPerPage;
    $this->stepRowPerPage    = $stepRowPerPage;
    $this->sessionContainer  = $sessionContainer;
	}

	/**
   * This is the default "index" action of the controller.
   * It displays the list of organisation.
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
      'module'            => 'organisation',
      'search'            => $search,
      'rowPerPage'        => $rowPerPage,
      'stepRowPerPage'    => $this->stepRowPerPage,
      'viewOrganisations' => $this->viewOrganisationTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]);
  }
  
  /*
   * This action displays a page allowing to add a new organisation
   */
  public function addAction()
  {

    // Get the list of all available annee scolaire (sorted)
    $etapesDepart = $this->organisationManager->getEtapes();
    $etapesArrivee = &$etapesDepart;

    // Create Form
    $form = new OrganisationForm($etapesDepart, $etapesArrivee, 'create');

    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {
      
      // Fill in the form with POST data
      $data = $this->params()->fromPost();
      $form->setData($data);

      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Add organisation
        $result = $this->organisationManager->addOrganisation($data);
        if ($result instanceof Organisation) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage("Le organisation '" . $data['NOMTRAJET'] . "' a été ajouté");          
          // Redirect to "index" page
          return $this->redirect()->toRoute('organisation', ['action'=>'index']); 
        } else {
          
          // Add a flash message Error
          switch($result){

            case 1:
              $this->flashMessenger()->addErrorMessage("Le organisation '" . $data['NOMTRAJET'] . "' existe déjà");
              break;
            
            default:
            $this->flashMessenger()->addErrorMessage("Erreur dans la sauvegarde du organisation '" . $data['NOMTRAJET'] . "'");
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
   * This action displays a page allowing to edit an existing organisation
   */
  public function editAction()
  {
    
		$id = (int)$this->params()->fromRoute('id', -1);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
    if ($id<1) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $organisation = $this->organisationTable->findOneById($id);

    if ($organisation == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }

    // Get the list of all available annee scolaire (sorted)
    $etapesDepart = $this->organisationManager->getEtapes();
    $etapesArrivee = &$etapesDepart;
    
    // Create organisation form
    $form = new OrganisationForm($etapesDepart, $etapesArrivee, 'update');
  
    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {

      // Fill in the form with POST data
      $data = $this->params()->fromPost();            
      $form->setData($data);
    
      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Update organisation
        if ($this->organisationManager->updateOrganisation($organisation, $data)) {
				
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage('Organisation modifié');
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Un organisation '" . $data['NOMTRAJET'] . "' existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('organisation', ['action'=>'index']);
      }               
    } else {
		
      $form->setData($organisation->getArrayCopy());
    }

    return new ViewModel([
      'form' => $form,
    ]); 
  }
  
  /*
   * This action delete a organisation
   */
  public function deleteAction()
  {
	
    $id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
		
      $this->getResponse()->setStatusCode(404);
      return;
    }

    $organisation = $this->organisationTable->findOneById($id);
    if ($organisation == null) {
  
			$this->getResponse()->setStatusCode(404);
      return;
    }

    $nom = $organisation->getNom();
    
    // Delete véhicule
    $this->organisationManager->deleteOrganisation($organisation);

    // Add a flash message
    $this->flashMessenger()->addWarningMessage("Le organisation '$nom' a été supprimé");

    // Redirect to "index" page
    return $this->redirect()->toRoute('organisation', ['action'=>'index']);      
  }
}