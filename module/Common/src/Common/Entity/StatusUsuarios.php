<?php

namespace Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatusUsuarios
 *
 * @ORM\Table(name="status_usuarios")
 * @ORM\Entity
 */
class StatusUsuarios
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_status_usuarios", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idStatusUsuarios;

    /**
     * @var string
     *
     * @ORM\Column(name="status_usuarios", type="string", length=45, nullable=false)
     */
    private $statusUsuarios;



    /**
     * Get idStatusUsuarios
     *
     * @return integer
     */
    public function getIdStatusUsuarios()
    {
        return $this->idStatusUsuarios;
    }

    /**
     * Set statusUsuarios
     *
     * @param string $statusUsuarios
     *
     * @return StatusUsuarios
     */
    public function setStatusUsuarios($statusUsuarios)
    {
        $this->statusUsuarios = $statusUsuarios;

        return $this;
    }

    /**
     * Get statusUsuarios
     *
     * @return string
     */
    public function getStatusUsuarios()
    {
        return $this->statusUsuarios;
    }
}
