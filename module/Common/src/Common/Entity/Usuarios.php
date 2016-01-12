<?php

namespace Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usuarios
 *
 * @ORM\Table(name="usuarios")
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
     * @var integer
     *
     * @ORM\Column(name="nm_whatsapp", type="integer", nullable=true)
     */
    private $nmWhatsapp;

    /**
     * @var string
     *
     * @ORM\Column(name="senha_whatsap", type="string", length=12, nullable=true)
     */
    private $senhaWhatsap;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;



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
     * @param integer $nmWhatsapp
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
     * @return integer
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
     * Set status
     *
     * @param integer $status
     *
     * @return Usuarios
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }
}
