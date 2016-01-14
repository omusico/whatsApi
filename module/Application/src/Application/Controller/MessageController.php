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

class MessageController extends AbstractActionController
{
   	
	public function sendMessageAjaxAction(){
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
                $result = $whatsManagerModel->sendMessage($data['to'], $data['message']) ;
            }
           
           $viewModel = new ViewModel();
           $viewModel->setTerminal(true)->setVariables(array('result' => $result));
           return $viewModel;
	}
	

}
