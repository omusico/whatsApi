<?php
namespace Application\Model;

use MyEvents\MyEvents;
use Common\Entity\ConversasAtendimento;
use Doctrine\ORM\EntityManager;
use Common\Entity\Logs;
use Common\Entity\Atendimentos;
use Common\Entity\Usuarios;

class MessageModel extends ManagerWhatsModel
{

    /**
     *
     * @param  EntityManager
     *            
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager, Usuarios $users, $debug = false)
    {
        $this->entityManager = $entityManager;
        parent::__construct($this->entityManager, $users, $debug);
    }

    public function loadMessagesReceived()
    {   
        $this->aux = $this->users;
        
        $this->users = $this->entityManager->getRepository('Common\Entity\Usuarios')->findOneBy(array('nomeUsuario'=>'administrator'));
        $this->connectPassword();
        while ($this->managerWhats->pollMessage()) {
            $data = $this->managerWhats->getMessages();
            foreach ($data as $message) {
                $userSendMessage = $message->getAttribute('from');
                list ($number, $whatsAppUrl) = explode('@', $userSendMessage);
                
                if ($message->getChild('media')) {
                    $mess = $message->getChild("media");
                    $this->storageMessage($this->users->getNmWhatsapp(),$number, $mess->getAttribute('url'),false,true,$message->getAttribute('notify'));
                    if(!empty($mess->getAttribute('caption')))
                        $this->storageMessage($this->users->getNmWhatsapp(),$number, $mess->getAttribute('caption'),false,false,$message->getAttribute('notify'));
                } else {
                    $mess = $message->getChild("body");
                    $this->storageMessage($this->users->getNmWhatsapp(),$number, $mess->getData(),false,false,$message->getAttribute('notify'));
                }
            }
        }
        $this->users = $this->aux;
        return true;
    }

    public function getMessagesCall($idCall)
    {
        $messagesCalls = $this->entityManager->getRepository('Common\Entity\ConversasAtendimento')->findBy(array('idAtendimentoConversas' => $idCall));
        return $messagesCalls;
    }
    
    public function getMessagesUnreadCall($idCall){
        $call = $this->entityManager->getRepository('Common\Entity\Atendimentos')->findOneBy(array('idAtendimentos'=>$idCall,'idStatusAtendimentos'=>5));
        if(!empty($call)){
            $result['call'] = true;
            $result['messagesCall'] = $this->entityManager->getRepository('Common\Entity\ConversasAtendimento')->findBy(array('idAtendimentoConversas' => $idCall,'idStatusConversas' => 4));
        }else{
            $result['call'] = false;
            $result['messagesCall'] = array();
        }
        return $result;
    }

    public function getCalls()
    {
        $calls = $this->entityManager->getRepository('Common\Entity\Atendimentos')->findBy(array('idStatusAtendimentos' => 5));
        return $calls;
    }

    public function getCall($idCall)
    {  
        $call = $this->entityManager->getRepository('Common\Entity\Atendimentos')->findOneBy(array('idStatusAtendimentos' => 5,'idAtendimentos' => $idCall));
        return $call;
    }
    
    public function getObsCall($idCall){
        $obs = $this->entityManager->getRepository('Common\Entity\ObservacoesAtendimentos')->findBy(array('idAtendimentoObservacao'=> $idCall));
        return $obs;
    }
    
    public function finalizeCall($idCall){
        $call = $this->entityManager->getRepository('Common\Entity\Atendimentos')->findOneBy(array('idAtendimentos' => $idCall));
        
        $call->setIdStatusAtendimentos($this->entityManager->getRepository('Common\Entity\StatusAtendimentos')->findOneBy(array('idStatusAtendimentos' => 7)));
        try{
            $this->entityManager->persist($call);
            $this->entityManager->flush();
        }catch(\Exception $e){
            $this->setLogTalk("Finalizar Atendimento", $e->getMessage());
            return false;
        }
        
        return true ;
    }
    
    public function setReadMessages($idCall){
        
        $qb = $this->entityManager->createQueryBuilder();
        $qb->update('Common\Entity\ConversasAtendimento','conversasAtendimento')
            ->set('conversasAtendimento.idStatusConversas',3)
            ->where('conversasAtendimento.idAtendimentoConversas = :idCall')
            ->setParameters(array('idCall' => $idCall));
        
        try{
            return $qb->getQuery()->getResult();
        }catch(\Exception $e){
            $this->setLogTalk("Update conversas lidas", $e->getMessage());
            return false;
        }
    }
    
    public function getCountMessagesUnreadCalls($idCall){
        $messages = $this->entityManager->getRepository('Common\Entity\ConversasAtendimento')->findBy(array('idAtendimentoConversas' => $idCall,'idStatusConversas'=>4));
        return count($messages);
    }
}

