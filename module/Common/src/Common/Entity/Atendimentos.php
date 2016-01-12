<?php

namespace Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Atendimentos
 *
 * @ORM\Table(name="atendimentos", uniqueConstraints={@ORM\UniqueConstraint(name="protocolo_atendimento_UNIQUE", columns={"protocolo_atendimento"})}, indexes={@ORM\Index(name="fk_atendimentos_usuarios_idx", columns={"id_usuario_atendimento"}), @ORM\Index(name="fk_atendimentos_status_atendimentos1_idx", columns={"Id_status_atendimentos"})})
 * @ORM\Entity
 */
class Atendimentos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_atendimentos", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAtendimentos;

    /**
     * @var string
     *
     * @ORM\Column(name="protocolo_atendimento", type="string", length=45, nullable=true)
     */
    private $protocoloAtendimento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_atendimento", type="datetime", nullable=false)
     */
    private $dataAtendimento = 'CURRENT_TIMESTAMP';

    /**
     * @var \Common\Entity\StatusAtendimentos
     *
     * @ORM\ManyToOne(targetEntity="Common\Entity\StatusAtendimentos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_status_atendimentos", referencedColumnName="id_status_atendimentos")
     * })
     */
    private $idStatusAtendimentos;

    /**
     * @var \Common\Entity\Usuarios
     *
     * @ORM\ManyToOne(targetEntity="Common\Entity\Usuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_usuario_atendimento", referencedColumnName="id_usuarios")
     * })
     */
    private $idUsuarioAtendimento;



    /**
     * Get idAtendimentos
     *
     * @return integer
     */
    public function getIdAtendimentos()
    {
        return $this->idAtendimentos;
    }

    /**
     * Set protocoloAtendimento
     *
     * @param string $protocoloAtendimento
     *
     * @return Atendimentos
     */
    public function setProtocoloAtendimento($protocoloAtendimento)
    {
        $this->protocoloAtendimento = $protocoloAtendimento;

        return $this;
    }

    /**
     * Get protocoloAtendimento
     *
     * @return string
     */
    public function getProtocoloAtendimento()
    {
        return $this->protocoloAtendimento;
    }

    /**
     * Set dataAtendimento
     *
     * @param \DateTime $dataAtendimento
     *
     * @return Atendimentos
     */
    public function setDataAtendimento($dataAtendimento)
    {
        $this->dataAtendimento = $dataAtendimento;

        return $this;
    }

    /**
     * Get dataAtendimento
     *
     * @return \DateTime
     */
    public function getDataAtendimento()
    {
        return $this->dataAtendimento;
    }

    /**
     * Set idStatusAtendimentos
     *
     * @param \Common\Entity\StatusAtendimentos $idStatusAtendimentos
     *
     * @return Atendimentos
     */
    public function setIdStatusAtendimentos(\Common\Entity\StatusAtendimentos $idStatusAtendimentos = null)
    {
        $this->idStatusAtendimentos = $idStatusAtendimentos;

        return $this;
    }

    /**
     * Get idStatusAtendimentos
     *
     * @return \Common\Entity\StatusAtendimentos
     */
    public function getIdStatusAtendimentos()
    {
        return $this->idStatusAtendimentos;
    }

    /**
     * Set idUsuarioAtendimento
     *
     * @param \Common\Entity\Usuarios $idUsuarioAtendimento
     *
     * @return Atendimentos
     */
    public function setIdUsuarioAtendimento(\Common\Entity\Usuarios $idUsuarioAtendimento = null)
    {
        $this->idUsuarioAtendimento = $idUsuarioAtendimento;

        return $this;
    }

    /**
     * Get idUsuarioAtendimento
     *
     * @return \Common\Entity\Usuarios
     */
    public function getIdUsuarioAtendimento()
    {
        return $this->idUsuarioAtendimento;
    }
}
