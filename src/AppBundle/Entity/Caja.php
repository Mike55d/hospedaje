<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Caja
 *
 * @ORM\Table(name="caja")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CajaRepository")
 */
class Caja
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
     * @ORM\Column(name="nro", type="string", length=255, unique=true)
     */
    private $nro;

    /**
     * @var string
     *
     * @ORM\Column(name="responsable", type="string", length=255)
     */
    private $responsable;

    /**
     * @var string
     *
     * @ORM\Column(name="emailCaja", type="string", length=255)
     */
    private $emailCaja;

    /**
     * @var string
     *
     * @ORM\Column(name="encargado", type="string", length=255)
     */
    private $encargado;

    /**
     * @var string
     *
     * @ORM\Column(name="emailEncargado", type="string", length=255)
     */
    private $emailEncargado;

    /**
    * @ORM\ManyToOne(targetEntity="Grupo")
    * @ORM\JoinColumn(name="grupo", referencedColumnName="id")
    */
    private $grupo;


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
     * Set nro
     *
     * @param string $nro
     *
     * @return Caja
     */
    public function setNro($nro)
    {
        $this->nro = $nro;

        return $this;
    }

    /**
     * Get nro
     *
     * @return string
     */
    public function getNro()
    {
        return $this->nro;
    }

    /**
     * Set responsable
     *
     * @param string $responsable
     *
     * @return Caja
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return string
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Set emailCaja
     *
     * @param string $emailCaja
     *
     * @return Caja
     */
    public function setEmailCaja($emailCaja)
    {
        $this->emailCaja = $emailCaja;

        return $this;
    }

    /**
     * Get emailCaja
     *
     * @return string
     */
    public function getEmailCaja()
    {
        return $this->emailCaja;
    }

    /**
     * Set encargado
     *
     * @param string $encargado
     *
     * @return Caja
     */
    public function setEncargado($encargado)
    {
        $this->encargado = $encargado;

        return $this;
    }

    /**
     * Get encargado
     *
     * @return string
     */
    public function getEncargado()
    {
        return $this->encargado;
    }

    /**
     * Set emailEncargado
     *
     * @param string $emailEncargado
     *
     * @return Caja
     */
    public function setEmailEncargado($emailEncargado)
    {
        $this->emailEncargado = $emailEncargado;

        return $this;
    }

    /**
     * Get emailEncargado
     *
     * @return string
     */
    public function getEmailEncargado()
    {
        return $this->emailEncargado;
    }

    /**
     * Set grupo
     *
     * @param \AppBundle\Entity\Grupo $grupo
     *
     * @return Caja
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
}
