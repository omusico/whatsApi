<?php

namespace Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usuarios
 *
 * @ORM\Table(name="usuarios", indexes={@ORM\Index(name="fk_usuarios_status_usuarios1_idx", columns={"id_status_usuarios"})})
 * @ORM\Entity
 */
class Usuarios
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_usuarios", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUsuarios;

    /**
     * @var string
     *
     * @ORM\Column(name="nome_usuario", type="string", length=45, nullable=true)
     */
    private $nomeUsuario;

    /**
     * @var string
     *
     * @ORM\Column(name="senha_usuario", type="string", length=45, nullable=true)
     */
    private $senhaUsuario;

    /**
     * @var float
     *
     * @ORM\Column(name="nm_whatsapp", type="float", precision=10, scale=0, nullable=false)
     */
    private $nmWhatsapp;

    /**
     * @var string
     *
     * @ORM\Column(name="senha_whatsap", type="string", length=12, nullable=false)
     */
    private $senhaWhatsap;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_criacao", type="datetime", nullable=true)
     */
    private $dataCriacao = 'CURRENT_TIMESTAMP';

    /**
     * @var \Common\Entity\StatusUsuarios
     *
     * @ORM\ManyToOne(targetEntity="Common\Entity\StatusUsuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_status_usuarios", referencedColumnName="id_status_usuarios")
     * })
     */
    private $idStatusUsuarios;



    /**
     * Get idUsuarios
     *
     * @return integer
     */
    public function getIdUsuarios()
    {
        return $this->idUsuarios;
    }

    /**
     * Set nomeUsuario
     *
     * @param string $nomeUsuario
     *
     * @return Usuarios
     */
    public function setNomeUsuario($nomeUsuario)
    {
        $this->nomeUsuario = $nomeUsuario;

        return $this;
    }

    /**
     * Get nomeUsuario
     *
     * @return string
     */
    public function getNomeUsuario()
    {
        return $this->nomeUsuario;
    }

    /**
     * Set senhaUsuario
     *
     * @param string $senhaUsuario
     *
     * @return Usuarios
     */
    public function setSenhaUsuario($senhaUsuario)
    {
        $this->senhaUsuario = $senhaUsuario;

        return $this;
    }

    /**
     * Get senhaUsuario
     *
     * @return string
     */
    public function getSenhaUsuario()
    {
        return $this->senhaUsuario;
    }

    /**
     * Set nmWhatsapp
     *
     * @param float $nmWhatsapp
     *
     * @return Usuarios
     */
    public function setNmWhatsapp($nmWhatsapp)
    {
        $this->nmWhatsapp = $nmWhatsapp;

        return $this;
    }

    /**
     * Get nmWhatsapp
     *
     * @return float
     */
    public function getNmWhatsapp()
    {
        return $this->nmWhatsapp;
    }

    /**
     * Set senhaWhatsap
     *
     * @param string $senhaWhatsap
     *
     * @return Usuarios
     */
    public function setSenhaWhatsap($senhaWhatsap)
    {
        $this->senhaWhatsap = $senhaWhatsap;

        return $this;
    }

    /**
     * Get senhaWhatsap
     *
     * @return string
     */
    public function getSenhaWhatsap()
    {
        return $this->senhaWhatsap;
    }

    /**
     * Set dataCriacao
     *
     * @param \DateTime $dataCriacao
     *
     * @return Usuarios
     */
    public function setDataCriacao($dataCriacao)
    {
        $this->dataCriacao = $dataCriacao;

        return $this;
    }

    /**
     * Get dataCriacao
     *
     * @return \DateTime
     */
    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }

    /**
     * Set idStatusUsuarios
     *
     * @param \Common\Entity\StatusUsuarios $idStatusUsuarios
     *
     * @return Usuarios
     */
    public function setIdStatusUsuarios(\Common\Entity\StatusUsuarios $idStatusUsuarios = null)
    {
        $this->idStatusUsuarios = $idStatusUsuarios;

        return $this;
    }

    /**
     * Get idStatusUsuarios
     *
     * @return \Common\Entity\StatusUsuarios
     */
    public function getIdStatusUsuarios()
    {
        return $this->idStatusUsuarios;
    }
}
