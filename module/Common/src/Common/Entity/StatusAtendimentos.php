<?php

namespace Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatusAtendimentos
 *
 * @ORM\Table(name="status_atendimentos")
 * @ORM\Entity
 */
class StatusAtendimentos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_status_atendimentos", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idStatusAtendimentos;

    /**
     * @var string
     *
     * @ORM\Column(name="status_atendimentos", type="string", length=45, nullable=false)
     */
    private $statusAtendimentos;



    /**
     * Get idStatusAtendimentos
     *
     * @return integer
     */
    public function getIdStatusAtendimentos()
    {
        return $this->idStatusAtendimentos;
    }

    /**
     * Set statusAtendimentos
     *
     * @param string $statusAtendimentos
     *
     * @return StatusAtendimentos
     */
    public function setStatusAtendimentos($statusAtendimentos)
    {
        $this->statusAtendimentos = $statusAtendimentos;

        return $this;
    }

    /**
     * Get statusAtendimentos
     *
     * @return string
     */
    public function getStatusAtendimentos()
    {
        return $this->statusAtendimentos;
    }
}
