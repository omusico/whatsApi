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
     * @param
     *            EntityManager
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
                    $this->storageMessage($this->users->getNmWhatsapp(), $number, $mess->getAttribute('url'));
                } else {
                    $mess = $message->getChild("body");
                    $this->storageMessage($this->users->getNmWhatsapp(), $number, $mess->getData());
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
}

