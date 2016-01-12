<?php

namespace Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Atendimentos
 *
 * @ORM\Table(name="atendimentos", uniqueConstraints={@ORM\UniqueConstraint(name="protocolo_atendimento_UNIQUE", columns={"protocolo_atendimento"})}, indexes={@ORM\Index(name="fk_atendimentos_usuarios_idx", columns={"id_usuario_atendimento"})})
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
     * @var integer
     *
     * @ORM\Column(name="status_atendimento", type="integer", nullable=true)
     */
    private $statusAtendimento;

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
     * Set statusAtendimento
     *
     * @param integer $statusAtendimento
     *
     * @return Atendimentos
     */
    public function setStatusAtendimento($statusAtendimento)
    {
        $this->statusAtendimento = $statusAtendimento;

        return $this;
    }

    /**
     * Get statusAtendimento
     *
     * @return integer
     */
    public function getStatusAtendimento()
    {
        return $this->statusAtendimento;
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
