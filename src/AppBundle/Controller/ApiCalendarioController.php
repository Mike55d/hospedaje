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
		$grupos = $em->getRepository('AppBundle:Grupo')->findAll(); 
		$users = $em->getRepository('AppBundle:User')->findAll(); 
		$reservas = $em->getRepository('AppBundle:Reserva')->findByStatus('Reservado'); 
		$view = $this->view($reservas,200);
		return $this->handleView($view);
	}


	/**
	* @Route("/grupos")
	*/
	public function grupos(Request $request)
	{
		$em =$this->getDoctrine()->getManager(); 
		$grupos = $em->getRepository('AppBundle:Grupo')->findAll(); 
		// $users = $em->getRepository('AppBundle:User')->findAll(); 
		// $reservas = $em->getRepository('AppBundle:Reserva')->findByStatus('Reservado'); 
		$view = $this->view($grupos,200);
		return $this->handleView($view);
	}

	/**
	* @Route("/solicitudesAprobadas")
	*/
	public function solicitudesAprobadas(Request $request)
	{
		$em =$this->getDoctrine()->getManager();
		$aprobadas = $em->getRepository('AppBundle:Reserva')->buscarGrupo($request->get('grupo'));
		// $grupos = $em->getRepository('AppBundle:Grupo')->findAll();
		// $users = $em->getRepository('AppBundle:User')->findAll(); 
		// $reservas = $em->getRepository('AppBundle:Reserva')->findByStatus('Reservado'); 
		$view = $this->view($aprobadas,200);
		return $this->handleView($view);
	}

	/**
	* @Route("/personas")
	*/
	public function personas(Request $request)
	{
		$em =$this->getDoctrine()->getManager();
		$personasReservas = $em->getRepository('AppBundle:ReservaPersona')->findByReserva($request->get('solicitud'));
		$personas = [];
		foreach ($personasReservas as $personasReserva) {
			$personas[]= $personasReserva->getPersona();
		}
		// $grupos = $em->getRepository('AppBundle:Grupo')->findAll();
		// $users = $em->getRepository('AppBundle:User')->findAll(); 
		// $reservas = $em->getRepository('AppBundle:Reserva')->findByStatus('Reservado'); 
		$view = $this->view($personas,200);
		return $this->handleView($view);
	}

	/**
	* @Route("/getFechaReserva")
	*/
	public function getFechaReserva(Request $request)
	{
		$em =$this->getDoctrine()->getManager(); 
		$reservaPersona = $em->getRepository('AppBundle:ReservaPersona')
		->findBy([$request->get('reserva'),$request->get('persona')]);
		$view = $this->view($reservaPersona,200);
		return $this->handleView($view);
	}


}
