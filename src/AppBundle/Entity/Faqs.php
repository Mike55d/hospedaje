<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Faqs
 *
 * @ORM\Table(name="faqs")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FaqsRepository")
 */
class Faqs
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
     * @ORM\Column(name="pregunta", type="text", nullable=true)
     */
    private $pregunta;

    /**
     * @var string
     *
     * @ORM\Column(name="respuesta", type="text")
     */
    private $respuesta;

    /**
     * @var bool
     *
     * @ORM\Column(name="activa", type="boolean")
     */
    private $activa;


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
     * Set pregunta
     *
     * @param string $pregunta
     *
     * @return Faqs
     */
    public function setPregunta($pregunta)
    {
        $this->pregunta = $pregunta;

        return $this;
    }

    /**
     * Get pregunta
     *
     * @return string
     */
    public function getPregunta()
    {
        return $this->pregunta;
    }

    /**
     * Set respuesta
     *
     * @param string $respuesta
     *
     * @return Faqs
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;

        return $this;
    }

    /**
     * Get respuesta
     *
     * @return string
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    /**
     * Set activa
     *
     * @param boolean $activa
     *
     * @return Faqs
     */
    public function setActiva($activa)
    {
        $this->activa = $activa;

        return $this;
    }

    /**
     * Get activa
     *
     * @return bool
     */
    public function getActiva()
    {
        return $this->activa;
    }
}

