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
	public function newAction()
	{
		$em =$this->getDoctrine()->getManager();
		$reservacion = new Reservacion;
		$cama = $em->getRepository('AppBundle:Cama')->find($request->get('cama')); 
		$persona = $em->getRepository('AppBundle:Persona')->find($request->get('persona')); 
		$reservacion->setFechaInicio($request->get('fechaInicio'));
		$reservacion->setFechaFin($request->get('fechaFin'));
		$reservacion->setCama($cama);
		$reservacion->setReserva($persona->getReserva());
		$reservacion->setPersona($persona);
		$em->persist($reservacion);
		$em->flush();
		return new JsonResponse('ok');
	}

	/**
	* @Route("/editReserva", name="calendario_editReserva")
	*/
	public function editAction()
	{
		$em =$this->getDoctrine()->getManager();
		$reservacion = $em->getRepository('AppBundle:Reservacion')->find($request->get('reservacion')); 
		$cama = $em->getRepository('AppBundle:Cama')->find($request->get('cama')); 
		$persona = $em->getRepository('AppBundle:Persona')->find($request->get('persona')); 
		$reservacion->setFechaInicio($request->get('fechaInicio'));
		$reservacion->setFechaFin($request->get('fechaFin'));
		$reservacion->setCama($cama);
		$reservacion->setReserva($persona->getReserva());
		$reservacion->setPersona($persona);
		$em->flush();
		return new JsonResponse('ok');
	}

	/**
	* @Route("/delReserva" , name="calendario_delReserva")
	*/
	public function delAction()
	{
		$em =$this->getDoctrine()->getManager(); 
		$reservacion = $em->getRepository('AppBundle:Reservacion')->find($request->get('reservacion')); 
		$em->remove($reservacion);
		$em->flush();
		return new JsonResponse('ok');
	}

}
