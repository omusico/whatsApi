<?php

namespace Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConversasAtendimento
 *
 * @ORM\Table(name="conversas_atendimento", indexes={@ORM\Index(name="fk_conversas_atendimento_atendimentos1_idx", columns={"id_atendimento_conversas"})})
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="status_mensagem_atendimento", type="integer", nullable=true)
     */
    private $statusMensagemAtendimento;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return ConversasAtendimento
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set statusMensagemAtendimento
     *
     * @param integer $statusMensagemAtendimento
     *
     * @return ConversasAtendimento
     */
    public function setStatusMensagemAtendimento($statusMensagemAtendimento)
    {
        $this->statusMensagemAtendimento = $statusMensagemAtendimento;

        return $this;
    }

    /**
     * Get statusMensagemAtendimento
     *
     * @return integer
     */
    public function getStatusMensagemAtendimento()
    {
        return $this->statusMensagemAtendimento;
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
}
