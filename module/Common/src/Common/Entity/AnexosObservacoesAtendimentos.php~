<?php

namespace Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnexosObservacoesAtendimentos
 *
 * @ORM\Table(name="anexos_observacoes_atendimentos", indexes={@ORM\Index(name="fk_anexos_observacoes_atendimentos_observacoes_atendimentos_idx", columns={"id_observacao_atendimento"})})
 * @ORM\Entity
 */
class AnexosObservacoesAtendimentos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_anexos_observacoes_atendimentos", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAnexosObservacoesAtendimentos;

    /**
     * @var string
     *
     * @ORM\Column(name="nome_anexo", type="string", length=60, nullable=false)
     */
    private $nomeAnexo;

    /**
     * @var \Common\Entity\ObservacoesAtendimentos
     *
     * @ORM\ManyToOne(targetEntity="Common\Entity\ObservacoesAtendimentos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_observacao_atendimento", referencedColumnName="id_observacoes_atendimento")
     * })
     */
    private $idObservacaoAtendimento;



    /**
     * Get idAnexosObservacoesAtendimentos
     *
     * @return integer
     */
    public function getIdAnexosObservacoesAtendimentos()
    {
        return $this->idAnexosObservacoesAtendimentos;
    }

    /**
     * Set nomeAnexo
     *
     * @param string $nomeAnexo
     *
     * @return AnexosObservacoesAtendimentos
     */
    public function setNomeAnexo($nomeAnexo)
    {
        $this->nomeAnexo = $nomeAnexo;

        return $this;
    }

    /**
     * Get nomeAnexo
     *
     * @return string
     */
    public function getNomeAnexo()
    {
        return $this->nomeAnexo;
    }

    /**
     * Set idObservacaoAtendimento
     *
     * @param \Common\Entity\ObservacoesAtendimentos $idObservacaoAtendimento
     *
     * @return AnexosObservacoesAtendimentos
     */
    public function setIdObservacaoAtendimento(\Common\Entity\ObservacoesAtendimentos $idObservacaoAtendimento = null)
    {
        $this->idObservacaoAtendimento = $idObservacaoAtendimento;

        return $this;
    }

    /**
     * Get idObservacaoAtendimento
     *
     * @return \Common\Entity\ObservacoesAtendimentos
     */
    public function getIdObservacaoAtendimento()
    {
        return $this->idObservacaoAtendimento;
    }
}
