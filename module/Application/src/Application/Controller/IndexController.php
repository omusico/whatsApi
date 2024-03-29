<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\ManagerWhatsModel;
use WhatsProt;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {           
        if ($user = $this->identity())
            return $this->redirect()->toRoute('crm');
        
       return new ViewModel();
    }
	
    /** LOGIN **/
    public function loginAction(){
        $request = $this->getRequest();
        if ($request->isPost()) {
                $data = $request->getPost();
                $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        
                $adapter = $authService->getAdapter();
                $adapter->setIdentityValue($data['login']);
                $adapter->setCredentialValue($data['password']);
                $authResult = $authService->authenticate();
        
                if ($authResult->isValid()) {
                    return $this->redirect()->toRoute('crm');
                } else {
                   $this->flashmessenger()->addErrorMessage('Senha ou email inválidos');
                   return $this->redirect()->toRoute('application');
                }
        }
        return new ViewModel();
    }
    /**
     * ROUTER CRM
     * */
    public function getMessagesAction(){
    
        $debug = false;
        $result   = array();
        $whatsManagerModel = new ManagerWhatsModel($this->getEntityManager(),$this->identity(),$debug);
        $whatsManagerModel->connectPassword();
        $result = $whatsManagerModel->getMessages();
    
        return new ViewModel(array('result' => $result));
    }
    
    /**
     * LOGOUT SYSTEM  
     * */
    public function logoutAction()
    {
        $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        $authService->clearIdentity();
    
        $this->flashmessenger()->addSuccessMessage("No momento você não está logado!");
        return $this->redirect()->toRoute('application');
    }
	
	/** REGISTER NUMBER ***/
	public function registerNumberAction(){
		
	    $request = $this->getRequest();
		$debug   = true;
		$result  = false;
		$username = null;
		$nickname = null;
		if($request->isPost()){
			$data = $request->getPost();
			$username = $data['number'];                      // Telephone number including the country code without '+' or '00'.
			$nickname = $data['nickname'];
			
			$register = new \Registration($username, $debug);
		    $result = $register->codeRequest('sms');
		}
		
		return new ViewModel(array('result' => $result,'username' => $username, 'nickname' => $nickname));
		
	}
	
	/** CONFIGURE NUMBER ***/
	public function configureNumberAction(){
		$username 	= $this->params()->fromQuery('username',null);
		$nickname 	= $this->params()->fromQuery('nickname',null);
		$request  	= $this->getRequest();
		$debug 		= true;
		$result  	= null;
		
		if($request->isPost()){
		    
		    
			$data = $request->getPost();
			$username = $data['username'];  
			$nickname = $data['nickname'];
			
			$codeRegister = $data['code'];
			$w = new WhatsProt($username, $nickname, $debug);
			$result = $w->codeRegister($codeRegister);
        }
		
		return new ViewModel(array('result' => $result,'username' => $username, 'nickname' => $nickname));
	}

}
