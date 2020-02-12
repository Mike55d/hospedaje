<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServiciosUs
 *
 * @ORM\Table(name="servicios_us")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ServiciosUsRepository")
 */
class ServiciosUs
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="idUsuario", type="integer")
     */
    private $idUsuario;

    /**
     * @var int
     *
     * @ORM\Column(name="idServicio", type="integer")
     */
    private $idServicio;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idUsuario
     *
     * @param integer $idUsuario
     *
     * @return ServiciosUs
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get idUsuario
     *
     * @return int
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set idServicio
     *
     * @param integer $idServicio
     *
     * @return ServiciosUs
     */
    public function setIdServicio($idServicio)
    {
        $this->idServicio = $idServicio;

        return $this;
    }

    /**
     * Get idServicio
     *
     * @return int
     */
    public function getIdServicio()
    {
        return $this->idServicio;
    }
}

