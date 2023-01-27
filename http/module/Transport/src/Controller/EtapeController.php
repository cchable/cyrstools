<?php
/**
 * This controleur is responsible for add/edit/delete 'etape'. 
 * 
 * @package   module/Transport/src/Controller/EtapeController.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\Controller\Plugin\FlashMessenger;

use Transport\Service\EtapeManager;

use Transport\Model\Etape;
use Transport\Model\EtapeTable;

use Transport\Form\EtapeForm;
use Transport\Form\SearchForm;


/**
 * 
 */
class EtapeController extends AbstractActionController
{
  
	/*
	 * Etape table manager
	 * @var Plannigetape\Model\EtapeTable
	 */
	private $etapeTable;

	/*
   * Etape manager
   * @var Plannigetape\Service\EtapeManager
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

  /**
   * 
   */
	public function __construct(
		EtapeTable $etapeTable,
		EtapeManager $etapeManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
	{
    
		$this->etapeTable     = $etapeTable;
		$this->etapeManager   = $etapeManager;
    $this->defaultRowPerPage = $defaultRowPerPage;
    $this->stepRowPerPage    = $stepRowPerPage;
    $this->sessionContainer  = $sessionContainer;
	}

	/**
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
   * This action displays a page allowing to add a new etape
   */
  public function addAction()
  {
    
    // Get the list of all available brand (sorted)
    $marques = $this->etapeManager->getMarque();
    
    // Get the list of all available type of vehicle (sorted)
    $typeEtapes = $this->etapeManager->getTypeEtape();
    
    // Create Form
    $form = new EtapeForm($marques, $typeEtapes, 'create');

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
        $result = $this->etapeManager->addEtape($data);
        if ($result instanceof Etape) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage("Le véhicule '" . $data['NOMVEHICULE'] . "' a été ajouté");          
          // Redirect to "index" page
          return $this->redirect()->toRoute('etape', ['action'=>'index']); 
        } else {
          
          // Add a flash message Error
          switch($result){

            case 1:
              $this->flashMessenger()->addErrorMessage("Le véhicule '" . $data['NOMVEHICULE'] . "' existe déjà");
              break;
            
            default:
            $this->flashMessenger()->addErrorMessage("Erreur dans la sauvegarde du véhicule '" . $data['NOMVEHICULE'] . "'");
          }
        }
      } else {
        
        // Add a flash message Error
        $this->flashMessenger()->addMessage("Des données dans le formulaire sont erronées", 'error', 0);
      }
    } 
    
    return new ViewModel([
      'form'          => $form, 
      'marques'       => $marques,
      'typeEtapes' => $typeEtapes,
    ]);  
  }
  
  /**
   * This action displays a page allowing to edit an existing etape
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
    
    // Get the list of all available brand (sorted)
    $marques = $this->etapeManager->getMarque();
    
    // Get the list of all available type of vehicle (sorted)
    $typeEtapes = $this->etapeManager->getTypeEtape();
    
    // Create etape form
    $form = new EtapeForm($marques, $typeEtapes, 'update');
    
     // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {

      // Fill in the form with POST data
      $data = $this->params()->fromPost();            
      $form->setData($data);
    
      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Update etape
        if ($this->etapeManager->updateEtape($etape, $data)) {
				
          // Add a flash message Suucess
          $this->flashMessenger()->addSuccessMessage('Véhicule modifié');
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Un véhicule '" . $data['NOMVEHICULE'] . "' existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('etape', ['action'=>'index']);
      }               
    } else {
		
      $form->setData($etape->getArrayCopy());
    }

    return new ViewModel([
      'form'          => $form,
      'marques'       => $marques,
      'typeEtapes' => $typeEtapes,
    ]); 
  }
  
  /*
   * This action delete a etape
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
    
    // Delete véhicule
    $this->etapeManager->deleteEtape($etape);

    // Add a flash message
    $this->flashMessenger()->addWarningMessage("Le véhicule '$nom' a été supprimé");

    // Redirect to "index" page
    return $this->redirect()->toRoute('etape', ['action'=>'index']);      
  }
  
  /**
   * This action displays a page of an existing etape
   */
  public function infoAction()
  {
    
		$id = (int) $this->params()->fromRoute('id', -1);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
    if ($id<1) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $etape = $this->viewEtapeTable->getEtape($id);

    if ($etape == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    return new ViewModel([
      'module'   => 'etape',
      'etape' => $etape,
    ]);  
  }  
}