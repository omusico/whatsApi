<?php

namespace Application\Model;


use MyEvents\MyEvents;
use Common\Entity\ConversasAtendimento;
use Doctrine\ORM\EntityManager;
use Common\Entity\Logs;
use Common\Entity\Atendimentos;
use Common\Entity\Usuarios;
use Common\Entity\ObservacoesAtendimentos;
class ManagerWhatsModel{
    
    /**
     * @var \WhatsProt
     * */
    public $managerWhats;
    /**
     * @param string $nickname
     * */
    public $password;
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
    public $users;
    
    public function __construct(EntityManager $entityManager,Usuarios $users = null,$debug = false){
        $this->entityManager    = $entityManager;
        $this->users            = $users;
        $this->setWhatsProt($this->users->getNmWhatsapp(),'pv-whats',$debug);
        $this->events           = new \MyEvents($this->managerWhats);
    }
    
    public function setWhatsProt($username = null ,$nickname = null ,$debug = false){
        if(!empty($username) && !empty($nickname)){
            $this->managerWhats = new \WhatsProt($username, $nickname,$debug);
        }else{
            $this->managerWhats = null;
        }
        return $this->managerWhats;
    }
    
    public function connectPassword(){
        $this->managerWhats->connect();
        $this->managerWhats->loginWithPassword($this->users->getSenhaWhatsap());
        return $this->managerWhats;
    }
    
    public function requestRegister($codeRegister,$codeRequest = 'sms'){
        $this->managerWhats->codeRequest($codeRequest);
        $result = $this->managerWhats->codeRegister($codeRegister);
        
        $result['login']    = "Seu usuário é: ".$result->login;
        $result['password'] =  "Sua senha é: ".$result->pw;
        
        return $result;
    }
    
    
    public function sendMessage($to, $message, $files=null){
        
        if(!empty($files))
            $return['midias'] = $this->sendFiles($to, $files);
        
        if(!empty($message)){
            $return['message'] = $this->managerWhats->sendMessage($to, $message);
            $this->storageMessage($to, $this->users->getNmWhatsapp(), $message,true);
           
            
            if(empty($return['message']) || !isset($return))
                $this->setLogTalk('Enviar Mensagem:'.$to, "Não foi possível enviar a mensagem para o cliente");
        }        
        return $return;
    }
    
    public function sendObs($obsText,$idCall){
        $call = $this->entityManager->getRepository('Common\Entity\Atendimentos')->findOneBy(array('idAtendimentos' => $idCall));
        
        $obs = new ObservacoesAtendimentos();
        $obs->setIdAtendimentoObservacao($call);
        $obs->setObservacoes($obsText);
        $obs->setData(new \DateTime('now'));
        try{
            $this->entityManager->persist($obs);
            $this->entityManager->flush();
        }catch (\Exception $e){
            $this->setLogTalk("Gravar Observação", $e->getMessage());
            return false;
        }
        
        return $obs;        
    }
    
    public function getMessages()
    {
        $result = array();
        while ($this->managerWhats->pollMessage()){
            $data = $this->managerWhats->getMessages();
           foreach ($data as $message) {
                $userSendMessage = $message->getAttribute('from');
                list($number, $whatsAppUrl) = explode('@',$userSendMessage);
                $result[$userSendMessage]['userSend'] = $message->getAttribute('notify');
                
                if($message->getChild('media')){
                    $mess = $message->getChild("media");
                    //$mess = $message->getChild("enc");
                    $result[$userSendMessage]['messages'][] = $mess->getAttribute('url');
                    $this->storageMessage($this->users->getNmWhatsapp(),$number, $mess->getAttribute('url'),false,true,$message->getAttribute('notify'));
                }else{
                    $mess = $message->getChild("body");
                    //$mess = $message->getChild("enc");
                    $result[$userSendMessage]['messages'][] = $mess->getData();
                    $this->storageMessage($this->users->getNmWhatsapp(),$number, $mess->getData(),false,false,$message->getAttribute('notify'));
                }
            }
        }
    
        return $result;
    }
    
    public function storageMessage($to, $from, $message , $isOperator = false, $isImage = false,$nameContact = false){
    
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('atendimento')
        ->from('Common\Entity\Atendimentos','atendimento')
        ->where('(atendimento.nmrContato = :to OR atendimento.nmrContato = :from) AND atendimento.idStatusAtendimentos = 5')
        ->setParameters(array('to' => $to, 'from' => $from));
    
        $serviceClient = $qb->getQuery()->getArrayResult();
    
        if(empty($serviceClient)){
            $date               = date('Ymdhis');
            $protocolService    = md5($from.$date);
            $serviceClient      = $this->storageServiceClient($protocolService,$from,$nameContact);
        }else{
            $serviceClient      = $this->entityManager->getRepository('Common\Entity\Atendimentos')->findOneBy(array('idAtendimentos'=>$serviceClient[0]['idAtendimentos']));
        }
         
        if($serviceClient){
            $talk = new ConversasAtendimento();
            $talk->setDataConversaAtendimento(new \DateTime('now'));
            $talk->setNmrEnviado($from);
            $talk->setNmrRecebido($to);
            $talk->setMensagem($message);
            $talk->setIdAtendimentoConversas($serviceClient);
        
            if($isOperator)
                $talk->setIdStatusConversas($this->entityManager->getRepository('Common\Entity\StatusConversas')->findOneBy(array('idStatusConversas'=> 3)));
            else
                $talk->setIdStatusConversas($this->entityManager->getRepository('Common\Entity\StatusConversas')->findOneBy(array('idStatusConversas'=> 4)));
            
            if($isImage){
                $talk->setImagem(1);
            }else{
                $talk->setImagem(0);
            }
             
            if($nameContact){
                $talk->setNomeContato($nameContact);
            }
            
            try{
                $this->entityManager->persist($talk);
                $this->entityManager->flush();
            }catch(\Exception $e){
               $this->setLogTalk('Gravar conversa:'.$from, $e->getMessage());
               return false;
            }
            return true;
        }else{
            return false;
        }
    }
    
    public function storageServiceClient($protocolService,  $nmrContato,$nameContact = false){
        
        $serviceClient = new Atendimentos();
        $serviceClient->setIdStatusAtendimentos($this->entityManager->getRepository('Common\Entity\StatusAtendimentos')->findOneBy(array('idStatusAtendimentos' => 5)));
        $serviceClient->setIdUsuarioAtendimento($this->users);
        $serviceClient->setProtocoloAtendimento($protocolService);
        $serviceClient->setDataAtendimento(new \DateTime('now'));
        $serviceClient->setNmrContato($nmrContato);
        
        if($nameContact){
            $serviceClient->setNomeContato($nameContact);
        }
        try{
         		$this->entityManager->persist($serviceClient);
        		$this->entityManager->flush();
        }catch(\Exception $e){
           $this->setLogTalk("Criação novo atendimento", $e->getMessage());
         	  return false;
        }
        
        return $serviceClient;
    }
    
      
    public function setLogTalk($ação,$erro){
        if (!$this->entityManager->isOpen()) {
            $this->entityManager = $this->entityManager->create(
                $this->entityManager->getConnection(),
                $this->entityManager->getConfiguration()
            );
        }
        
        $log = new Logs();
        $log->setDataLogs(new \DateTime('now'));
        $log->setDescricaoLogs($erro);
        $log->setDescricaoAcaoLogs($ação);
        
        try{
            $this->entityManager->persist($log);
            $this->entityManager->flush();
        }catch(\Exception $e){
           throw new \Exception("Não foi possível guardar as mensagens recebidas:".$e->getMessage());
       }
        
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

