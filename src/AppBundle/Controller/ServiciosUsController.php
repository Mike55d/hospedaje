<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#use AppBundle\Form\ServiciosUsType;
use AppBundle\Entity\ServiciosUs;
use Symfony\Component\HttpFoundation\JsonResponse;


class ServiciosUsController extends Controller
{
   
    /**
     * @Route("/serviciosUs/nuevo", name="serviciosUs")
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
        return new JsonResponse(1);
    }
    
}
