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
     * @ORM\Column(name="nmr_enviado", type="string", length=13, nullable=false)
     */
    private $nmrEnviado;

    /**
     * @var string
     *
     * @ORM\Column(name="nmr_recebido", type="string", length=13, nullable=false)
     */
    private $nmrRecebido;

    /**
     * @var string
     *
     * @ORM\Column(name="mensagem", type="text", length=-1, nullable=false)
     */
    private $mensagem;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_conversa_atendimento", type="datetime", nullable=false)
     */
    private $dataConversaAtendimento = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="id_atendimento_conversas", type="integer", nullable=false)
     */
    private $idAtendimentoConversas;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_status_conversas", type="integer", nullable=false)
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
     * Set nmrEnviado
     *
     * @param string $nmrEnviado
     *
     * @return ConversasAtendimento
     */
    public function setNmrEnviado($nmrEnviado)
    {
        $this->nmrEnviado = $nmrEnviado;

        return $this;
    }

    /**
     * Get nmrEnviado
     *
     * @return string
     */
    public function getNmrEnviado()
    {
        return $this->nmrEnviado;
    }

    /**
     * Set nmrRecebido
     *
     * @param string $nmrRecebido
     *
     * @return ConversasAtendimento
     */
    public function setNmrRecebido($nmrRecebido)
    {
        $this->nmrRecebido = $nmrRecebido;

        return $this;
    }

    /**
     * Get nmrRecebido
     *
     * @return string
     */
    public function getNmrRecebido()
    {
        return $this->nmrRecebido;
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
     * @param integer $idAtendimentoConversas
     *
     * @return ConversasAtendimento
     */
    public function setIdAtendimentoConversas($idAtendimentoConversas)
    {
        $this->idAtendimentoConversas = $idAtendimentoConversas;

        return $this;
    }

    /**
     * Get idAtendimentoConversas
     *
     * @return integer
     */
    public function getIdAtendimentoConversas()
    {
        return $this->idAtendimentoConversas;
    }

    /**
     * Set idStatusConversas
     *
     * @param integer $idStatusConversas
     *
     * @return ConversasAtendimento
     */
    public function setIdStatusConversas($idStatusConversas)
    {
        $this->idStatusConversas = $idStatusConversas;

        return $this;
    }

    /**
     * Get idStatusConversas
     *
     * @return integer
     */
    public function getIdStatusConversas()
    {
        return $this->idStatusConversas;
    }
}
