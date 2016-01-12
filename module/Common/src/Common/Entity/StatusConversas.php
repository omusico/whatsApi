<?php

namespace Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatusConversas
 *
 * @ORM\Table(name="status_conversas")
 * @ORM\Entity
 */
class StatusConversas
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_status_conversas", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idStatusConversas;

    /**
     * @var string
     *
     * @ORM\Column(name="status_conversas", type="string", length=45, nullable=false)
     */
    private $statusConversas;



    /**
     * Get idStatusConversas
     *
     * @return integer
     */
    public function getIdStatusConversas()
    {
        return $this->idStatusConversas;
    }

    /**
     * Set statusConversas
     *
     * @param string $statusConversas
     *
     * @return StatusConversas
     */
    public function setStatusConversas($statusConversas)
    {
        $this->statusConversas = $statusConversas;

        return $this;
    }

    /**
     * Get statusConversas
     *
     * @return string
     */
    public function getStatusConversas()
    {
        return $this->statusConversas;
    }
}
