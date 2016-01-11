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
        
        $username = '5511941872988';                      // Telephone number including the country code without '+' or '00'.
        $nickname = 'Pagina Viva';
        
        $w = new WhatsProt($username, $nickname, $debug);
        
        //$w->codeRequest('sms');


        //$result = $w->codeRegister('944252');
        //echo "\nYour username is: ".$result->login."\n";
        //echo "Your password is: ".$result->pw."\n";


        $password = "UBeg+m8T6Iqusp+X9+6DBcvYdno=";     // Use registerTool.php or exampleRegister.php to obtain your password
        $target = "5511970560486";                   // Destination telephone number including the country code without '+' or '00'.

        $w->connect();
        $w->loginWithPassword($password);
		$result = array();
        //echo $w->sendMessage($target, "Fala aí seu pela saco");


        while ($w->pollMessage()){
            $data = $w->getMessages();
            foreach ($data as $message) {
               $mess = $message->getChild("body");
               $userSendMessage = $message->getAttribute('from');
               $result[$userSendMessage]['userSend'] = $message->getAttribute('notify');
               $result[$userSendMessage]['messages'][] = $mess->getData();
			}
        }
        return new ViewModel(array('result' => $result));
	}
	
	public function sendMessageAction(){
            $request  = $this->getRequest();
            $debug = false;
            $username = '5511941872988';                      // Telephone number including the country code without '+' or '00'.
            $nickname = 'Pagina Viva';
            $password = "UBeg+m8T6Iqusp+X9+6DBcvYdno=";     // Use registerTool.php or exampleRegister.php to obtain your password
            
            $whatsManagerModel = new ManagerWhatsModel($username,$nickname);
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
		$debug   = false;
		$result  = false;
		$username = null;
		$nickname = null;
		if($request->isPost()){
			$data = $request->getPost();
			$username = $data['number'];                      // Telephone number including the country code without '+' or '00'.
			$nickname = $data['nickname'];
			
			$w = new WhatsProt($username, $nickname, $debug);
			try{
				$w->codeRequest('sms');
				$result = true;
			}catch(\Exception $e){
				$result = false;
			}
		}
		
		return new ViewModel(array('result' => $result,'username' => $username, 'nickname' => $nickname));
		
	}
	
	public function configureNumberAction(){
		$username 	= $this->params()->fromQuery('username',null);
		$nickname 	= $this->params()->fromQuery('nickname',null);
		$request  	= $this->getRequest();
		$debug 		= false;
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
