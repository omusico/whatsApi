<?php

namespace Application\Model;


use MyEvents\MyEvents;
use Common\Entity\ConversasAtendimento;
use Doctrine\ORM\EntityManager;
use Common\Entity\Logs;
use Common\Entity\Atendimentos;
use Common\Entity\Usuarios;
class ManagerWhatsModel{
    
    /**
     * @var \WhatsProt
     * */
    private $managerWhats;
    /**
     * @param string $nickname
     * */
    private $password;
    /**
     * @var MyEvents
     * */
    private $events;
    /**
     * @var EntityManager
     * */
    private $entityManager;
    /**
     * @var Usuarios
     * */
    private $users;
    
    public function __construct($username = null,$nickname = null, $debug = false,EntityManager $entityManager,Usuarios $users = null){
        $this->setWhatsProt($username, $nickname,$debug);
        $this->events           = new \MyEvents($this->managerWhats);
        $this->entityManager    = $entityManager;
        $this->users            = $users;
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
        
        if(!empty($message)){
            $return['message'] = $this->managerWhats->sendMessage($to, $message);
            
            if(empty($return['message']))
                $this->setLogTalk('Enviar Mensagem:'.$to, "Não foi possível enviar a mensagem para o cliente");
        }        
        return $return;
    }
    
    
    
    public function storageServiceClient($protocolService){
        $serviceClient = new Atendimentos();
        $serviceClient->setIdStatusAtendimentos($this->entityManager->getRepository('Common\Entity\StatusAtendimentos')->findOneBy(array('idStatusAtendimentos' => 1)));
        $serviceClient->setIdUsuarioAtendimento($this->users);
        $serviceClient->setProtocoloAtendimento($protocolService);
        $serviceClient->setDataAtendimento(new \DateTime('now'));
        try{
            $this->entityManager->persist($serviceClient);
            $this->entityManager->flush();
        }catch(\Exception $e){
            $this->setLogTalk("Criação novo atendimento", $e->getMessage());
            return false;
        }
        return $serviceClient;
    }
    
    public function storageMessage($from, $contactName, $message , $isOperator = null){
        
        $serviceClient = $this->entityManager->getRepository('Common\Entity\Atendimentos')->findOneBy(array(''));
        if(empty($serviceClient)){
                $date               = date('Ymdhis');
                $protocolService    = md5($from.date);
                $serviceClient      = $this->storageServiceClient($protocolService);
        }
        
        $talk = new ConversasAtendimento();
        $talk->setDate(new \DateTime('now'));
        $talk->setNmrContato($from);
        $talk->setNomeContato($contactName);
        $talk->setMensagem($message);
        $talk->setIdAtendimentoConversas($serviceClient);
        if($isOperator)
            $talk->setStatusMensagemAtendimento($this->entityManager->getRepository('Common\Entity\StatusConversas')->findOneBy(array('idStatusConversa'=> 1)));
        else
            $talk->setStatusMensagemAtendimento($this->entityManager->getRepository('Common\Entity\StatusConversas')->findOneBy(array('idStatusConversa'=> 2)));
        
        try{
            $this->entityManager->persist($talk);
            $this->entityManager->flush();
            
        }catch(\Exception $e){
            $this->setLogTalk('Gravar conversa:'.$from, $e->getMessage());
            return false;
        }
        
        return true;
        
    }
        
    public function setLogTalk($ação,$erro){
        $log = new Logs();
        $log->setDate(new \DateTime('now'));
        $log->setDescricaoLogs($erro);
        $log->setDescricaoAcaoLogs($ação);
        
        try{
            $this->entityManager->persist($log);
            $this->entityManager->flush();
        }catch(\Exception $e){
            throw new \Exception("Não foi possível guardar as mensagens recebidas");
        }
        
    }
    
    /**
     * return array();
     * **/
    public function getMessages()
    {   
        $result = array();
        while ($this->managerWhats->pollMessage()){
                $data = $this->managerWhats->getMessages();
                foreach ($data as $message) {
                   $mess = $message->getChild("body");
                   $userSendMessage = $message->getAttribute('from');
                   $result[$userSendMessage]['userSend'] = $message->getAttribute('notify');
                   $result[$userSendMessage]['messages'][] = $mess->getData();
                   
                   $this->storageMessage($userSendMessage, $message->getAttribute('notify'), $mess->getData());
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
                    $this->setLogTalk("Enviar image:".$to, "Não foi possível enviar a mídia");
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
                   $this->setLogTalk("Enviar image:".$to, "Não foi possível enviar a mídia");
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
                    $this->setLogTalk("Enviar image:".$to, "Não foi possível enviar a mídia");
                }
            }
            return true;
        }
    
}

