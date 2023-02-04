<?php
/**
 * This controleur is responsible for add/edit/delete 'IndisponibiliteVehicule'. 
 * 
 * @package   module/Transport/src/Controller/IndisponibiliteVehiculeController.php
 * @version   1.0.1
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Transport\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\Controller\Plugin\FlashMessenger;

use Transport\Service\IndisponibiliteVehiculeManager;

use Transport\Model\IndisponibiliteVehicule;
use Transport\Model\IndisponibiliteVehiculeTable;
use Transport\Model\ViewIndisponibiliteVehiculeTable;

use Transport\Form\IndisponibiliteVehiculeForm;
use Transport\Form\SearchForm;


/*
 * 
 */
class IndisponibiliteVehiculeController extends AbstractActionController
{
  
  /**
   * IndisponibiliteVehicule table manager
   * @var Transport\Model\IndisponibiliteVehiculeTable
   */
  private $indisponibiliteVehiculeTable;
  
  /**
   * ViewIndisponibiliteVehicule table manager
   * @var Transport\Model\ViewIndisponibiliteVehiculeTable
   */
  private $viewIndisponibiliteVehiculeTable; 
  
  /**
   * Indisponibilitehauffeur manager
   * @var Transport\Service\VehiculeManager
   */
  private $indisponibiliteVehiculeManager;

  /**
   * Application config.
   * @var type 
   */
  private $defaultRowPerPage;

  /**
   * Application stepRowPerPage.
   * @var int 
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
    IndisponibiliteVehiculeTable     $indisponibiliteVehiculeTable,
    ViewIndisponibiliteVehiculeTable $viewIndisponibiliteVehiculeTable,
    IndisponibiliteVehiculeManager   $indisponibiliteVehiculeManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
  {
    
    $this->indisponibiliteVehiculeTable     = $indisponibiliteVehiculeTable;
    $this->viewIndisponibiliteVehiculeTable = $viewIndisponibiliteVehiculeTable;
    $this->indisponibiliteVehiculeManager   = $indisponibiliteVehiculeManager;
    $this->defaultRowPerPage = $defaultRowPerPage;
    $this->stepRowPerPage    = $stepRowPerPage;
    $this->sessionContainer  = $sessionContainer;
  }

  /*
   * This is the default "index" action of the controller. It displays the 
   * list of vehicule.
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
      'formSearch'                => $formSearch,
      'module'                    => 'indisponibilitevehicule',
      'search'                    => $search,
      'rowPerPage'                => $rowPerPage,
      'stepRowPerPage'            => $this->stepRowPerPage,
      'indisponibilitesVehicules' => $this->viewIndisponibiliteVehiculeTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]);   
  }
   
  /*
   * This action displays a page allowing to add a new vehicule.
   */
  public function addAction()
  {
    
    // Get the list of all available annee scolaire (sorted)
    $vehicules = $this->indisponibiliteVehiculeManager->getVehicules();

    // Create Form
    $form = new IndisponibiliteVehiculeForm($vehicules);

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
        
        // Add indisponibilitevehicule
        if ($this->indisponibiliteVehiculeManager->addIndisponibiliteVehicule($data)) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage("L'indisponibilité du vehicule a été ajoutée");
          // Redirect to "index" page
          return $this->redirect()->toRoute('indisponibilitevehicule', ['action'=>'index']); 
        } else {
          
          // Add a flash message Error
          $this->flashMessenger()->addMessage("L'indisponibilité du vehicule existe déjà", 'error', 0);
        }
      } else {
        
        // Add a flash message Error
        $this->flashMessenger()->addMessage("Des données dans le formulaire sont erronées", 'error', 0);
      }             
    } 
    
    return new ViewModel([
      'form'      => $form,
      'vehicules' => $vehicules 
    ]);   
  }  
  
  /*
   * This action delete an indisponibilitevehicule
   */
  public function deleteAction()
  {
	
    $id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
		
      $this->getResponse()->setStatusCode(404);
      return;
    }

    $indisponibiliteVehicule = $this->indisponibiliteVehiculeTable->findOneById($id);
    if ($indisponibiliteVehicule == null) {
  
			$this->getResponse()->setStatusCode(404);
      return;
    }

    $date = $indisponibiliteVehicule->getDateDebut();
    
    // Delete vehicule.
    $this->indisponibiliteVehiculeManager->deleteIndisponibiliteVehicule($id);

    // Add a flash message.
    $this->flashMessenger()->addWarningMessage("L'indisponibilité du $date a été supprimée");

    // Redirect to "index" page
    return $this->redirect()->toRoute('indisponibilitevehicule', ['action'=>'index']);      
  }
  
  /*
   * This action displays a page allowing to edit an existing indisponibilitevehicule
   */
  public function editAction()
  {
    
		$id = (int)$this->params()->fromRoute('id', -1);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
    if ($id<1) {
      
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $indisponibiliteVehicule = $this->indisponibiliteVehiculeTable->findOneById($id);

    if ($indisponibiliteVehicule == null) {
      
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    // Get the list of all available vehicule (sorted)
    $vehicules = $this->indisponibiliteVehiculeManager->getVehicules();
    
    // Create Form
    $form = new IndisponibiliteVehiculeForm($vehicules);
    
     // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {

      // Fill in the form with POST data
      $data = $this->params()->fromPost();            
      $form->setData($data);
    
      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        $date = $indisponibiliteVehicule->getDateDebut();
        // Update vehicule
        if ($this->indisponibiliteVehiculeManager->updateIndisponibiliteVehicule($indisponibiliteVehicule, $data)) {
				
          // Add a flash message Suucess
          $this->flashMessenger()->addSuccessMessage("L'indisponibilité du $date a été modifiée.");
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("L'indisponibilité du $date existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('indisponibilitevehicule', ['action'=>'index']);
      }               
    } else {
		
      $form->setData($indisponibiliteVehicule->getArrayCopy(true));
    }

    return new ViewModel([
      'form'      => $form,
      'vehicules' => $vehicules 
    ]);
  }
  
 //ToDo   
  private function initDataForPaginator() {  
 
  }
}