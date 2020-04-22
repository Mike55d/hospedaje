<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReservaPersona
 *
 * @ORM\Table(name="reserva_persona")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReservaPersonaRepository")
 */
class ReservaPersona
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
     * @var string
     *
     * @ORM\Column(name="entrada", type="datetime" , nullable = true)
     */
    private $entrada;

    /**
     * @var string
     *
     * @ORM\Column(name="salida", type="datetime" , nullable = true)
     */
    private $salida;
    /**
    * @ORM\ManyToOne(targetEntity="Reserva")
    * @ORM\JoinColumn(name="reserva", referencedColumnName="id" , nullable=true)
    */
    private $reserva;

    /**
    * @ORM\ManyToOne(targetEntity="Persona")
    * @ORM\JoinColumn(name="persona", referencedColumnName="id")
    */
    private $persona;


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
     * Set entrada
     *
     * @param \DateTime $entrada
     *
     * @return ReservaPersona
     */
    public function setEntrada($entrada)
    {
        $this->entrada = $entrada;

        return $this;
    }

    /**
     * Get entrada
     *
     * @return \DateTime
     */
    public function getEntrada()
    {
        return $this->entrada;
    }

    /**
     * Set salida
     *
     * @param \DateTime $salida
     *
     * @return ReservaPersona
     */
    public function setSalida($salida)
    {
        $this->salida = $salida;

        return $this;
    }

    /**
     * Get salida
     *
     * @return \DateTime
     */
    public function getSalida()
    {
        return $this->salida;
    }

    /**
     * Set reserva
     *
     * @param \AppBundle\Entity\Reserva $reserva
     *
     * @return ReservaPersona
     */
    public function setReserva(\AppBundle\Entity\Reserva $reserva = null)
    {
        $this->reserva = $reserva;

        return $this;
    }

    /**
     * Get reserva
     *
     * @return \AppBundle\Entity\Reserva
     */
    public function getReserva()
    {
        return $this->reserva;
    }

    /**
     * Set persona
     *
     * @param \AppBundle\Entity\Persona $persona
     *
     * @return ReservaPersona
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
}
