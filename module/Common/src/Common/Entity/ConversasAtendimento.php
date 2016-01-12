<?php

namespace Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConversasAtendimento
 *
 * @ORM\Table(name="conversas_atendimento", indexes={@ORM\Index(name="fk_conversas_atendimento_atendimentos1_idx", columns={"id_atendimento_conversas"}), @ORM\Index(name="fk_conversas_atendimento_status_conversas1_idx", columns={"id_status_conversas"})})
 * @ORM\Entity
 */
class ConversasAtendimento
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_conversas", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idConversas;

    /**
     * @var string
     *
     * @ORM\Column(name="nome_contato", type="string", length=45, nullable=true)
     */
    private $nomeContato;

    /**
     * @var integer
     *
     * @ORM\Column(name="nmr_contato", type="integer", nullable=false)
     */
    private $nmrContato;

    /**
     * @var string
     *
     * @ORM\Column(name="mensagem", type="text", length=65535, nullable=false)
     */
    private $mensagem;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_conversa_atendimento", type="datetime", nullable=false)
     */
    private $dataConversaAtendimento = 'CURRENT_TIMESTAMP';

    /**
     * @var \Common\Entity\Atendimentos
     *
     * @ORM\ManyToOne(targetEntity="Common\Entity\Atendimentos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_atendimento_conversas", referencedColumnName="id_atendimentos")
     * })
     */
    private $idAtendimentoConversas;

    /**
     * @var \Common\Entity\StatusConversas
     *
     * @ORM\ManyToOne(targetEntity="Common\Entity\StatusConversas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_status_conversas", referencedColumnName="id_status_conversas")
     * })
     */
    private $idStatusConversas;



    /**
     * Get idConversas
     *
     * @return integer
     */
    public function getIdConversas()
    {
        return $this->idConversas;
    }

    /**
     * Set nomeContato
     *
     * @param string $nomeContato
     *
     * @return ConversasAtendimento
     */
    public function setNomeContato($nomeContato)
    {
        $this->nomeContato = $nomeContato;

        return $this;
    }

    /**
     * Get nomeContato
     *
     * @return string
     */
    public function getNomeContato()
    {
        return $this->nomeContato;
    }

    /**
     * Set nmrContato
     *
     * @param integer $nmrContato
     *
     * @return ConversasAtendimento
     */
    public function setNmrContato($nmrContato)
    {
        $this->nmrContato = $nmrContato;

        return $this;
    }

    /**
     * Get nmrContato
     *
     * @return integer
     */
    public function getNmrContato()
    {
        return $this->nmrContato;
    }

    /**
     * Set mensagem
     *
     * @param string $mensagem
     *
     * @return ConversasAtendimento
     */
    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;

        return $this;
    }

    /**
     * Get mensagem
     *
     * @return string
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }

    /**
     * Set dataConversaAtendimento
     *
     * @param \DateTime $dataConversaAtendimento
     *
     * @return ConversasAtendimento
     */
    public function setDataConversaAtendimento($dataConversaAtendimento)
    {
        $this->dataConversaAtendimento = $dataConversaAtendimento;

        return $this;
    }

    /**
     * Get dataConversaAtendimento
     *
     * @return \DateTime
     */
    public function getDataConversaAtendimento()
    {
        return $this->dataConversaAtendimento;
    }

    /**
     * Set idAtendimentoConversas
     *
     * @param \Common\Entity\Atendimentos $idAtendimentoConversas
     *
     * @return ConversasAtendimento
     */
    public function setIdAtendimentoConversas(\Common\Entity\Atendimentos $idAtendimentoConversas = null)
    {
        $this->idAtendimentoConversas = $idAtendimentoConversas;

        return $this;
    }

    /**
     * Get idAtendimentoConversas
     *
     * @return \Common\Entity\Atendimentos
     */
    public function getIdAtendimentoConversas()
    {
        return $this->idAtendimentoConversas;
    }

    /**
     * Set idStatusConversas
     *
     * @param \Common\Entity\StatusConversas $idStatusConversas
     *
     * @return ConversasAtendimento
     */
    public function setIdStatusConversas(\Common\Entity\StatusConversas $idStatusConversas = null)
    {
        $this->idStatusConversas = $idStatusConversas;

        return $this;
    }

    /**
     * Get idStatusConversas
     *
     * @return \Common\Entity\StatusConversas
     */
    public function getIdStatusConversas()
    {
        return $this->idStatusConversas;
    }
}
