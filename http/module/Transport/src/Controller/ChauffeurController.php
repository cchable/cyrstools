<?php
/**
 * @package   : module/Transport/src/Controller/ChauffeurController.php
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

use Transport\Service\ChauffeurManager;

use Transport\Model\Chauffeur;
use Transport\Model\ChauffeurTable;

use Transport\Form\ChauffeurForm;
use Transport\Form\SearchForm;


/*
 * 
 */
class ChauffeurController extends AbstractActionController
{
  
  /*
   * Chauffeur table manager
   * @var Transport\Model\ChauffeurTable
   */
  private $chauffeurTable; 
  
  /*
   * Chauffeur manager
   * @var Transport\Service\ChauffeurManager
   */
  private $chauffeurManager;

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
    ChauffeurTable   $chauffeurTable,
    ChauffeurManager $chauffeurManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
  {
    
    $this->chauffeurTable     = $chauffeurTable;
    $this->chauffeurManager   = $chauffeurManager;
    $this->defaultRowPerPage  = $defaultRowPerPage;
    $this->stepRowPerPage     = $stepRowPerPage;
    $this->sessionContainer   = $sessionContainer;
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
      'formSearch'     => $formSearch,
      'module'         => 'chauffeur',
      'search'         => $search,
      'rowPerPage'     => $rowPerPage,
      'stepRowPerPage' => $this->stepRowPerPage,
      'chauffeurs'     => $this->chauffeurTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]);   
  }
   
  /*
   * This action displays a page allowing to add a new chauffeur.
   */
  public function addAction()
  {
    
    // Create Form
    $form = new ChauffeurForm('create');

    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {
      
      // Fill in the form with POST data
      $data = $this->params()->fromPost();
      $form->setData($data);

      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();   
        
        // Add chauffeur
        if ($this->chauffeurManager->addChauffeur($data)) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage('Le chauffeur ' . $data['PRENOMCHAUFFEUR'] . ' a été ajouté');
          // Redirect to "index" page
          return $this->redirect()->toRoute('chauffeur', ['action'=>'index']); 
        } else {
          
          // Add a flash message Error
          $this->flashMessenger()->addMessage("Le chauffeur " . $data['PRENOMCHAUFFEUR'] . " existe déjà", 'error', 0);
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
  
  /*
   * This action delete an chauffeur
   */
  public function deleteAction()
  {
	
    $id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
		
      $this->getResponse()->setStatusCode(404);
      return;
    }

    $chauffeur = $this->chauffeurTable->findOneById($id);
    if ($chauffeur == null) {
  
			$this->getResponse()->setStatusCode(404);
      return;
    }

    $prenom = $chauffeur->getPrenom();
    
    // Delete chauffeur.
    $this->chauffeurManager->deleteChauffeur($id);

    // Add a flash message.
    $this->flashMessenger()->addWarningMessage("Le chauffeur $prenom a été supprimé");

    // Redirect to "index" page
    return $this->redirect()->toRoute('chauffeur', ['action'=>'index']);      
  }
  
  /*
   * This action displays a page allowing to edit an existing chauffeur
   */
  public function editAction()
  {
    
		$id = (int)$this->params()->fromRoute('id', -1);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
    if ($id<1) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $chauffeur = $this->chauffeurTable->findOneById($id);

    if ($chauffeur == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    // Create chauffeur form
    $form = new ChauffeurForm('update');
    
     // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {

      // Fill in the form with POST data
      $data = $this->params()->fromPost();            
      $form->setData($data);
    
      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Update chauffeur
        if ($this->chauffeurManager->updateChauffeur($chauffeur, $data)) {
				
          $prenom = $chauffeur->getPrenom();
          // Add a flash message Suucess
          $this->flashMessenger()->addSuccessMessage("Chauffeur $prenom modifié");
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Le chauffeur " . $data['PRENOMCHAUFFEUR'] . " existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('chauffeur', ['action'=>'index']);
      }               
    } else {
		
      $form->setData($chauffeur->getArrayCopy());
    }

    return new ViewModel([
      'form' => $form,
    ]);
  }
}