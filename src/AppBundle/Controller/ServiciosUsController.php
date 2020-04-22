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
    $idR = $post->idReserva;
    $persona = $em->getRepository('AppBundle:Persona')->find($idU);
    $servicio =$em->getRepository('AppBundle:Servicios')->find($idS);
    $reserva = $em->getRepository('AppBundle:Reserva')->find($idR); 
    $ServiciosUs->setPersona($persona);
    $ServiciosUs->setServicio($servicio);
    $ServiciosUs->setReserva($reserva);
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
    $persona = $em->getRepository('AppBundle:Persona')->find($request->get('persona'));
    $servicios = $em->getRepository('AppBundle:ServiciosUs')->findBy(['persona' => $persona , 'reserva' => $request->get('reserva')]);
    $lavanderia = $em->getRepository('AppBundle:ServiciosUs')->buscarTipo('lavanderia',$request->get('reserva') , $request->get('persona'));
    $totalLavanderia = 0;
    foreach ($lavanderia as $lav) {
      $totalLavanderia += $lav->getServicio()->getCosto(); 
    }
    $tienda = $em->getRepository('AppBundle:ServiciosUs')->buscarTipo('tienda',$request->get('reserva') , $request->get('persona'));
    $totalTienda = 0;
    foreach ($tienda as $tie) {
      $totalTienda += $tie->getServicio()->getCosto(); 
    }
    $roto = $em->getRepository('AppBundle:ServiciosUs')->buscarTipo('roto',$request->get('reserva') , $request->get('persona'));
    $totalRoto = 0;
    foreach ($roto as $ro) {
      $totalRoto += $ro->getServicio()->getCosto(); 
    }
    $serviciosJson=['data'=>[]];
    $totalServicios = 0;
    foreach ($servicios as $servicio) {
      $totalServicios += $servicio->getServicio()->getCosto();
      array_push(
        $serviciosJson['data'],
        [
          $servicio->getServicio()->getTipo(),
          $servicio->getServicio()->getServicio(),
          $servicio->getServicio()->getCosto(),
          '<button onclick="deleteItem('.$servicio->getId().')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> </button>'
        ]);
    }
    $serviciosJson['cantidades']= [
      'cantidadLavanderia' => count($lavanderia),
      'cantidadTienda' => count($tienda),
      'cantidadRoto' => count($roto),
      'totalLavanderia' => $totalLavanderia,
      'totalTienda' => $totalTienda,
      'totalRoto' => $totalRoto,
      'totalServicios' => $totalServicios,
    ];
    $view = $this->view($serviciosJson,200);
    return $this->handleView($view);
  }

  /**
  * @Route("api/serviciosUs/del", name="serviciosUsDelete")
  */
  public function delAction(Request $request )
  {
    $em =$this->getDoctrine()->getManager();
    $servicio = $em->getRepository('AppBundle:ServiciosUs')->find($request->get('id'));
    $em->remove($servicio);
    $em->flush();
    $view = $this->view($servicio,200);
    return $this->handleView($view);
  }

}
