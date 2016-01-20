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
use Zend\View\Model\JsonModel;
use Application\Model\ManagerWhatsModel;
use Application\Model\MessageModel;
use WhatsProt;

class MessageController extends AbstractActionController
{
   	public function indexAction(){
   	    return new ViewModel();
   	}
	public function sendMessageAjaxAction(){
            $request  = $this->getRequest();
            $debug = false;
            $whatsManagerModel = new ManagerWhatsModel($this->getEntityManager(),$this->identity(),$debug);
            $whatsManagerModel->connectPassword();
            
            $result   = false;
            if($request->isPost()){ 
                $data   = $request->getPost();
				$result = $whatsManagerModel->sendMessage($data['to'], $data['message']) ;
            }
           
           $viewModel = new ViewModel();
           $viewModel->setTerminal(true)->setVariables(array('result' => $result));
           return $viewModel;
	}
	
	public function sendObsAjaxAction(){
	    $request  = $this->getRequest();
	    $debug = false;
	    $whatsManagerModel = new ManagerWhatsModel($this->getEntityManager(),$this->identity(),$debug);
	    $whatsManagerModel->connectPassword();
	
	    $result   = false;
	    if($request->isPost()){
	        $data   = $request->getPost();
	        $result = $whatsManagerModel->sendObs($data['obs'], $data['idCall']) ;
	    }
	     
	    $viewModel = new ViewModel();
	    $viewModel->setTerminal(true)->setVariables(array('result' => $result));
	    return $viewModel;
	}
	
	public function messagesAjaxAction(){
	    $request   = $this->getRequest();
	    $debug     = false;
	    $whatsManagerModel = new ManagerWhatsModel($this->getEntityManager(),$this->identity(),$debug);
	    $whatsManagerModel->connectPassword();
	    
	    $result   = false;
	    if($request->isPost()){
	        $data   = $request->getPost();
	        $result = $whatsManagerModel->getMessages() ;
	    }
	     
	    $viewModel = new ViewModel();
	    $viewModel->setTerminal(true)->setVariables(array('result' => $result));
	    return $viewModel;
	    
	}
	
	public function messagesCallAjaxAction(){
	    $request   = $this->getRequest();
	    $result    = false;
	    $debug     = false;
	    $messageModel = new MessageModel($this->getEntityManager(),$this->identity(),$debug);
	    
	    if($request->isPost()){
	        $data      = $request->getPost();
	        $messages  = $messageModel->getMessagesCall($data['idCall']);
	        $messageModel->setReadMessages($data['idCall']);
	        
	    }
	    
	    $viewModel = new ViewModel();
	    $viewModel->setTerminal(true)->setVariables(array('messages' => $messages));
	    return $viewModel;
	}
	

	public function messagesUnreadCallAjaxAction(){
	    $request   = $this->getRequest();
	    $result    = false;
	    $debug     = false;
	    $messageModel = new MessageModel($this->getEntityManager(),$this->identity(),$debug);
	     
	    if($request->isPost()){
	        $data      = $request->getPost();
	        $messages  = $messageModel->getMessagesUnreadCall($data['idCall']);
	        $messageModel->setReadMessages($data['idCall']);
	         
	    }
	     
	    $viewModel = new ViewModel();
	    $viewModel->setTerminal(true)->setVariables(array('messages' => $messages));
	    return $viewModel;
	}
	
	
	public function getCallsAjaxAction(){
	    $messageModel = new MessageModel($this->getEntityManager(), $this->identity());
	    $calls = $messageModel->getCalls();
	    
	    $viewModel = new ViewModel();
	    $viewModel->setTerminal(true)->setVariables(array('calls'=> $calls));
	    return $viewModel;
	}
	
	public function loadMessagesAjaxAction(){
	    $messageModel = new MessageModel($this->getEntityManager(), $this->identity());
	    $return = $messageModel->loadMessagesReceived();
	    
	    return new JsonModel(array('result' => $return));
	}
	
	public function getProtocolCallAjaxAction(){
	    $request   = $this->getRequest();
	    $result    = null;
	    if($request->isPost()){
	        $data          = $request->getPost();
	        $messageModel  = new MessageModel($this->getEntityManager(), $this->identity());
	        $call          = $messageModel->getCall($data['idCall']);
	         
	        if(!empty($call))
	            $result    = $call->getProtocoloAtendimento();
	    }
	    return new JsonModel(array('result' => $result));
	}
	
	public function getObsCallAjaxAction(){
        $request    = $this->getRequest();
        $obsCall    = null;
        if($request->isPost()){
            $data           = $request->getPost();
            $messageModel   = new MessageModel($this->getEntityManager(),$this->identity());
            $obsCall        = $messageModel->getObsCall($data['idCall']);
        }
	    $viewModel = new ViewModel();
	    $viewModel->setTerminal(true)->setVariables(array('obsCall'=>$obsCall));
	    return $viewModel;
	}
	
	public function finalizeCallAjaxAction(){
	    $request    = $this->getRequest();
	    $result     = false;
	    if($request->isPost()){
	        $data           = $request->getPost();
	        $messageModel   = new MessageModel($this->getEntityManager(),$this->identity());
	        $result         = $messageModel->finalizeCall($data['idCall']);
	    }
	    return new JsonModel(array('result' => $result));
	}
}
