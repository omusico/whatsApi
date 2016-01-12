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
       return new ViewModel();
    }
	
	
	public function getMessagesAction(){
		
        $debug = false;
        $result   = array();
        $username = '5511941872988';                      // Telephone number including the country code without '+' or '00'.
        $nickname = 'pvwhats';
        $password = "OfDOGOZe4+fmIe3RL+24iprtQk0=";     // Use registerTool.php or exampleRegister.php to obtain your password
          
        $whatsManagerModel = new ManagerWhatsModel($username,$nickname,$debug,$this->getEntityManager());
        $whatsManagerModel->connectPassword($password);
        $result = $whatsManagerModel->getMessages();

        
        return new ViewModel(array('result' => $result));
	}
	
	public function sendMessageAction(){
            $request  = $this->getRequest();
            $debug = false;
            $username = '5511941872988';                      // Telephone number including the country code without '+' or '00'.
            $nickname = 'pvwhats';
            $password = "OfDOGOZe4+fmIe3RL+24iprtQk0=";     // Use registerTool.php or exampleRegister.php to obtain your password
            
            $whatsManagerModel = new ManagerWhatsModel($username,$nickname,$debug,$this->getEntityManager());
            $whatsManagerModel->connectPassword($password);
            
            $result   = false;
            if($request->isPost()){ 
                $data   = $request->getPost();
                $files  = $request->getFiles();
                $result = $whatsManagerModel->sendMessage($data['to'], $data['message'], $files) ;
                
            }
            return new ViewModel(array('result' => $result));
	}
	// return bollean
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
			
			$w = new WhatsProt($username, $nickname, $debug);
		    $result = $w->codeRequest('sms');
		}
		
		return new ViewModel(array('result' => $result,'username' => $username, 'nickname' => $nickname));
		
	}
	
	public function configureNumberAction(){
		$username 	= $this->params()->fromQuery('username',null);
		$nickname 	= $this->params()->fromQuery('nickname',null);
		$request  	= $this->getRequest();
		$debug 		= true;
		$result  	= null;
		
		if($request->isPost()){
		    
		    
			$data = $request->getPost();
			
//             $username = $data['username'];  
// 			$nickname = $data['nickname'];
			
			$username = '5511941872988';
			$nickname = 'pvwhats';    
			    
			$codeRegister = $data['code'];
			$w = new WhatsProt($username, $nickname, $debug);
			$result = $w->codeRegister($codeRegister);
        }
		
		return new ViewModel(array('result' => $result,'username' => $username, 'nickname' => $nickname));
	}

}
