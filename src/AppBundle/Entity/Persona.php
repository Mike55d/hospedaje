<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Persona
 *
 * @ORM\Table(name="persona")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PersonaRepository")
 */
class Persona
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
     * @ORM\Column(name="tratamiento", type="string", length=255)
     */
    private $tratamiento;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="fechaNacimiento", type="datetime")
     */
    private $fechaNacimiento;


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
     * @var string
     *
     * @ORM\Column(name="comidas", type="string" , length=255 , nullable = true)
     */
    private $comidas;

        /**
     * @var string
     *
     * @ORM\Column(name="huesped", type="boolean" , nullable = true)
     */
    private $huesped;

    /**
    * @ORM\ManyToOne(targetEntity="Grupo")
    * @ORM\JoinColumn(name="grupo", referencedColumnName="id")
    */
    private $grupo;

    /**
    * @ORM\ManyToOne(targetEntity="User")
    * @ORM\JoinColumn(name="user", referencedColumnName="id")
    */
    private $user;

    /**
    * @ORM\ManyToOne(targetEntity="Reserva")
    * @ORM\JoinColumn(name="reserva", referencedColumnName="id" , nullable=true)
    */
    private $reserva;


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
     * Set tratamiento
     *
     * @param string $tratamiento
     *
     * @return Persona
     */
    public function setTratamiento($tratamiento)
    {
        $this->tratamiento = $tratamiento;

        return $this;
    }

    /**
     * Get tratamiento
     *
     * @return string
     */
    public function getTratamiento()
    {
        return $this->tratamiento;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Persona
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set entrada
     *
     * @param \DateTime $entrada
     *
     * @return Persona
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
     * @return Persona
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
     * Set comidas
     *
     * @param string $comidas
     *
     * @return Persona
     */
    public function setComidas($comidas)
    {
        $this->comidas = $comidas;

        return $this;
    }

    /**
     * Get comidas
     *
     * @return string
     */
    public function getComidas()
    {
        return $this->comidas;
    }

    /**
     * Set huesped
     *
     * @param boolean $huesped
     *
     * @return Persona
     */
    public function setHuesped($huesped)
    {
        $this->huesped = $huesped;

        return $this;
    }

    /**
     * Get huesped
     *
     * @return boolean
     */
    public function getHuesped()
    {
        return $this->huesped;
    }

    /**
     * Set grupo
     *
     * @param \AppBundle\Entity\Grupo $grupo
     *
     * @return Persona
     */
    public function setGrupo(\AppBundle\Entity\Grupo $grupo = null)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return \AppBundle\Entity\Grupo
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Persona
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     *
     * @return Persona
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set reserva
     *
     * @param \AppBundle\Entity\Reserva $reserva
     *
     * @return Persona
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
}