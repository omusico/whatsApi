<?php

namespace Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ObservacoesAtendimentos
 *
 * @ORM\Table(name="observacoes_atendimentos", indexes={@ORM\Index(name="fk_observacoes_atendimentos_atendimentos1_idx", columns={"id_atendimento_observacao"})})
 * @ORM\Entity
 */
class ObservacoesAtendimentos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_observacoes_atendimento", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idObservacoesAtendimento;

    /**
     * @var string
     *
     * @ORM\Column(name="observacoes", type="string", length=255, nullable=true)
     */
    private $observacoes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data", type="datetime", nullable=false)
     */
    private $data = 'CURRENT_TIMESTAMP';

    /**
     * @var \Common\Entity\Atendimentos
     *
     * @ORM\ManyToOne(targetEntity="Common\Entity\Atendimentos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_atendimento_observacao", referencedColumnName="id_atendimentos")
     * })
     */
    private $idAtendimentoObservacao;



    /**
     * Get idObservacoesAtendimento
     *
     * @return integer
     */
    public function getIdObservacoesAtendimento()
    {
        return $this->idObservacoesAtendimento;
    }

    /**
     * Set observacoes
     *
     * @param string $observacoes
     *
     * @return ObservacoesAtendimentos
     */
    public function setObservacoes($observacoes)
    {
        $this->observacoes = $observacoes;

        return $this;
    }

    /**
     * Get observacoes
     *
     * @return string
     */
    public function getObservacoes()
    {
        return $this->observacoes;
    }

    /**
     * Set data
     *
     * @param \DateTime $data
     *
     * @return ObservacoesAtendimentos
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return \DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set idAtendimentoObservacao
     *
     * @param \Common\Entity\Atendimentos $idAtendimentoObservacao
     *
     * @return ObservacoesAtendimentos
     */
    public function setIdAtendimentoObservacao(\Common\Entity\Atendimentos $idAtendimentoObservacao = null)
    {
        $this->idAtendimentoObservacao = $idAtendimentoObservacao;

        return $this;
    }

    /**
     * Get idAtendimentoObservacao
     *
     * @return \Common\Entity\Atendimentos
     */
    public function getIdAtendimentoObservacao()
    {
        return $this->idAtendimentoObservacao;
    }
}
