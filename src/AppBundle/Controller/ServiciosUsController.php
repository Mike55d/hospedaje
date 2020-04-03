<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\ServiciosUs;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\AbstractFOSRestController;



class ServiciosUsController extends AbstractFOSRestController
{

    /**
     * @Route("api/serviciosUs/nuevo", name="serviciosUs")
     */
    public function nuevoAction(Request $request )
    {
        $em =$this->getDoctrine()->getManager(); 
        $ServiciosUs = new ServiciosUs();
        $post = json_decode($request->getContent());
        $idU = $post->idUsuario;
        $idS = $post->idServicio;
        $persona = $em->getRepository('AppBundle:Persona')->find($idU);
        $servicio =$em->getRepository('AppBundle:Servicios')->find($idS);
        $ServiciosUs->setPersona($persona);
        $ServiciosUs->setServicio($servicio);
        $em->persist($ServiciosUs);
        $em->flush();
        $view = $this->view($ServiciosUs,200);
        return $this->handleView($view);
    }

    /**
     * @Route("/api/getServicios", name="getServicios")
     */
    public function getServiciosAction(Request $request )
    {
        $em =$this->getDoctrine()->getManager(); 
        $ServiciosUs = new ServiciosUs();
        $persona = $em->getRepository('AppBundle:Persona')->find(1);
        $servicios = $em->getRepository('AppBundle:ServiciosUs')->findByPersona($persona);
        $serviciosJson=['data'=>[]];
        foreach ($servicios as $servicio) {
            array_push($serviciosJson['data'], [$servicio->getServicio()->getServicio(),$servicio->getServicio()->getCosto()]);
        }
        $view = $this->view($serviciosJson,200);
        return $this->handleView($view);
    }

}
