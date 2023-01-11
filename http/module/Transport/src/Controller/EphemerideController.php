<?php
/**
 * This controleur is responsible for add/edit/delete 'ephemeride'. 
 * 
 * @package   module/Transport/src/Controller/EphemerideController.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Transport\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\Controller\Plugin\FlashMessenger;

use Transport\Service\EphemerideManager;

use Transport\Model\Ephemeride;
use Transport\Model\EphemerideTable;
use Transport\Model\ViewEphemerideTable;

use Transport\Form\EphemerideForm;
use Transport\Form\SearchForm;


/*
 * 
 */
class EphemerideController extends AbstractActionController
{
  
  /*
   * Ephemeride table manager
   * @var Transport\Model\EphemerideTable
   */
  private $ephemerideTable;   
  
  /*
   * ViewEphemeride table manager
   * @var Transport\Model\ViewEphemerideTable
   */
  private $viewEphemerideTable; 
  
  /*
   * Ephemeride manager
   * @var Transport\Service\EphemerideManager
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
    EphemerideTable     $ephemerideTable,
    ViewEphemerideTable $viewEphemerideTable,
    EphemerideManager   $ephemerideManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
  {
    
    $this->ephemerideTable     = $ephemerideTable;
    $this->viewEphemerideTable = $viewEphemerideTable;
    $this->ephemerideManager   = $ephemerideManager;
    $this->defaultRowPerPage = $defaultRowPerPage;
    $this->stepRowPerPage    = $stepRowPerPage;
    $this->sessionContainer  = $sessionContainer;
  }

  /**
   * This is the default "index" action of the controller. 
   * It displays the list of ephemeride.
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
      'module'         => 'ephemeride',
      'search'         => $search,
      'rowPerPage'     => $rowPerPage,
      'stepRowPerPage' => $this->stepRowPerPage,
      'ephemerides'    => $this->viewEphemerideTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]);   
  }
   
  /*
   * This action displays a page allowing to add a new ephemeride.
   */
  public function addAction()
  {
    
    // Get the list of all available school year (sorted)
    $anneesScolaires = $this->ephemerideManager->getAnneesScolaires();
    
    // Create ephemeride Form
    $form = new EphemerideForm($anneesScolaires, 'create');

    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {
      
      // Fill in the form with POST data
      $data = $this->params()->fromPost();
      $form->setData($data);

      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();  
        
        // Add Ephemeride
        $result = $this->ephemerideManager->addEphemeride($data);
        if ($result instanceof Ephemeride) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage("L'éphéméride '" . $data['NOMEPHEMERIDE'] . "' a été ajoutée");
          // Redirect to "index" page
          return $this->redirect()->toRoute('ephemeride', ['action'=>'index']); 
        } else {
          
          // Add a flash message Error
          switch($result){
            
            case 1:
              $this->flashMessenger()->addErrorMessage("L'éphéméride en date du '" . $data['STARTDATEPHEMERIDE'] . "' existe déjà");
              break;
            
            case 2:
              $this->flashMessenger()->addErrorMessage("La date de fin de l'éphéméride du '" . $data['STARTDATEPHEMERIDE'] . "' est déjà incluse dans une autre éphéméride");
              break;
            
            case 3:
              $this->flashMessenger()->addErrorMessage("La date de début de l'éphéméride du '" . $data['STARTDATEPHEMERIDE'] . "' est déjà incluse dans une autre éphéméride");
              
              break;
            
            case 4:
              $this->flashMessenger()->addErrorMessage("L'éphéméride en date du '" . $data['STARTDATEPHEMERIDE'] . "' englobe une autre éphéméride");
              
              break;
            default:
            $this->flashMessenger()->addErrorMessage("Erreur dans la sauvegarde de l'éphéméride du  '" . $data['STARTDATEPHEMERIDE'] . "'");
          }
        }
      } else {
        
        // Add a flash message Error
        $this->flashMessenger()->addMessage("Des données dans le formulaire sont erronées", 'error', 0);
      }
    } 
    
    return new ViewModel([
      'form' => $form,
      'anneesScolaires' => $anneesScolaires       
    ]);   
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

    $nomEphemeride = $ephemeride->getNomEphemeride();
    
    // Delete ephemeride.
    $this->ephemerideManager->deleteEphemeride($id);

    // Add a flash message.
    $this->flashMessenger()->addWarningMessage("L'éphéméride '$nomEphemeride' a été supprimée");

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

    if (null == $ephemeride) {
      $this->getResponse()->setStatusCode(404);
      return;
    }

    // Get the list of all available chauffeur (sorted)
    $anneesScolaires = $this->ephemerideManager->getAnneesScolaires();
    
    // Create ephemeride Form
    $form = new EphemerideForm($anneesScolaires);
    
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
        $result = $this->ephemerideManager->updateEphemeride($ephemeride, $data);
        if ($result instanceof Ephemeride) {
				
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage("L'éphéméride '" . $data['NOMEPHEMERIDE'] . "' a été modifiée");
        } else {
              
          // Add a flash message Error
          switch($result){
            
            case 1:
              $this->flashMessenger()->addErrorMessage("L'éphéméride en date du '" . $data['STARTDATEPHEMERIDE'] . "' existe déjà");
              break;
            
            case 2:
              $this->flashMessenger()->addErrorMessage("La date de fin de l'éphéméride du '" . $data['STARTDATEPHEMERIDE'] . "' est déjà incluse dans une autre éphéméride");
              break;
            
            case 3:
              $this->flashMessenger()->addErrorMessage("La date de début de l'éphéméride du '" . $data['STARTDATEPHEMERIDE'] . "' est déjà incluse dans une autre éphéméride");
              
              break;
            
            case 4:
              $this->flashMessenger()->addErrorMessage("L'éphéméride en date du '" . $data['STARTDATEPHEMERIDE'] . "' englobe une autre éphéméride");
              
              break;
            default:
            $this->flashMessenger()->addErrorMessage("Erreur dans la sauvegarde de l'éphéméride du  '" . $data['STARTDATEPHEMERIDE'] . "'");
          }
        }

        // Redirect to "index" page
        return $this->redirect()->toRoute('ephemeride', ['action'=>'index']);
      }               
    } else {
		
      $form->setData($ephemeride->getArrayCopy());
    }

    return new ViewModel([
      'form'            => $form,
      'anneesScolaires' => $anneesScolaires 
    ]);
  }
}