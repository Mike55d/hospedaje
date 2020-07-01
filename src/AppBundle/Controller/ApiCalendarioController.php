<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Reservacion;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

/**
*@Route("api/calendario")
*/
class ApiCalendarioController extends AbstractFOSRestController
{
	/**
	* @Route("/newReserva")
	*/
	public function newAction(Request $request)
	{
		$em =$this->getDoctrine()->getManager();
		$reservacion = new Reservacion;
		$cama = $em->getRepository('AppBundle:Cama')->find($request->get('cama')); 
		$persona = $em->getRepository('AppBundle:Persona')->find($request->get('persona')); 
		$reserva = $em->getRepository('AppBundle:Reserva')->find($request->get('reserva'));
		$reservacion->setFechaInicio(new \Datetime($request->get('fechaInicio')));
		$reservacion->setFechaFin(new \Datetime($request->get('fechaFin')));
		$reservacion->setCama($cama);
		$reservacion->setReserva($reserva);
		$reservacion->setStatus('pendiente');
		$reservacion->setPersona($persona);
		$em->persist($reservacion);
		$em->flush();
		$view = $this->view(['id' =>$reservacion->getId()],200);
		return $this->handleView($view);
	}


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
		$reservaSinCama = [];
		foreach ($aprobadas as $reserv) {
			$personas = $em->getRepository('AppBundle:ReservaPersona')->findByReserva($reserv);
			$personaSinCama = null;
			foreach ($personas as $per) {
				$reservacion = $em->getRepository('AppBundle:Reservacion')->findByPersona($per->getPersona());
				if (!$reservacion) {
				$personaSinCama = $per;
				}
			}
			if ($personaSinCama) {
				$reservaSinCama[] = $reserv;
			}
		}
		$view = $this->view($reservaSinCama,200);
		return $this->handleView($view);
	}

	/**
	* @Route("/personas")
	*/
	public function personas(Request $request)
	{
		$em =$this->getDoctrine()->getManager();
		$reserva = $request->get('solicitud');
		$personasReservas = $em->getRepository('AppBundle:ReservaPersona')->findByReserva($reserva);
		$personas = [];
		foreach ($personasReservas as $personasReserva) {
			$exist = $em->getRepository('AppBundle:Reservacion')
			->findBy(['reserva'=>$reserva , 'persona'=>$personasReserva->getPersona()]);
			if(!$exist){
				$personas[]= $personasReserva->getPersona();
			}
		}
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

	
	/**
	* @Route("/editReserva", name="calendario_editReserva")
	*/
	public function editAction(Request $request)
	{
		$em =$this->getDoctrine()->getManager();
		$reservacion = $em->getRepository('AppBundle:Reservacion')->find($request->get('reservacion')); 
		$cama = $em->getRepository('AppBundle:Cama')->find($request->get('cama')); 
		$reservacion->setFechaInicio(new \Datetime($request->get('fechaInicio')));
		$reservacion->setFechaFin(new \Datetime($request->get('fechaFin')));
		$reservacion->setCama($cama);
		$status = $request->get('status');
		if($status){
			$reservacion->setStatus($status);
		}
		$em->flush();
		return new JsonResponse('ok');
	}

	/**
	* @Route("/delReserva" , name="calendario_delReserva")
	*/
	public function delAction(Request $request)
	{
		$em =$this->getDoctrine()->getManager(); 
		$reservacion = $em->getRepository('AppBundle:Reservacion')->find($request->get('reserva')); 
		$em->remove($reservacion);
		$em->flush();
		return new JsonResponse('ok');
	}


}
