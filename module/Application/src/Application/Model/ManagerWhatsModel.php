<?php

namespace Application\Model;


use MyEvents\MyEvents;
class ManagerWhatsModel{
    
     /**
     * .
     *
     * @var \WhatsProt
     */
    private $managerWhats;
    
    /**
     * 
     * 
     * @param string $nickname*/
    private $password;
    /***
     * @var MyEvents
     * */
    private $events;
    
    public function __construct($username = null,$nickname = null){
        $this->setWhatsProt($username, $nickname);
        $this->events = new \MyEvents($this->managerWhats);
    }
    
    public function setWhatsProt($username = null ,$nickname = null ,$debug = false){
        if(!empty($username) && !empty($nickname)){
            $this->managerWhats = new \WhatsProt($username, $nickname,$debug);
        }else{
            $this->managerWhats = null;
        }
        return $this->managerWhats;
    }
    
    public function connectPassword($password){
        $this->managerWhats->connect();
        $this->managerWhats->loginWithPassword($password);
        return $this->managerWhats;
    }
    
    public function requestRegister($codeRegister,$codeRequest = 'sms'){
        $this->managerWhats->codeRequest($codeRequest);
        $result = $this->managerWhats->codeRegister($codeRegister);
        
        $result['login']    = "Seu usuário é: ".$result->login;
        $result['password'] =  "Sua senha é: ".$result->pw;
        
        return $result;
    }
    
    
    public function sendMessage($to, $message,$files){
        
        if(!empty($files))
            $return['midias'] = $this->sendFiles($to, $files);
        
        if(!empty($message))
            $return['message'] = $this->managerWhats->sendMessage($to, $message);
        
        return $return;
    }
    
    
    /**
     * return array();
     * **/
    public function getMessages()
    {   
        $result = array();
        while ($this->managerWhats->pollMessage()){
                $data = $w->getMessages();
                foreach ($data as $message) {
                   $mess = $message->getChild("body");
                   $userSendMessage = $message->getAttribute('from');
                   $result[$userSendMessage]['userSend'] = $message->getAttribute('notify');
                   $result[$userSendMessage]['messages'][] = $mess->getData();
    			}
            }
            
         return $result;
    }
    
    protected function sendFiles($to,$files){
            
            set_time_limit(600);
            $explode = explode('.', $files['image']['name']);
            $extensao = end($explode);
            $extensao = strtolower($extensao);
            
            define('CAMINHO_MIDIA', 'c:/xampp5-6/htdocs/whatsApi/public/img/send-messages/');
                        
            if(in_array($extensao, array('3gp', 'mp4', 'mov', 'avi'))){
                $newName = md5($files['image']['name'].date('Ymdhis')).".".$extensao;
                $pathImage = CAMINHO_MIDIA.$newName;
                $urlMain = $_SERVER['HTTP_ORIGIN'].'/img/send-messages/'.$newName;
        
                if (move_uploaded_file($files['image']['tmp_name'], $pathImage)) {
                    $this->managerWhats->sendMessageVideo($to,$urlMain);
                    $this->managerWhats->pollMessage();
                } else {
                    echo "Possível ataque de upload de arquivo!\n";
                }
            }
        
            if(in_array($extensao, array('jpg', 'jpeg', 'gif', 'png'))){
                $newName = md5($files['image']['name'].date('Ymdhis')).".".$extensao;
                $pathImage = CAMINHO_MIDIA.$newName;
                $urlMain = $_SERVER['HTTP_ORIGIN'].'/img/send-messages/'.$newName;
                
                if (move_uploaded_file($files['image']['tmp_name'], $pathImage)) {
                    $this->managerWhats->sendMessageImage($to,$urlMain);
                    $this->managerWhats->pollMessage();
                } else {
                    echo "Possível ataque de upload de arquivo!\n";
                }
            }
        
            if(in_array($extensao, array('3gp', 'caf', 'wav', 'mp3', 'wma', 'ogg', 'aif', 'aac', 'm4a'))){
        
                $newName = md5($_FILES['image']['name'].date('Ymdhis')).".".$extensao;
                $pathImage = CAMINHO_MIDIA.$newName;
                $urlMain = $_SERVER['HTTP_ORIGIN'].'/img/send-messages/'.$newName;
                
                if (move_uploaded_file($files['image']['tmp_name'], $pathImage)) {
                    $this->managerWhats->sendMessageAudio($to,$urlMain);
                    $this->managerWhats->pollMessage();
                } else {
                    echo "Possível ataque de upload de arquivo!\n";
                }
            }
            return true;
        }
    
}

