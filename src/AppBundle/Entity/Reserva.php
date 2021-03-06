<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Solicitud
 *
 * @ORM\Table(name="reserva")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReservaRepository")
 */
class Reserva
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
     * @ORM\Column(name="menor", type="integer")
     */
    private $menor;

    /**
     * @var int
     *
     * @ORM\Column(name="adulto", type="integer")
     */
    private $adulto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="llegada", type="datetime")
     */
    private $llegada;

     /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="salida", type="datetime")
     */
    private $salida;

            /**
     * @var string
     *
     * @ORM\Column(name="primeraComida", type="string", length=255)
     */
    private $primeraComida;

            /**
     * @var string
     *
     * @ORM\Column(name="ultimaComida", type="string", length=255)
     */
    private $ultimaComida;
    
        /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
    * @ORM\ManyToOne(targetEntity="User")
    * @ORM\JoinColumn(name="user", referencedColumnName="id")
    */

    private $user;


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
     * Set menor
     *
     * @param integer $menor
     *
     * @return Solicitud
     */
    public function setMenor($menor)
    {
        $this->menor = $menor;

        return $this;
    }

    /**
     * Get menor
     *
     * @return int
     */
    public function getMenor()
    {
        return $this->menor;
    }

    /**
     * Set adulto
     *
     * @param integer $adulto
     *
     * @return Solicitud
     */
    public function setAdulto($adulto)
    {
        $this->adulto = $adulto;

        return $this;
    }

    /**
     * Get adulto
     *
     * @return int
     */
    public function getAdulto()
    {
        return $this->adulto;
    }

    /**
     * Set llegada
     *
     * @param \DateTime $llegada
     *
     * @return Solicitud
     */
    public function setLlegada($llegada)
    {
        $this->llegada = $llegada;

        return $this;
    }

    /**
     * Get llegada
     *
     * @return \DateTime
     */
    public function getLlegada()
    {
        return $this->llegada;
    }

    /**
     * Set salida
     *
     * @param \DateTime $salida
     *
     * @return Solicitud
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
     * Set status
     *
     * @param string $status
     *
     * @return Solicitud
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Solicitud
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Reserva
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
     * Set primeraComida
     *
     * @param string $primeraComida
     *
     * @return Reserva
     */
    public function setPrimeraComida($primeraComida)
    {
        $this->primeraComida = $primeraComida;

        return $this;
    }

    /**
     * Get primeraComida
     *
     * @return string
     */
    public function getPrimeraComida()
    {
        return $this->primeraComida;
    }

    /**
     * Set ultimaComida
     *
     * @param string $ultimaComida
     *
     * @return Reserva
     */
    public function setUltimaComida($ultimaComida)
    {
        $this->ultimaComida = $ultimaComida;

        return $this;
    }

    /**
     * Get ultimaComida
     *
     * @return string
     */
    public function getUltimaComida()
    {
        return $this->ultimaComida;
    }
}
