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
        $call = $this->entityManager->getRepository('Common\Entity\Atendimentos')->findOneBy(array('idAtendimentos'=>$idCall));
        if(!empty($call)){
            $result['call'] = true;
            $result['messagesCall'] = $this->entityManager->getRepository('Common\Entity\ConversasAtendimento')->findBy(array('idAtendimentoConversas' => $idCall,'idStatusConversas' => 4));
        }else{
            $result['call'] = false;
            $result['messagesCall'] = array();
        }
        return $result;
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
    
    public function getAllMessagesClient($numberClient){
        
        $query = $this->entityManager->createQueryBuilder();
        $query->select('conversasAtendimento')
                ->from('Common\Entity\ConversasAtendimento', 'conversasAtendimento')
                ->join('common\Entity\Atendimentos','atendimentos','WITH','atendimentos.idAtendimentos = conversasAtendimento.idAtendimentoConversas')
                ->where('(conversasAtendimento.nmrRecebido = :numberClient OR conversasAtendimento.nmrEnviado = :numberClient) AND atendimentos.idStatusAtendimentos = 7')
                ->setParameters(array('numberClient' => $numberClient));
        
        try{
            $conversasAtendimento = $query->getQuery()->getResult();
        }catch (\Exception $e){
            $this->setLogTalk("Listar mensagens anteriores", $e->getMessage());
            return false;
        }
        
        return $conversasAtendimento;
    }
    
    public function deleteFilesDB(){
        $pasta = './vendor/whatsapp/chat-api/src/wadata';
        $file = $pasta."/axolotl-{$this->users->getNmWhatsapp()}.db";
        if(is_file($file) || is_dir($file))
            unlink($file);
    }
}


