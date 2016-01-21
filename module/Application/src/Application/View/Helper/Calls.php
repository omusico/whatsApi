<?php
namespace  Application\View\Helper;
use Zend\View\Helper\AbstractHelper;
use Application\Model\MessageModel;

class Calls extends AbstractHelper{
    protected $users;
    
    protected $entityManager;
    
    public function __invoke($users)
    {
        $sm = $this->getView()->getHelperPluginManager()->getServiceLocator();
        $renderer = $sm->get('Zend\View\Renderer\RendererInterface');
    
        $this->users  = $users;
        $this->entityManager = $sm->get('Doctrine\ORM\EntityManager');
         
        return $this;
    }
    
    public function getCountMessage($idCall){
        
        $messageModel = new MessageModel($this->entityManager, $this->users);
        $countMessage = $messageModel->getCountMessagesUnreadCalls($idCall);
        
        return $countMessage;
    }
}