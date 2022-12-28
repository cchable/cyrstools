<?php
/**
 * This is the Marque Controller. 
 * 
 * @package   module/Transport/src/Controller/MarqueController.php
 * @version   1.0
 * @copyright 2018-22 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\Controller\Plugin\FlashMessenger;

use Transport\Service\MarqueManager;

use Transport\Model\Marque;
use Transport\Model\MarqueTable;

use Transport\Form\MarqueForm;
use Transport\Form\SearchForm;


/*
 * 
 */
class MarqueController extends AbstractActionController
{
  
  /*
   * Marque table manager
   * @var Transport\Model\MarqueTable
   */
  private $marqueTable; 
  
  /*
   * Marque manager
   * @var Transport\Service\MarqueManager
   */
  private $marqueManager;

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
    MarqueTable   $marqueTable,
    MarqueManager $marqueManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
  {
    
    $this->marqueTable   = $marqueTable;
    $this->marqueManager = $marqueManager;
    $this->defaultRowPerPage    = $defaultRowPerPage;
    $this->stepRowPerPage       = $stepRowPerPage;
    $this->sessionContainer     = $sessionContainer;
  }

  /*
   * This is the default "index" action of the controller. It displays the 
   * list of marque.
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
      'formSearch'      => $formSearch,
      'module'          => 'marque',
      'search'          => $search,
      'rowPerPage'      => $rowPerPage,
      'stepRowPerPage'  => $this->stepRowPerPage,
      'marques'         => $this->marqueTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]);   
  }
   
  /*
   * This action displays a page allowing to add a new marque.
   */
  public function addAction()
  {
    
    // Create Form
    $form = new MarqueForm('create');

    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {
      
      // Fill in the form with POST data
      $data = $this->params()->fromPost();
      $form->setData($data);

      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();  
        
        // Add Marque
        if ($this->marqueManager->addMarque($data)) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage("La marque '" . $data['NOMMARQUE'] . "' a été ajoutée");
          // Redirect to "index" page
          return $this->redirect()->toRoute('marque', ['action'=>'index']); 
        } else {
          
          // Add a flash message Error
          $this->flashMessenger()->addMessage("La marque '" . $data['NOMMARQUE'] . "' existe déjà", 'error', 0);
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
   * This action delete an marque
   */
  public function deleteAction()
  {
	
    $id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
		
      $this->getResponse()->setStatusCode(404);
      return;
    }

    $marque = $this->marqueTable->findOneById($id);
    if ($marque == null) {
  
			$this->getResponse()->setStatusCode(404);
      return;
    }

    $marque = $marque->getMarque();
    
    // Delete marque.
    $this->marqueManager->deleteMarque($id);

    // Add a flash message.
    $this->flashMessenger()->addWarningMessage("L'marque $marque a été supprimée");

    // Redirect to "index" page
    return $this->redirect()->toRoute('marque', ['action'=>'index']);      
  }
  
  /*
   * This action displays a page allowing to edit an existing marque
   */
  public function editAction()
  {
    
		$id = (int)$this->params()->fromRoute('id', -1);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
    if ($id<1) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $marque = $this->marqueTable->findOneById($id);

    if ($marque == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    // Create marque form
    $form = new MarqueForm('update');
    
     // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {

      // Fill in the form with POST data
      $data = $this->params()->fromPost();            
      $form->setData($data);
    
      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Update marque
        if ($this->marqueManager->updateMarque($marque, $data)) {
				
          // Add a flash message Suucess
          $this->flashMessenger()->addSuccessMessage("L'année scolaire a été modifiée");
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("L'marque " . $data['ANNEEANNEESCOLAIRE'] . " existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('marque', ['action'=>'index']);
      }               
    } else {
		
      $form->setData($marque->getArrayCopy());
    }

    return new ViewModel([
      'form' => $form,
    ]);
  }
}