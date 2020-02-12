<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#use AppBundle\Form\ServiciosUsType;
use AppBundle\Entity\ServiciosUs;


class ServiciosUsController extends Controller
{
   
    /**
     * @Route("/serviciosUs/nuevo", name="serviciosUs")
     */
    public function nuevoAction(Request $request )
    {
        $msg = 'Ok' ;
        $em =$this->getDoctrine()->getManager(); 
        $ServiciosUs = new ServiciosUs();
        $post = json_decode($request->getContent());
        $idU = $post->idUsuario;
        $idS = $post->idServicio;
        if ($em->getRepository('AppBundle:User')->findBy(['id' => $idU])&$em->getRepository('AppBundle:Servicios')->findBy(['id' => $idS])) {
            $ServiciosUs->setIdUsuario($idU);
            $ServiciosUs->setIdServicio($idS);
            $em->persist($ServiciosUs);
            $em->flush();
        } else {
            $msg = 'No Existe el registro';
        }
        return new Response(json_encode(['msg'=>$msg]));
    }
    
}
