<?php

namespace Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Logs
 *
 * @ORM\Table(name="logs")
 * @ORM\Entity
 */
class Logs
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_logs", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLogs;

    /**
     * @var string
     *
     * @ORM\Column(name="descricao_logs", type="text", length=16, nullable=true)
     */
    private $descricaoLogs;

    /**
     * @var string
     *
     * @ORM\Column(name="descricao_acao_logs", type="string", length=45, nullable=true)
     */
    private $descricaoAcaoLogs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_logs", type="datetime", nullable=false)
     */
    private $dataLogs = 'CURRENT_TIMESTAMP';



    /**
     * Get idLogs
     *
     * @return integer
     */
    public function getIdLogs()
    {
        return $this->idLogs;
    }

    /**
     * Set descricaoLogs
     *
     * @param string $descricaoLogs
     *
     * @return Logs
     */
    public function setDescricaoLogs($descricaoLogs)
    {
        $this->descricaoLogs = $descricaoLogs;

        return $this;
    }

    /**
     * Get descricaoLogs
     *
     * @return string
     */
    public function getDescricaoLogs()
    {
        return $this->descricaoLogs;
    }

    /**
     * Set descricaoAcaoLogs
     *
     * @param string $descricaoAcaoLogs
     *
     * @return Logs
     */
    public function setDescricaoAcaoLogs($descricaoAcaoLogs)
    {
        $this->descricaoAcaoLogs = $descricaoAcaoLogs;

        return $this;
    }

    /**
     * Get descricaoAcaoLogs
     *
     * @return string
     */
    public function getDescricaoAcaoLogs()
    {
        return $this->descricaoAcaoLogs;
    }

    /**
     * Set dataLogs
     *
     * @param \DateTime $dataLogs
     *
     * @return Logs
     */
    public function setDataLogs($dataLogs)
    {
        $this->dataLogs = $dataLogs;

        return $this;
    }

    /**
     * Get dataLogs
     *
     * @return \DateTime
     */
    public function getDataLogs()
    {
        return $this->dataLogs;
    }
}
