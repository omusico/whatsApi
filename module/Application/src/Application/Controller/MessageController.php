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
use Application\Model\CallsModel;

class MessageController extends AbstractActionController
{
   	public function indexAction(){
   	    return new ViewModel();
   	}
   	
   	/** SEND MESSAGE AND STORAGE **/
	public function sendMessageAjaxAction(){
            $request  = $this->getRequest();
            $debug = false;
            $messageModel = new MessageModel($this->getEntityManager(),$this->identity(),$debug);
            $messageModel->connectPassword();
            $messageModel->deleteFilesDB();
            $result   = false;
            if($request->isPost()){ 
                $data   = $request->getPost();
				$result = $messageModel->sendMessage($data['to'], $data['message']) ;
            }
            $messageModel->deleteFilesDB();
           $viewModel = new ViewModel();
           $viewModel->setTerminal(true)->setVariables(array('result' => $result));
           return $viewModel;
	}
	
	/** STORAGE PS OF THE CALL **/
	public function sendObsAjaxAction(){
	    $request  = $this->getRequest();
	    $debug = false;
	    $callModel = new CallsModel($this->getEntityManager(),$this->identity(),$debug);
	    $callModel->connectPassword();
	
	    $result   = false;
	    if($request->isPost()){
	        $data   = $request->getPost();
	        $result = $callModel->sendObs($data['obs'], $data['idCall']) ;
	    }
	     
	    $viewModel = new ViewModel();
	    $viewModel->setTerminal(true)->setVariables(array('result' => $result));
	    return $viewModel;
	}
	
	/** LOAD MESSAGES **/
	public function messagesAjaxAction(){
	    $request   = $this->getRequest();
	    $debug     = false;
	    $messageModel = new MessageModel($this->getEntityManager(),$this->identity(),$debug);
	    $messageModel->connectPassword();
	    
	    $result   = false;
	    if($request->isPost()){
	        $data   = $request->getPost();
	        $result = $messageModel->getMessages();
	    }
	     
	    $viewModel = new ViewModel();
	    $viewModel->setTerminal(true)->setVariables(array('result' => $result));
	    return $viewModel;
	    
	}
	
	/** GET MESSAGES SPECIFIC CALL **/
	public function messagesCallAjaxAction(){
	    $request      = $this->getRequest();
	    $messages     = null;
	    $messagesOld  = null;
	    $debug        = false;
	    $messageModel = new MessageModel($this->getEntityManager(),$this->identity(),$debug);
	    
	    if($request->isPost()){
	        $data      = $request->getPost();
	        $messages  = $messageModel->getMessagesCall($data['idCall']);
	        $messagesOld = $messageModel->getAllMessagesClient($data['number']);  
	        $messageModel->setReadMessages($data['idCall']);
	    }
	    $messageModel->deleteFilesDB();
	    $viewModel = new ViewModel();
	    $viewModel->setTerminal(true)->setVariables(array('messages' => $messages,'messagesOld'=> $messagesOld));
	    return $viewModel;
	}
	
    /** SHOW MESSAGES UNREAD **/
	public function messagesUnreadCallAjaxAction(){
	    $request   = $this->getRequest();
	    $result    = false;
	    $debug     = false;
	    $messageModel = new MessageModel($this->getEntityManager(),$this->identity(),$debug);
	     
	    if($request->isPost()){
	        $data      = $request->getPost();
	        $result  = $messageModel->getMessagesUnreadCall($data['idCall']);
	        $messageModel->setReadMessages($data['idCall']);
	    }
	     $messageModel->deleteFilesDB();
	    $viewModel = new ViewModel();
	    $viewModel->setTerminal(true)->setVariables(array('result' => $result));
	    return $viewModel;
	}
	
	
	public function getCallsAjaxAction(){
	    $callModel = new CallsModel($this->getEntityManager(), $this->identity());
	    $calls     = $callModel->getCalls();
	    
	    $viewModel = new ViewModel();
	    $viewModel->setTerminal(true)->setVariables(array('calls'=> $calls));
	    return $viewModel;
	}
	
	/** LOAD MESSAGES **/
	public function loadMessagesAjaxAction(){
	    $messageModel = new MessageModel($this->getEntityManager(), $this->identity());
	    $return = $messageModel->loadMessagesReceived();
	    
	    return new JsonModel(array('result' => $return));
	}
	
	/** GET NUMBER PROTOCOL AJAX **/
	public function getProtocolCallAjaxAction(){
	    $request   = $this->getRequest();
	    $result    = null;
	    if($request->isPost()){
	        $data          = $request->getPost();
	        $callModel  = new CallsModel($this->getEntityManager(), $this->identity());
	        $call          = $callModel->getCall($data['idCall']);
	         
	        if(!empty($call))
	            $result    = $call->getProtocoloAtendimento();
	    }
	    return new JsonModel(array('result' => $result));
	}
	
	/** GET OBSERVATIONS OF THE SPECIFIC CALL  **/
	public function getObsCallAjaxAction(){
        $request    = $this->getRequest();
        $obsCall    = null;
        if($request->isPost()){
            $data           = $request->getPost();
            $callModel      = new CallsModel($this->getEntityManager(),$this->identity());
            $obsCall        = $callModel->getObsCall($data['idCall']);
        }
	    $viewModel = new ViewModel();
	    $viewModel->setTerminal(true)->setVariables(array('obsCall'=>$obsCall));
	    return $viewModel;
	}
	
	/** SET STATUS PENDING **/
	public function pendingCallAjaxAction(){
	    $request    = $this->getRequest();
	    $result     = false;
	    if($request->isPost()){
	        $data          = $request->getPost();
	        $callsModel    = new CallsModel($this->getEntityManager(),$this->identity());
	        $callsModel->setCall($data['idCall']);
	        $result        = $callsModel->setPendingCall();
	    }
	    return new JsonModel(array('result' => $result));
	}
	
	/** FINALIZE CALL **/
	public function finalizeCallAjaxAction(){
	    $request    = $this->getRequest();
	    $result     = false;
	    if($request->isPost()){
	        $data         = $request->getPost();
	        $callsModel   = new CallsModel($this->getEntityManager(),$this->identity());
	        $result       = $callsModel->finalizeCall($data['idCall']);
	    }
	    return new JsonModel(array('result' => $result));
	}
}
