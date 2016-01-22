<?php
namespace Application\Model;

    use Doctrine\ORM\EntityManager;
use Common\Entity\Usuarios;
use Common\Entity\Atendimentos;
class CallsModel extends ManagerWhatsModel{
    /**
     *
     * @param  EntityManager
     *
     */
    private $entityManager;
    
    /**
     * 
     * @var Atendimentos 
     **/
    public $atendimentos;
    
    public function __construct(EntityManager $entityManager, Usuarios $users, $debug = false)
    {
        $this->entityManager = $entityManager;
        parent::__construct($this->entityManager, $users, $debug);
    }
    
    public function setCall($idCall){
        $this->atendimentos = $this->entityManager->getRepository('Common\Entity\Atendimentos')->findOneBy(array('idAtendimentos' => $idCall));
        
        if(!$this->atendimentos)
            throw  new \Exception("Atendimento não encontrado");
        
        return $this->atendimentos;
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
    
    public function setPendingCall(){
        $this->atendimentos->setIdStatusAtendimentos($this->entityManager->getRepository('Common\Entity\StatusAtendimentos')->findOneBy(array('idStatusAtendimentos' => 6)));
        try{
            $this->entityManager->persist($this->atendimentos);
            $this->entityManager->flush();
        }catch (\Exception $e){
            $this->setLogTalk("Atualizar status para pendente", $e->getMessage());
            return false;
        }
        return true;
    }
    
}