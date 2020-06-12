<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Reservacion;

/**
* @Route("calendario")
*/
class CalendarioController extends Controller
{
	/**
	* @Route("/" , name="calendario_index")
	*/
	public function indexAction()
	{
		$em =$this->getDoctrine()->getManager(); 
		$camas = $em->getRepository('AppBundle:Cama')->findAll();
		$reservaciones = $em->getRepository('AppBundle:Reservacion')->findAll();
		return $this->render('AppBundle:Calendario:index.html.twig', array(
			'camas' => $camas,
			'reservaciones' => $reservaciones,
		));
	}

	/**
	* @Route("/newReserva", name="calendario_newReserva")
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
		return new JsonResponse(['id' =>$reservacion->getId()]);
	}

	/**
	* @Route("/editReserva", name="calendario_editReserva")
	*/
	public function editAction(Request $request)
	{
		$em =$this->getDoctrine()->getManager();
		$reservacion = $em->getRepository('AppBundle:Reservacion')->find($request->get('reservacion')); 
		$cama = $em->getRepository('AppBundle:Cama')->find($request->get('cama')); 
		$persona = $em->getRepository('AppBundle:Persona')->find($request->get('persona')); 
		$reservacion->setFechaInicio(new \Datetime($request->get('fechaInicio')));
		$reservacion->setFechaFin(new \Datetime($request->get('fechaFin')));
		$reservacion->setCama($cama);
		$reservacion->setPersona($persona);
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
