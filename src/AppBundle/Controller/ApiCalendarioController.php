<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;


/**
*@Route("api/calendario")
*/
class ApiCalendarioController extends AbstractFOSRestController
{
	/**
	* @Route("/reservaciones")
	*/
	public function reservacionesAction(Request $request)
	{
		$em =$this->getDoctrine()->getManager(); 
		// $data = json_decode($request->getContent(),true);
		// $camas = $em->getRepository('AppBundle:Cama')->findAll();
		$cuartos = $em->getRepository('AppBundle:Cuarto')->findAll();
		$cuartoCamasReservas = [];
		foreach ($cuartos as $cuarto) {
			$camasReservas = [];
			$camas = $em->getRepository('AppBundle:Cama')->findByCuarto($cuarto);
			foreach ($camas as $cama) {
				$reservaciones = $em->getRepository('AppBundle:Reservacion')
				->buscarFecha($request->get('mes'),$request->get('año'),$cama->getId());
				$camasReservas[] = ['cama'=>$cama ,'reservaciones'=> $reservaciones];
			}
			$cuartoCamasReservas[]=['cuarto'=> $cuarto ,'camas'=>$camasReservas];
		}
		$view = $this->view($cuartoCamasReservas,200);
		return $this->handleView($view);
	}
	/**
	* @Route("/datosFiltro")
	*/
	public function datosFiltroAction(Request $request)
	{
		$em =$this->getDoctrine()->getManager(); 
		// $data = json_decode($request->getContent(),true);
		// $camas = $em->getRepository('AppBundle:Cama')->findAll();
		$grupos = $em->getRepository('AppBundle:Grupo')->findAll(); 
		$users = $em->getRepository('AppBundle:User')->findAll(); 
		$reservaciones = $em->getRepository('AppBundle:Reserva')->findByStatus('Reservado'); 
		$datos = ['grupos'=> $grupos,'users' => $users ,'reservaciones'=>$reservaciones];
		$view = $this->view($datos,200);
		return $this->handleView($view);
	}
}
