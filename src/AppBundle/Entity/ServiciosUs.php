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
    * @ORM\ManyToOne(targetEntity="Persona")
    * @ORM\JoinColumn(name="persona", referencedColumnName="id")
    */
    private $persona;


    /**
    * @ORM\ManyToOne(targetEntity="Servicios")
    * @ORM\JoinColumn(name="servicio", referencedColumnName="id")
    */

    private $servicio;


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
     * Set persona
     *
     * @param \AppBundle\Entity\Persona $persona
     *
     * @return ServiciosUs
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
     * Set servicio
     *
     * @param \AppBundle\Entity\Servicios $servicio
     *
     * @return ServiciosUs
     */
    public function setServicio(\AppBundle\Entity\Servicios $servicio = null)
    {
        $this->servicio = $servicio;

        return $this;
    }

    /**
     * Get servicio
     *
     * @return \AppBundle\Entity\Servicios
     */
    public function getServicio()
    {
        return $this->servicio;
    }
}
