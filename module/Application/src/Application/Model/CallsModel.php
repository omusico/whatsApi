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
    
    public function getCalls()
    {
        $query = $this->entityManager->createQueryBuilder();
        $query->select('atendimentos')
        ->from('Common\Entity\Atendimentos', 'atendimentos')
        ->where('atendimentos.idStatusAtendimentos IN (5,6)');
        try{
            $calls = $query->getQuery()->getResult();
        }catch (\Exception $e){
            $this->setLogTalk("Listar atendimentos abertos/ pendentes", $e->getMessage());
            return null;
        }
        return $calls;
    }
    
    public function finalizeCall($idCall){
        $call = $this->setCall($idCall);
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
    
    public function sendObs($obsText,$idCall){
        $call = $this->setCall($idCall);
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
    
    public function getCall($idCall)
    {
        $call = $this->entityManager->getRepository('Common\Entity\Atendimentos')->findOneBy(array('idStatusAtendimentos' => 5,'idAtendimentos' => $idCall));
        return $call;
    }
    
    public function getObsCall($idCall){
        $obs = $this->entityManager->getRepository('Common\Entity\ObservacoesAtendimentos')->findBy(array('idAtendimentoObservacao'=> $idCall));
        return $obs;
    }
    
}