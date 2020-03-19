<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservacion
 *
 * @ORM\Table(name="reservacion")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReservacionRepository")
 */
class Reservacion
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
    * @ORM\ManyToOne(targetEntity="Persona")
    * @ORM\JoinColumn(name="persona", referencedColumnName="id")
    */
    private $persona;

    /**
    * @ORM\ManyToOne(targetEntity="Cama")
    * @ORM\JoinColumn(name="cama", referencedColumnName="id")
    */
    private $cama;

    /**
     * @var string
     *
     * @ORM\Column(name="fechaInicio", type="datetime")
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaFin", type="datetime")
     */
    private $fechaFin;


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
     * Set fechaInicio
     *
     * @param string $fechaInicio
     *
     * @return Reservacion
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return string
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     *
     * @return Reservacion
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set persona
     *
     * @param \AppBundle\Entity\Persona $persona
     *
     * @return Reservacion
     */
    public function setPersona(\AppBundle\Entity\Persona $persona = null)
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get persona
     *
     * @return \AppBundle\Entity\Persona
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * Set cama
     *
     * @param \AppBundle\Entity\Cama $cama
     *
     * @return Reservacion
     */
    public function setCama(\AppBundle\Entity\Cama $cama = null)
    {
        $this->cama = $cama;

        return $this;
    }

    /**
     * Get cama
     *
     * @return \AppBundle\Entity\Cama
     */
    public function getCama()
    {
        return $this->cama;
    }
}
